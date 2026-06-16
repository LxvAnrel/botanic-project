@extends('emails.layouts.flora')

@section('body')

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
  <td style="padding-bottom:28px;text-align:center;">
    <div style="display:inline-block;width:64px;height:64px;background-color:rgba(251,191,36,0.08);border:1px solid rgba(251,191,36,0.2);border-radius:50%;text-align:center;line-height:64px;font-size:28px;">⏳</div>
  </td>
</tr>
<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <h1 style="margin:0;font-family:'Georgia',serif;font-size:24px;font-weight:400;color:#EDE0CC;">Conta agendada para exclusão</h1>
  </td>
</tr>
<tr>
  <td style="padding-bottom:32px;text-align:center;">
    <p style="margin:0;font-size:13px;color:#7A8E72;">Olá, {{ $user->name }} — você ainda tem 30 dias</p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:24px;">
    <p style="margin:0;font-size:14px;line-height:1.8;color:#9AA88E;">
      Recebemos sua solicitação de exclusão. Sua conta ficará pausada por <strong style="color:#EDE0CC;">30 dias</strong>. Se mudar de ideia, basta clicar no botão abaixo — tudo fica preservado.
    </p>
  </td>
</tr>

{{-- Timeline --}}
<tr>
  <td style="padding-bottom:28px;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:rgba(200,169,110,0.05);border:1px solid rgba(200,169,110,0.1);border-radius:12px;padding:20px 24px;">
      <tr><td style="padding-bottom:16px;">
        <p style="margin:0;font-size:9px;letter-spacing:4px;color:#C8A96E;text-transform:uppercase;">O que acontece agora</p>
      </td></tr>
      <tr><td style="padding-bottom:14px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
          <td style="width:28px;height:28px;background-color:rgba(200,169,110,0.15);border-radius:50%;text-align:center;vertical-align:middle;font-size:10px;font-weight:700;color:#C8A96E;">1</td>
          <td style="padding-left:12px;">
            <p style="margin:0;font-size:13px;color:#EDE0CC;">Conta pausada por 30 dias</p>
            <p style="margin:2px 0 0;font-size:11px;color:#7A8E72;">Seus dados ficam preservados até {{ $expiresAt }}</p>
          </td>
        </tr></table>
      </td></tr>
      <tr><td style="padding-bottom:14px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
          <td style="width:28px;height:28px;background-color:rgba(200,169,110,0.15);border-radius:50%;text-align:center;vertical-align:middle;font-size:10px;font-weight:700;color:#C8A96E;">2</td>
          <td style="padding-left:12px;">
            <p style="margin:0;font-size:13px;color:#EDE0CC;">Clique abaixo para reativar</p>
            <p style="margin:2px 0 0;font-size:11px;color:#7A8E72;">O link é seguro e expira junto com o prazo</p>
          </td>
        </tr></table>
      </td></tr>
      <tr><td>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
          <td style="width:28px;height:28px;background-color:rgba(239,68,68,0.15);border-radius:50%;text-align:center;vertical-align:middle;font-size:10px;font-weight:700;color:#f87171;">3</td>
          <td style="padding-left:12px;">
            <p style="margin:0;font-size:13px;color:#EDE0CC;">Exclusão permanente após 30 dias</p>
            <p style="margin:2px 0 0;font-size:11px;color:#7A8E72;">Diário Verde, badges, XP e cuidados serão apagados</p>
          </td>
        </tr></table>
      </td></tr>
    </table>
  </td>
</tr>

{{-- CTA --}}
<tr>
  <td style="padding-bottom:20px;text-align:center;">
    <a href="{{ $reactivationUrl }}" style="display:inline-block;background-color:#C8A96E;color:#0B160A;font-size:11px;letter-spacing:3px;text-transform:uppercase;font-weight:700;padding:14px 36px;border-radius:100px;text-decoration:none;">Reativar minha conta →</a>
  </td>
</tr>
<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <p style="margin:0;font-size:11px;color:#3A5E2D;">Se não foi você, reative e altere sua senha imediatamente.</p>
  </td>
</tr>
</table>

@endsection
