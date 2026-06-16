@extends('emails.layouts.flora')

@section('body')

@php
  $icons  = ['rega' => '💧', 'adubacao' => '🌱', 'poda' => '✂'];
  $colors = ['rega' => '59,130,246', 'adubacao' => '122,142,114', 'poda' => '200,169,110'];
  $icon   = $icons[$tipo]  ?? '🌿';
  $rgb    = $colors[$tipo] ?? '200,169,110';
@endphp

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
  <td style="padding-bottom:28px;text-align:center;">
    <div style="display:inline-block;width:64px;height:64px;background-color:rgba({{ $rgb }},0.1);border:1px solid rgba({{ $rgb }},0.25);border-radius:50%;text-align:center;line-height:64px;font-size:28px;">{{ $icon }}</div>
  </td>
</tr>
<tr>
  <td style="padding-bottom:6px;text-align:center;">
    <p style="margin:0;font-size:9px;letter-spacing:5px;color:rgb({{ $rgb }});text-transform:uppercase;">Cuidado pendente</p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <h1 style="margin:0;font-family:'Georgia',serif;font-size:24px;font-weight:400;color:#EDE0CC;">
      {{ $plantaNome }} precisa de {{ \App\Models\CareLog::rotulo($tipo) }}
    </h1>
  </td>
</tr>
<tr>
  <td style="padding-bottom:32px;text-align:center;">
    <p style="margin:0;font-size:13px;color:#7A8E72;">Olá, {{ $user->name }} — {{ $diasAtraso }} {{ $diasAtraso == 1 ? 'dia' : 'dias' }} atrasado</p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:28px;">
    <p style="margin:0;font-size:14px;line-height:1.8;color:#9AA88E;">
      Sua planta <strong style="color:#EDE0CC;">{{ $plantaNome }}</strong> está com a {{ strtolower(\App\Models\CareLog::rotulo($tipo)) }} atrasada há <strong style="color:#EDE0CC;">{{ $diasAtraso }} {{ $diasAtraso == 1 ? 'dia' : 'dias' }}</strong>. Um pequeno cuidado agora garante uma planta mais saudável e rende XP no seu Diário Verde.
    </p>
  </td>
</tr>

{{-- Dica do cuidado --}}
<tr>
  <td style="padding-bottom:28px;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:rgba({{ $rgb }},0.05);border:1px solid rgba({{ $rgb }},0.12);border-radius:12px;padding:20px 24px;">
      <tr><td style="padding-bottom:12px;">
        <p style="margin:0;font-size:9px;letter-spacing:4px;color:rgb({{ $rgb }});text-transform:uppercase;">Sobre esse cuidado</p>
      </td></tr>
      @if($tipo === 'rega')
      <tr><td style="padding-bottom:8px;">
        <p style="margin:0;font-size:13px;color:#EDE0CC;">Como regar corretamente</p>
        <p style="margin:4px 0 0;font-size:12px;color:#7A8E72;line-height:1.6;">Regue até a água escorrer pelo fundo do vaso. Evite encharcar o solo — verifique se o substrato está seco antes de regar novamente.</p>
      </td></tr>
      @elseif($tipo === 'adubacao')
      <tr><td style="padding-bottom:8px;">
        <p style="margin:0;font-size:13px;color:#EDE0CC;">Como adubar corretamente</p>
        <p style="margin:4px 0 0;font-size:12px;color:#7A8E72;line-height:1.6;">Prefira adubo líquido diluído em água durante a rega. Na estação fria, reduza a frequência — as plantas crescem mais devagar.</p>
      </td></tr>
      @elseif($tipo === 'poda')
      <tr><td style="padding-bottom:8px;">
        <p style="margin:0;font-size:13px;color:#EDE0CC;">Como podar corretamente</p>
        <p style="margin:4px 0 0;font-size:12px;color:#7A8E72;line-height:1.6;">Use tesoura ou alicate limpos e afiados. Retire folhas amarelas, secas ou danificadas. Corte logo acima de um nó para estimular novos galhos.</p>
      </td></tr>
      @endif
    </table>
  </td>
</tr>

<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <a href="{{ route('dashboard') }}" style="display:inline-block;background-color:#C8A96E;color:#0B160A;font-size:11px;letter-spacing:3px;text-transform:uppercase;font-weight:700;padding:14px 36px;border-radius:100px;text-decoration:none;">Registrar cuidado →</a>
  </td>
</tr>
<tr>
  <td style="padding-top:10px;text-align:center;">
    <p style="margin:0;font-size:11px;color:#3A5E2D;">Cada cuidado registrado rende XP e mantém sua sequência ativa.</p>
  </td>
</tr>
</table>

@endsection
