<?php
/**
 * Created by PhpStorm.
 * User: yunfan
 * Date: 2017/1/9
 * Time: 10:04
 */


\swoole_timer_after((60-date("s"))*1000, function(){
    \swoole_timer_tick(60000,function($id){
        echo "after tick \n";
    });
});