# 06 — Diário Verde e cuidados

## 1. O Diário Verde (relação N:N)

"Salvar uma planta" = criar um vínculo na pivô `plant_user`. Em
`PlantController::toggleFavorite`:

```php
if ($user->plants()->where('plant_id',$plant->id)->exists()) {
    $user->plants()->detach($plant);   // remove
    return response()->json(['added' => false]);
}
$user->plants()->attach($plant);       // adiciona
return response()->json(['added' => true]);
```

- `attach`/`detach` inserem/removem linha na pivô.
- O índice único `(user_id, plant_id)` impede duplicatas.
- A resposta é **JSON** (chamada via `fetch`), então o botão na página da planta
  alterna o estado **sem recarregar** (`toggleFavorite()` em `show.blade.php`).

O dashboard lista `auth()->user()->diarioVerde()->paginate(12)` (alias de
`plants()`), exibindo cada planta com "salva há X dias" (`$planta->pivot->created_at`,
graças ao `withTimestamps()`).

## 2. Dados de cuidado por planta

Duas colunas em `plants` definem o ritmo ideal:
`dias_entre_regas`, `dias_entre_adubacoes`. Quando ausentes, o model aplica uma
**heurística** baseada na luz:

```php
public function intervaloRega(): int {
    return $this->dias_entre_regas ?? match($this->habitat_luz) {
        'sol_pleno' => 3, 'sombra' => 7, default => 5,   // dias
    };
}
public function intervaloAdubacao(): int { return $this->dias_entre_adubacoes ?? 30; }
```

O seeder (`PlantSeeder`) preenche valores por espécie (ex.: suculentas regam a
cada ~10 dias) com fallback pela heurística.

## 3. Histórico de cuidados (`care_logs`)

Cada ação registrada é uma linha: `tipo ∈ {rega, adubacao, poda}` + `data`.
**Conceito:** *event log* — em vez de guardar só "última rega", guarda-se o
histórico completo; o estado atual é **derivado** consultando o log.

## 4. Cálculo de status — `app/Support/PlantCare.php`

Serviço puro (sem banco) que, dada a última data e o intervalo, calcula o estado:

```php
public static function status(?CarbonInterface $ultima, int $intervaloDias): array {
    if (!$ultima) return ['estado'=>'nunca','proxima'=>null,'dias'=>null];
    $proxima = $ultima->copy()->startOfDay()->addDays($intervaloDias);
    $dias = Carbon::today()->diffInDays($proxima, false);  // negativo = atrasado
    $estado = match(true) {
        $dias < 0   => 'atrasado',
        $dias <= 1  => 'em_breve',
        default     => 'em_dia',
    };
    return ['estado'=>$estado,'proxima'=>$proxima,'dias'=>$dias];
}
```

- `diffInDays(..., false)` retorna **com sinal**: positivo = dias até vencer,
  negativo = dias de atraso.
- `rotulo()` traduz para texto curto ("Em 3d", "Atrasada 2d", "Hoje").

> **Conceito — serviço puro / função determinística:** mesma entrada → mesma
> saída, sem efeitos colaterais. Fácil de testar e reusar (dashboard, página da
> planta e o comando de lembretes usam o mesmo cálculo).

## 5. Registro de cuidado sem reload (AJAX)

`CareController::store(Plant $plant)`:
1. valida `tipo`;
2. confirma que a planta está no Diário do usuário (`abort_unless … 403`);
3. cria o `CareLog` com `data = hoje`;
4. dispara a gamificação (`addXp`, `updateStreak`, `checkAllBadges` —
   [doc 08](08-gamificacao.md));
5. se a requisição **espera JSON** (`$request->expectsJson()`), devolve o estado
   recalculado (`carePayload`); senão, redireciona (fallback sem JS).

`carePayload` recomputa, para a planta, o status de rega/adubação, última poda e
o histórico recente — tudo já **formatado** para o front:

```php
'rega' => statusJson(PlantCare::status($ultima('rega'), $plant->intervaloRega())),
'historico' => $logs->take(8)->map(fn($l)=>['id'=>$l->id,'label'=>…,'data'=>…]),
```

No front (`plants/show.blade.php`), `careAction()` faz `fetch` POST, recebe o
JSON e **re-renderiza** os elementos (status, próxima data, histórico) via
`renderCare(data)` — sem recarregar a página. `careRemove()` faz o mesmo via
`DELETE /cuidado/{careLog}`.

## 6. Selo de rega no dashboard (evitando N+1)

`DashboardController::index` calcula o status de rega de cada card. Para não
fazer uma query por planta (*problema N+1*), busca a última rega de todas as
plantas da página em **uma** consulta agregada:

```php
$ultimasRegas = $user->careLogs()->where('tipo','rega')
    ->whereIn('plant_id',$plantIds)
    ->selectRaw('plant_id, MAX(data) as ultima')
    ->groupBy('plant_id')->pluck('ultima','plant_id');
```

Depois aplica `PlantCare::status` em memória por planta.

> **Conceito — problema N+1:** carregar uma lista e, para cada item, fazer outra
> query, gera N+1 consultas. A solução é buscar tudo de uma vez (agregação ou
> *eager loading*).

## 7. Como eu faria na mão

- **Favoritar:** `INSERT`/`DELETE` na tabela de junção; endpoint retornando JSON;
  `fetch` no front alternando o botão.
- **Status:** guardar logs e calcular `proxima = ultima + intervalo`; comparar
  com hoje. Exatamente a função `PlantCare::status`, que não depende de framework.
- **Sem reload:** endpoint que devolve JSON + um punhado de `document.getElementById().textContent = …`.
