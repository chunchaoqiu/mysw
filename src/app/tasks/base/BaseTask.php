<?php
/**
 * Created by PhpStorm.
 * User: yunfan
 * Date: 2017/1/6
 * Time: 11:02
 */
namespace App\tasks\base;

abstract class BaseTask {
    protected $worker;

    public function doing(\swoole_process $worker,$args='')
    {
        $this->worker = $worker;
        $this->run($args);
    }
}