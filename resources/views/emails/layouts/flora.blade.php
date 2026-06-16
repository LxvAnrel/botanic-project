<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $subject ?? 'Flora' }}</title>
<style>
  body,table,td,a{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
  table,td{mso-table-lspace:0;mso-table-rspace:0}
  img{-ms-interpolation-mode:bicubic;border:0;outline:none;text-decoration:none}
  body{margin:0!important;padding:0!important;background-color:#0B160A}
  a{color:#C8A96E;text-decoration:none}
  a:hover{text-decoration:underline}
  .btn:hover{opacity:.9}
  @media only screen and (max-width:620px){
    .email-container{width:100%!important;margin:auto!important}
    .fluid{max-width:100%!important;height:auto!important}
    .stack-column,.stack-column-center{display:block!important;width:100%!important;max-width:100%!important}
    .pad{padding:24px!important}
  }
</style>
</head>
<body style="margin:0;padding:0;background-color:#0B160A;font-family:'Georgia',serif;">

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:#0B160A;">
<tr>
<td style="padding:32px 16px 0;">

  {{-- Container --}}
  <table class="email-container" role="presentation" cellspacing="0" cellpadding="0" border="0" width="560" style="margin:auto;max-width:560px;">

    {{-- Header / Logo --}}
    <tr>
      <td style="padding:0 0 28px;text-align:center;">
        <p style="margin:0;font-family:'Georgia',serif;font-size:28px;letter-spacing:10px;color:#C8A96E;text-transform:uppercase;font-weight:400;">Flora</p>
        <p style="margin:4px 0 0;font-size:9px;letter-spacing:5px;color:#3A5E2D;text-transform:uppercase;">Botânica Interativa</p>
      </td>
    </tr>

    {{-- Card principal --}}
    <tr>
      <td style="background-color:#131F11;border:1px solid rgba(200,169,110,0.12);border-radius:16px;padding:40px 40px 32px;" class="pad">
        @yield('body')
      </td>
    </tr>

    {{-- Rodapé --}}
    <tr>
      <td style="padding:28px 0 32px;text-align:center;">
        <p style="margin:0 0 8px;font-size:11px;color:#3A5E2D;letter-spacing:1px;">
          Você recebeu este e-mail porque tem uma conta na Flora.
        </p>
        <p style="margin:0;font-size:11px;color:#2A4A25;">
          &copy; {{ date('Y') }} Flora · Plataforma Botânica
        </p>
      </td>
    </tr>

  </table>
</td>
</tr>
</table>

</body>
</html>
