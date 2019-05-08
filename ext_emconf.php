<?php


$EM_CONF[$_EXTKEY] = array(
    'title' => 'Instant Annotations',
    'description' => 'Create and Deploy your annotations from semantify.it to your typo3 website.',
    'category' => 'plugin',
    'author' => 'semantify.it',
    'author_company' => 'semantify.it',
    'author_email' => 'typo3@semantify.it',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.1.4',
    'constraints' => array(
        'depends' => array(
            'typo3' => '6.2.0-9.5.99',
        ),
        'conflicts' => array(
            'mayrhofen_annotator' => '0.1.0-9.9.9',
            'semantify_it' => '0.1.0-9.9.9'
        ),
        'suggests' => array(
        ),
    ),

);
