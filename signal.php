<?php
/**
 * Created by PhpStorm.
 * User: yunfan
 * Date: 2017/1/9
 * Time: 10:39
 */

$workers = [];
$worker_num = 3;//创建的进程数

for($i=0;$i<$worker_num ; $i++){
    $process = new swoole_process('process');
    $pid = $process->start();
    $workers[$pid] = $process;
}

function process(swoole_process $process){// 第一个处理

    $process->exec('/bin/echo', [111]);
//    $process->write($process->pid);
//    sleep(10);
    echo $process->pid,"\t",$process->callback .PHP_EOL;

//    $process->exit(0);
}

//\swoole_process::signal(SIGTERM, function ($signo) {
//    echo "term end \n";
//});
//
//\swoole_process::signal(SIGCHLD, function ($signo) {
//
//    while ($ret = \swoole_process::wait(false)) {
//        var_dump($ret);
//    };
//});


