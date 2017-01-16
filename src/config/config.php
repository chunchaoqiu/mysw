<?php
/**
 * Created by PhpStorm.
 * User: yunfan
 * Date: 2017/1/6
 * Time: 11:57
 */

return [
    'crontab'=>[
        [
            'taskname'=>'php',
            'rule'=>'*/1 * * * * *',
            'unique'=>2,
            'execute'=>'\\PHPKit\\Console\\Tasks\\EchoTask',
            'args'=>[
                'cmd'=>'php -v',
                'ext'=>[]
            ]
        ]
    ],
    'daemon'=>[
        ['className'=>'\\PHPKit\\Console\\Workers\\PushMessageWorker','processNum'=>'5','queue'=>[
            'host'=>'127.0.0.1','port'=>'11300','tube'=>'testtube'
        ]]
    ]
];