# 05 — Catálogo e Quiz (Livewire)

## 1. O conceito de reatividade server-side (Livewire)

Um componente Livewire é uma classe PHP (`app/Livewire/`) + uma view Blade
(`resources/views/livewire/`). O **estado público** da classe (propriedades) é
serializado e enviado ao navegador. Quando o usuário interage (digita, clica),
o Livewire envia uma requisição **AJAX** com o estado + a ação; o servidor
reexecuta o componente, gera o novo HTML e devolve um *diff* aplicado ao DOM.

> **Conceito:** é como um "React server-side" — você descreve a UI em função do
> estado; o framework cuida de sincronizar. Vantagem: zero JavaScript manual.

A página inclui o componente com `<livewire:plant-catalog />` (em
`plants/index.blade.php`). O layout carrega `@livewireScripts`.

## 2. Catálogo — `app/Livewire/PlantCatalog.php`

### Estado
```php
public $search = '';        // texto de busca
public $habitat = '';       // sol_pleno | meia_sombra | sombra
public $petFriendly = false;
public $size = '';          // pequeno | medio | grande
public $perPage = 12;       // paginação incremental
protected $queryString = ['search','habitat','petFriendly','size'];
```
`$queryString` **espelha** os filtros na URL (ex.: `?search=jiboia&habitat=sombra`)
— permite compartilhar/voltar ao mesmo estado (deep-linking).

### Ações (métodos públicos)
- `updatingSearch()` — *hook* disparado **antes** de `search` mudar; reseta a
  paginação para 12.
- `setHabitat`, `setSize`, `togglePet`, `clearFilters` — alteram filtros.
- `loadMore()` — aumenta `perPage` em 9 ("carregar mais").

### Render (a nível micro)
```php
$query = Plant::query();
if ($this->search)      $query->search($this->search);     // scope
if ($this->habitat)     $query->sunlight($this->habitat);
if ($this->petFriendly) $query->petFriendly();
if ($this->size)        $query->bySize(match…);            // pequeno=50, medio=150, grande=500 cm
$total  = (clone $query)->count();
$plants = $query->take($this->perPage)->get();
```
Cada interação reexecuta esse `render()`, refazendo a query com os filtros
atuais. Os **scopes** do model ([doc 03](03-modelagem-de-dados.md)) deixam a
montagem da consulta declarativa.

> Note o `(clone $query)->count()` antes do `take()`: conta o total **sem** o
> limite, para exibir "X de Y" e decidir se mostra "carregar mais".

## 3. Quiz — `app/Livewire/PlantQuiz.php`

Wizard de 4 passos que recomenda **uma** planta.

### Estado
```php
public $step = 1;            // passo atual
public $answers = [];        // respostas acumuladas
public $result = null;       // planta recomendada
private array $stepKeys = [1=>'hasPets',2=>'light',3=>'space',4=>'experience'];
```

### Navegação
- `nextStep()` valida que o passo atual foi respondido; avança ou, no passo 4,
  chama `calculateMatch()`.
- `previousStep()` volta; `resetQuiz()` zera tudo.
- `updatedAnswers()` limpa o erro quando o usuário seleciona algo.

### Algoritmo de recomendação (`calculateMatch`)
Sistema de **pontuação ponderada** sobre todas as plantas:

```
Para cada planta:
  score = 0
  se (tem pets) e (planta é tóxica)      → score = -1000   (descarta)
  se (luz da planta == luz desejada)     → score += 10
  se (porte <= limite do espaço)         → score += 5      (small=50, medium=150, large=300)
  se (experiência == iniciante)          → score += 2  senão += 5
  se score >= 0: candidata
Escolhe a planta com maior score (arsort + primeira chave).
```

> **Conceito — função de pontuação:** cada critério contribui com um peso; o
> peso negativo grande (`-1000`) implementa uma **restrição forte** (segurança
> de pets) que elimina candidatas, enquanto os demais são **preferências**.

## 4. Por que não há JavaScript aqui

Toda a lógica (filtros, validação de passos, recomendação) está em PHP no
servidor. O Livewire faz a ponte AJAX automaticamente. O catálogo e o quiz
funcionam sem você escrever nenhuma linha de JS.

## 5. Como eu faria na mão

- **Catálogo:** um formulário GET com os filtros; o servidor lê `$_GET`, monta a
  consulta SQL com `WHERE` condicionais e `LIMIT`, e re-renderiza a lista.
  "Carregar mais" seria um parâmetro `?perPage=` ou paginação clássica. O
  Livewire só evita o *reload* completo, atualizando via AJAX.
- **Quiz:** guardaria as respostas na sessão entre passos (ou em campos hidden),
  e no fim rodaria o mesmo laço de pontuação em PHP. A diferença do Livewire é
  manter o estado em memória do componente e atualizar a tela sem recarregar.
