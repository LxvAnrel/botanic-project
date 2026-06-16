@extends('emails.layouts.flora')

@section('body')

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
  <td style="padding-bottom:28px;text-align:center;">
    <div style="display:inline-block;width:64px;height:64px;background-color:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:50%;text-align:center;line-height:64px;font-size:28px;">🍂</div>
  </td>
</tr>
<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <h1 style="margin:0;font-family:'Georgia',serif;font-size:24px;font-weight:400;color:#EDE0CC;">Conta excluída</h1>
  </td>
</tr>
<tr>
  <td style="padding-bottom:32px;text-align:center;">
    <p style="margin:0;font-size:13px;color:#7A8E72;">Olá, {{ $userName }} — até breve</p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:24px;">
    <p style="margin:0;font-size:14px;line-height:1.8;color:#9AA88E;">
      Sua conta na Flora foi <strong style="color:#EDE0CC;">permanentemente excluída</strong> em {{ now()->format('d/m/Y') }}. Todos os dados foram removidos dos nossos servidores.
    </p>
  </td>
</tr>

{{-- O que foi excluído --}}
<tr>
  <td style="padding-bottom:28px;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:rgba(239,68,68,0.04);border:1px solid rgba(239,68,68,0.1);border-radius:12px;padding:20px 24px;">
      <tr><td style="padding-bottom:14px;">
        <p style="margin:0;font-size:9px;letter-spacing:4px;color:#f87171;text-transform:uppercase;">Dados removidos</p>
      </td></tr>
      @foreach(['Diário Verde e plantas salvas','Histórico de cuidados (regas, adubações, podas)','Alertas e notificações','Conquistas, badges e XP','Dados de perfil e preferências'] as $item)
      <tr><td style="padding-bottom:8px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
          <td style="width:20px;font-size:10px;color:#f87171;vertical-align:top;padding-top:1px;">✕</td>
          <td style="font-size:12px;color:#7A8E72;">{{ $item }}</td>
        </tr></table>
      </td></tr>
      @endforeach
    </table>
  </td>
</tr>

<tr>
  <td style="padding-bottom:24px;">
    <p style="margin:0;font-size:14px;line-height:1.8;color:#9AA88E;">
      Se quiser voltar a cultivar com a Flora, pode criar uma nova conta a qualquer momento. Será sempre um prazer. 🌱
    </p>
  </td>
</tr>

<tr>
  <td style="text-align:center;">
    <a href="{{ url('/register') }}" style="display:inline-block;background-color:rgba(200,169,110,0.15);border:1px solid rgba(200,169,110,0.3);color:#C8A96E;font-size:11px;letter-spacing:3px;text-transform:uppercase;font-weight:600;padding:13px 32px;border-radius:100px;text-decoration:none;">Criar nova conta →</a>
  </td>
</tr>
</table>

@endsection
