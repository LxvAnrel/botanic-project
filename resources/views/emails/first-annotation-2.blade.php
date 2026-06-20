@extends('emails.layouts.flora')

@section('body')

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
  <td style="padding-bottom:28px;text-align:center;">
    <div style="display:inline-block;width:64px;height:64px;background-color:rgba(45,106,45,0.15);border:1px solid rgba(45,106,45,0.3);border-radius:50%;text-align:center;line-height:64px;font-size:28px;">🌱</div>
  </td>
</tr>
<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <h1 style="margin:0;font-family:'Georgia',serif;font-size:24px;font-weight:400;color:#EDE0CC;letter-spacing:1px;">Uma semana. Seu jardim ainda espera.</h1>
  </td>
</tr>
<tr>
  <td style="padding-bottom:32px;text-align:center;">
    <p style="margin:0;font-size:13px;color:#7A8E72;font-style:italic;">plantas que não são cuidadas não crescem</p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:24px;">
    <p style="margin:0;font-size:14px;line-height:1.9;color:#9AA88E;">
      Olá, {{ $user->name }}. Já faz sete dias desde que você criou sua conta na Flora — e seu Diário Verde ainda não tem nenhuma planta.
    </p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:28px;">
    <p style="margin:0;font-size:14px;line-height:1.9;color:#9AA88E;">
      Sabemos que começos às vezes travam. Mas são só dois minutos: escolha uma planta que você tem em casa, adicione ao seu diário, e a Flora cuida do resto — alertas, lembretes, histórico de cuidados.
    </p>
  </td>
</tr>

{{-- Quote / destaque visual --}}
<tr>
  <td style="padding-bottom:28px;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-left:2px solid rgba(200,169,110,0.4);padding-left:0;">
      <tr>
        <td style="padding:16px 20px;">
          <p style="margin:0;font-family:'Georgia',serif;font-size:15px;font-style:italic;color:#C8A96E;line-height:1.7;">"A melhor hora para plantar era há 20 anos. A segunda melhor hora é agora."</p>
          <p style="margin:8px 0 0;font-size:10px;letter-spacing:2px;color:#3A5E2D;text-transform:uppercase;">— provérbio chinês</p>
        </td>
      </tr>
    </table>
  </td>
</tr>

{{-- Sugestão de plantas --}}
<tr>
  <td style="padding-bottom:8px;">
    <p style="margin:0;font-size:9px;letter-spacing:4px;color:#7A8E72;text-transform:uppercase;">Comece com uma dessas</p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:28px;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tr>
        <td width="33%" style="padding:8px 6px 8px 0;vertical-align:top;">
          <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:10px;">
            <tr><td style="padding:14px 12px;text-align:center;">
              <p style="margin:0 0 6px;font-size:20px;">🪴</p>
              <p style="margin:0;font-size:11px;color:#EDE0CC;">Costela-de-Adão</p>
              <p style="margin:4px 0 0;font-size:9px;color:#7A8E72;">Meia sombra</p>
            </td></tr>
          </table>
        </td>
        <td width="33%" style="padding:8px 3px;vertical-align:top;">
          <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:10px;">
            <tr><td style="padding:14px 12px;text-align:center;">
              <p style="margin:0 0 6px;font-size:20px;">🌵</p>
              <p style="margin:0;font-size:11px;color:#EDE0CC;">Cacto</p>
              <p style="margin:4px 0 0;font-size:9px;color:#7A8E72;">Sol pleno</p>
            </td></tr>
          </table>
        </td>
        <td width="33%" style="padding:8px 0 8px 6px;vertical-align:top;">
          <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:10px;">
            <tr><td style="padding:14px 12px;text-align:center;">
              <p style="margin:0 0 6px;font-size:20px;">🌿</p>
              <p style="margin:0;font-size:11px;color:#EDE0CC;">Espada-de-São-Jorge</p>
              <p style="margin:4px 0 0;font-size:9px;color:#7A8E72;">Qualquer luz</p>
            </td></tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>

<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <a href="{{ route('dashboard') }}" class="btn" style="display:inline-block;background-color:#C8A96E;color:#0B160A;font-size:11px;letter-spacing:3px;text-transform:uppercase;font-weight:700;padding:14px 36px;border-radius:100px;text-decoration:none;">Adicionar minha primeira planta →</a>
  </td>
</tr>
<tr>
  <td style="padding-top:12px;padding-bottom:4px;text-align:center;">
    <a href="{{ route('plants.index') }}" style="font-size:11px;letter-spacing:2px;color:#7A8E72;text-transform:uppercase;text-decoration:none;">ver o catálogo completo</a>
  </td>
</tr>
</table>

@endsection
