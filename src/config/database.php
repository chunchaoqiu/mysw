<?php
/**
 * Created by PhpStorm.
 * User: yunfan
 * Date: 2017/1/6
 * Time: 11:59
 */

return [
    'source'=>'file',
    'file'=>realpath(__DIR__ . '/console.config.php'),
    'redbeanphp'=>realpath(__DIR__ . '/rb.php'),
    'mysql'=>[
        'host'=>'localhost',
        'port'=>'3306',
        'db'=>'module_user',
        'table'=>'config',
        'user'=>'root',
        'password'=>'111111'
    ]
];