<?php

return [
    'source'=>'file',
    'file'=>realpath(__DIR__ . '/console.config.php'),
    'redbeanphp'=>realpath(__DIR__ . '/rb.php'),
    'mysql'=>[
        'host'=>'127.0.0.1',
        'port'=>'3306',
        'db'=>'test',
        'user'=>'root',
        'password'=>'123456'
    ]
];
