@extends('emails.layouts.flora')

@section('body')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
  <td style="padding-bottom:24px;">
    <p style="margin:0;font-size:14px;color:#7A8E72;">Olá, {{ $user->name }}.</p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:32px;">
    <div style="font-size:14px;line-height:1.9;color:#9AA88E;">{!! nl2br(e($corpo)) !!}</div>
  </td>
</tr>
<tr>
  <td style="text-align:center;">
    <a href="{{ url('/dashboard') }}" style="display:inline-block;background-color:#C8A96E;color:#0B160A;font-size:11px;letter-spacing:3px;text-transform:uppercase;font-weight:700;padding:14px 36px;border-radius:100px;text-decoration:none;">Acessar o Flora →</a>
  </td>
</tr>
</table>
@endsection
