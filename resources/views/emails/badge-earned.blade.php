@extends('emails.layouts.flora')

@section('body')

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
  <td style="padding-bottom:28px;text-align:center;">
    <div style="display:inline-block;width:72px;height:72px;background-color:rgba(200,169,110,0.12);border:1px solid rgba(200,169,110,0.3);border-radius:50%;text-align:center;line-height:72px;font-size:32px;">{{ $badge['icon'] }}</div>
  </td>
</tr>
<tr>
  <td style="padding-bottom:6px;text-align:center;">
    <p style="margin:0;font-size:9px;letter-spacing:5px;color:#C8A96E;text-transform:uppercase;">Nova conquista desbloqueada</p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <h1 style="margin:0;font-family:'Georgia',serif;font-size:26px;font-weight:400;color:#EDE0CC;">{{ $badge['title'] }}</h1>
  </td>
</tr>
<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <p style="margin:0;font-size:13px;color:#7A8E72;font-style:italic;">{{ $badge['desc'] }}</p>
  </td>
</tr>
<tr>
  <td style="padding:16px 0 28px;text-align:center;">
    <span style="display:inline-block;background-color:rgba(200,169,110,0.15);border:1px solid rgba(200,169,110,0.3);color:#C8A96E;font-size:10px;letter-spacing:3px;text-transform:uppercase;padding:6px 16px;border-radius:100px;">+{{ $badge['xp'] }} XP</span>
  </td>
</tr>
<tr>
  <td style="padding-bottom:28px;">
    <p style="margin:0;font-size:14px;line-height:1.8;color:#9AA88E;text-align:center;">
      Parabéns, {{ $user->name }}! Você desbloqueou mais uma conquista no seu Diário Verde. Continue cuidando das suas plantas para ganhar novos badges e subir de nível.
    </p>
  </td>
</tr>

{{-- Progresso do usuário --}}
<tr>
  <td style="padding-bottom:28px;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:rgba(200,169,110,0.05);border:1px solid rgba(200,169,110,0.1);border-radius:12px;padding:20px 24px;">
      <tr><td style="padding-bottom:14px;">
        <p style="margin:0;font-size:9px;letter-spacing:4px;color:#C8A96E;text-transform:uppercase;">Seu progresso</p>
      </td></tr>
      <tr><td style="padding-bottom:10px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
          <td style="font-size:13px;color:#EDE0CC;">{{ $levelLabel }}</td>
          <td style="text-align:right;font-size:11px;color:#7A8E72;">{{ $totalXp }} XP total</td>
        </tr></table>
      </td></tr>
      <tr><td>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
          <td style="background-color:rgba(255,255,255,0.05);border-radius:100px;height:6px;overflow:hidden;">
            <div style="background-color:#C8A96E;width:{{ min($levelPercent, 100) }}%;height:6px;border-radius:100px;"></div>
          </td>
        </tr></table>
      </td></tr>
      @if($badgesEarned > 1)
      <tr><td style="padding-top:10px;">
        <p style="margin:0;font-size:11px;color:#7A8E72;">{{ $badgesEarned }} conquistas desbloqueadas · {{ $streakDays }} dias de sequência</p>
      </td></tr>
      @endif
    </table>
  </td>
</tr>

<tr>
  <td style="text-align:center;">
    <a href="{{ route('conquistas') }}" style="display:inline-block;background-color:#C8A96E;color:#0B160A;font-size:11px;letter-spacing:3px;text-transform:uppercase;font-weight:700;padding:14px 36px;border-radius:100px;text-decoration:none;">Ver minhas conquistas →</a>
  </td>
</tr>
</table>

@endsection
