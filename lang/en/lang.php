<?php
return [
    'setting' => [
        'sendgrid_secret' => [
            'label' => 'Sendgrid Secret.',
            'comment' => 'Enter your Sendgrid API secret key.'
        ],
        'autoconvert_html_to_plaintext' => [
            'label' => 'Automatically convert HTML to plain text, if plain text is empty.',
            'comment' => 'Messages with empty plain text body versions cannot be sent through Sendgrid.'
        ],
    ],
];