<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'powermailrecaptcha',
    'description' => 'Google recaptcha extension for powermail',
    'category' => 'plugin',
    'version' => '5.2.1',
    'state' => 'stable',
    'author' => 'Powermail Development Team',
    'author_email' => 'alexander.kellner@in2code.de',
    'author_company' => 'in2code.de',
    'constraints' => [
        'depends' => [
            'powermail' => '8.0.0-',
            'typo3' => '10.0.0-12.4.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ]
];
