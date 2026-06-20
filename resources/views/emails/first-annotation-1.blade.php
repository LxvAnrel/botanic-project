@extends('emails.layouts.flora')

@section('body')

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
  <td style="padding-bottom:28px;text-align:center;">
    <div style="display:inline-block;width:64px;height:64px;background-color:rgba(200,169,110,0.1);border:1px solid rgba(200,169,110,0.25);border-radius:50%;text-align:center;line-height:64px;font-size:28px;">📖</div>
  </td>
</tr>
<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <h1 style="margin:0;font-family:'Georgia',serif;font-size:24px;font-weight:400;color:#EDE0CC;letter-spacing:1px;">Seu diário ainda está em branco</h1>
  </td>
</tr>
<tr>
  <td style="padding-bottom:32px;text-align:center;">
    <p style="margin:0;font-size:13px;color:#7A8E72;font-style:italic;">e a primeira anotação é sempre a mais especial</p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:28px;">
    <p style="margin:0;font-size:14px;line-height:1.9;color:#9AA88E;">
      Olá, {{ $user->name }}. Sua conta está pronta — mas o melhor da Flora começa quando você registra sua primeira planta no Diário Verde.
    </p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:28px;">
    <p style="margin:0;font-size:14px;line-height:1.9;color:#9AA88E;">
      É lá que os alertas de rega e adubação ganham vida, onde você acumula XP e desbloqueia conquistas. Uma anotação pequena hoje pode evitar uma planta perdida amanhã.
    </p>
  </td>
</tr>

{{-- Destaque: o que você ganha --}}
<tr>
  <td style="padding-bottom:28px;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:rgba(200,169,110,0.05);border:1px solid rgba(200,169,110,0.1);border-radius:12px;">
      <tr><td style="padding:20px 24px 8px;">
        <p style="margin:0;font-size:9px;letter-spacing:4px;color:#C8A96E;text-transform:uppercase;">Ao fazer sua primeira anotação</p>
      </td></tr>
      <tr><td style="padding:10px 24px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
          <td style="width:28px;font-size:14px;color:#C8A96E;vertical-align:top;padding-top:1px;">◎</td>
          <td><p style="margin:0;font-size:13px;color:#EDE0CC;">Alertas personalizados ativados</p><p style="margin:2px 0 0;font-size:11px;color:#7A8E72;">Lembretes de rega e adubação no horário certo</p></td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:10px 24px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
          <td style="width:28px;font-size:14px;color:#C8A96E;vertical-align:top;padding-top:1px;">✦</td>
          <td><p style="margin:0;font-size:13px;color:#EDE0CC;">Primeiros pontos de XP</p><p style="margin:2px 0 0;font-size:11px;color:#7A8E72;">Cada cuidado registrado sobe seu nível na comunidade</p></td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:10px 24px 20px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
          <td style="width:28px;font-size:14px;color:#C8A96E;vertical-align:top;padding-top:1px;">◇</td>
          <td><p style="margin:0;font-size:13px;color:#EDE0CC;">Conquista "Primeiro Broto" desbloqueada</p><p style="margin:2px 0 0;font-size:11px;color:#7A8E72;">O começo de uma coleção de badges botânicos</p></td>
        </tr></table>
      </td></tr>
    </table>
  </td>
</tr>

<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <a href="{{ route('dashboard') }}" class="btn" style="display:inline-block;background-color:#C8A96E;color:#0B160A;font-size:11px;letter-spacing:3px;text-transform:uppercase;font-weight:700;padding:14px 36px;border-radius:100px;text-decoration:none;">Fazer minha primeira anotação →</a>
  </td>
</tr>
<tr>
  <td style="padding-top:12px;padding-bottom:4px;text-align:center;">
    <a href="{{ route('plants.index') }}" style="font-size:11px;letter-spacing:2px;color:#7A8E72;text-transform:uppercase;text-decoration:none;">ou explorar o catálogo primeiro</a>
  </td>
</tr>
</table>

@endsection
