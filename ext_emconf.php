<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'powermailrecaptcha',
    'description' => 'Google recaptcha extension for powermail',
    'category' => 'plugin',
    'version' => '5.0.3',
    'state' => 'stable',
    'author' => 'Powermail Development Team',
    'author_email' => 'alexander.kellner@in2code.de',
    'author_company' => 'in2code.de',
    'constraints' => [
        'depends' => [
            'powermail' => '8.0.0-9.99.99',
            'typo3' => '10.0.0-11.5.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ]
];
