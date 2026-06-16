@extends('emails.layouts.flora')

@section('body')

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
  <td style="padding-bottom:28px;text-align:center;">
    <div style="display:inline-block;width:64px;height:64px;background-color:rgba(200,169,110,0.1);border:1px solid rgba(200,169,110,0.25);border-radius:50%;text-align:center;line-height:64px;font-size:28px;">🌿</div>
  </td>
</tr>
<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <h1 style="margin:0;font-family:'Georgia',serif;font-size:26px;font-weight:400;color:#EDE0CC;letter-spacing:1px;">Bem-vindo(a), {{ $user->name }}</h1>
  </td>
</tr>
<tr>
  <td style="padding-bottom:32px;text-align:center;">
    <p style="margin:0;font-size:13px;color:#7A8E72;font-style:italic;">Seu jardim digital começa agora</p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:28px;">
    <p style="margin:0;font-size:14px;line-height:1.8;color:#9AA88E;">
      Sua conta está pronta. A Flora é seu companheiro botânico: explore mais de 20 espécies catalogadas, monte seu Diário Verde e receba alertas inteligentes de rega, adubação e poda.
    </p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:28px;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:rgba(200,169,110,0.05);border:1px solid rgba(200,169,110,0.1);border-radius:12px;">
      <tr><td style="padding:20px 24px 8px;">
        <p style="margin:0;font-size:9px;letter-spacing:4px;color:#C8A96E;text-transform:uppercase;">Por onde começar</p>
      </td></tr>
      <tr><td style="padding:10px 24px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
          <td style="width:28px;font-size:14px;color:#C8A96E;vertical-align:top;padding-top:1px;">◎</td>
          <td><p style="margin:0;font-size:13px;color:#EDE0CC;">Explore o catálogo</p><p style="margin:2px 0 0;font-size:11px;color:#7A8E72;">Filtre plantas por luz, porte e pet-friendly</p></td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:10px 24px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
          <td style="width:28px;font-size:14px;color:#C8A96E;vertical-align:top;padding-top:1px;">✦</td>
          <td><p style="margin:0;font-size:13px;color:#EDE0CC;">Faça o quiz</p><p style="margin:2px 0 0;font-size:11px;color:#7A8E72;">Receba a recomendação perfeita para seu espaço</p></td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:10px 24px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
          <td style="width:28px;font-size:14px;color:#C8A96E;vertical-align:top;padding-top:1px;">◉</td>
          <td><p style="margin:0;font-size:13px;color:#EDE0CC;">Monte seu Diário Verde</p><p style="margin:2px 0 0;font-size:11px;color:#7A8E72;">Salve as plantas que você tem ou quer ter</p></td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:10px 24px 20px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
          <td style="width:28px;font-size:14px;color:#C8A96E;vertical-align:top;padding-top:1px;">◇</td>
          <td><p style="margin:0;font-size:13px;color:#EDE0CC;">Ative os alertas</p><p style="margin:2px 0 0;font-size:11px;color:#7A8E72;">Notificações de poda, rega e adubação no tempo certo</p></td>
        </tr></table>
      </td></tr>
    </table>
  </td>
</tr>
<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <a href="{{ route('plants.index') }}" style="display:inline-block;background-color:#C8A96E;color:#0B160A;font-size:11px;letter-spacing:3px;text-transform:uppercase;font-weight:700;padding:14px 36px;border-radius:100px;text-decoration:none;">Explorar o catálogo →</a>
  </td>
</tr>
<tr>
  <td style="padding-top:12px;text-align:center;">
    <a href="{{ route('quiz') }}" style="font-size:11px;letter-spacing:2px;color:#7A8E72;text-transform:uppercase;text-decoration:none;">ou fazer o quiz de recomendação</a>
  </td>
</tr>
</table>

@endsection
