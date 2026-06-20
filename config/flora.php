<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Emails de administração
    |--------------------------------------------------------------------------
    | Lista separada por vírgula em ADMIN_EMAIL. Usar config() em vez de env()
    | para que funcione corretamente com config:cache em produção.
    */
    'admin_emails' => array_filter(array_map('trim', explode(',', env('ADMIN_EMAIL', '')))),

    /*
    |--------------------------------------------------------------------------
    | Remetentes de email por finalidade
    |--------------------------------------------------------------------------
    |
    | Todos os enderecos sao do mesmo dominio verificado no Resend
    | (florabotanic.site). Mude o local-part (antes do @) por env se quiser.
    |
    */

    'mail' => [
        'seguranca' => [
            'address' => env('MAIL_FROM_SEGURANCA', 'seguranca@florabotanic.site'),
            'name' => 'Flora · Segurança',
        ],
        'acesso' => [
            'address' => env('MAIL_FROM_ACESSO', 'acesso@florabotanic.site'),
            'name' => 'Flora · Acesso',
        ],
        'alertas' => [
            'address' => env('MAIL_FROM_ALERTAS', 'alertas@florabotanic.site'),
            'name' => 'Flora · Alertas',
        ],
        'ola' => [
            'address' => env('MAIL_FROM_OLA', 'ola@florabotanic.site'),
            'name' => 'Flora',
        ],
        'contato' => [
            'address' => env('MAIL_FROM_CONTATO', 'contato@florabotanic.site'),
            'name' => 'Flora · Contato',
        ],
    ],

];
