<?php

return [

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
    ],

];
