@extends('emails.layouts.flora')

@section('body')

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
  <td style="padding-bottom:28px;text-align:center;">
    <div style="display:inline-block;width:64px;height:64px;background-color:rgba(251,146,60,0.1);border:1px solid rgba(251,146,60,0.25);border-radius:50%;text-align:center;line-height:64px;font-size:28px;">🔥</div>
  </td>
</tr>
<tr>
  <td style="padding-bottom:6px;text-align:center;">
    <p style="margin:0;font-size:9px;letter-spacing:5px;color:#fb923c;text-transform:uppercase;">Sequência em risco</p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <h1 style="margin:0;font-family:'Georgia',serif;font-size:24px;font-weight:400;color:#EDE0CC;">{{ $streakDays }} dias de sequência</h1>
  </td>
</tr>
<tr>
  <td style="padding-bottom:32px;text-align:center;">
    <p style="margin:0;font-size:13px;color:#7A8E72;">Olá, {{ $user->name }} — ainda há tempo hoje</p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:24px;">
    <p style="margin:0;font-size:14px;line-height:1.8;color:#9AA88E;text-align:center;">
      Você acumulou <strong style="color:#EDE0CC;">{{ $streakDays }} dias</strong> cuidando das suas plantas sem parar. Mas ainda não registrou nenhum cuidado hoje — e a meia-noite está chegando.
    </p>
  </td>
</tr>

{{-- Barra de sequência --}}
<tr>
  <td style="padding-bottom:28px;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:rgba(251,146,60,0.06);border:1px solid rgba(251,146,60,0.15);border-radius:12px;padding:20px 24px;">
      <tr><td style="padding-bottom:12px;">
        <p style="margin:0;font-size:9px;letter-spacing:4px;color:#fb923c;text-transform:uppercase;">Sua sequência atual</p>
      </td></tr>
      <tr><td style="padding-bottom:12px;">
        <p style="margin:0;font-size:36px;color:#fb923c;font-family:'Georgia',serif;letter-spacing:-1px;">{{ $streakDays }} 🔥</p>
        <p style="margin:4px 0 0;font-size:12px;color:#7A8E72;">dias consecutivos de cuidados registrados</p>
      </td></tr>
      @if($streakDays >= 7)
      <tr><td>
        <p style="margin:0;font-size:11px;color:#3A5E2D;">✓ Você está no caminho para o badge <strong style="color:#C8A96E;">Regador Fiel</strong> (7 dias).</p>
      </td></tr>
      @endif
      @if($streakDays >= 25)
      <tr><td style="padding-top:6px;">
        <p style="margin:0;font-size:11px;color:#3A5E2D;">✓ Faltam apenas {{ 30 - $streakDays }} dias para <strong style="color:#C8A96E;">Sequência de Ouro</strong> (30 dias)!</p>
      </td></tr>
      @endif
    </table>
  </td>
</tr>

<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <a href="{{ route('dashboard') }}" style="display:inline-block;background-color:#C8A96E;color:#0B160A;font-size:11px;letter-spacing:3px;text-transform:uppercase;font-weight:700;padding:14px 36px;border-radius:100px;text-decoration:none;">Registrar cuidado agora →</a>
  </td>
</tr>
<tr>
  <td style="padding-top:10px;text-align:center;">
    <p style="margin:0;font-size:11px;color:#3A5E2D;">Qualquer cuidado vale — rega, adubação ou poda.</p>
  </td>
</tr>
</table>

@endsection
