<?php
return [
    'setting' => [
        'sendgrid_secret' => [
            'label' => 'Clé secrète Sendgrid',
            'comment' => 'Entrez votre clé secrète API Sendgrid.'
        ],
        'autoconvert_html_to_plaintext' => [
            'label' => 'Convertit automatiquement le HTML en texte brut, si le texte brut est vide.',
            'comment' => 'Les messages avec des versions de corps en texte brut vides ne peuvent pas être envoyés via Sendgrid.'
        ],
    ],
];