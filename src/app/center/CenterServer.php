<?php

namespace App\center;

use App\util\Loader;
use App\util\ParseCrontab;
use App\util\TickTable;

class CenterServer {

    public static $pid;
    public static $pid_file;
    public static $process_name;
    public static $child_process_name;
    public static $worker_list = [];
    protected static $unique_task_list;

    public function __construct()
    {




    }

    public static function start($process_name)
    {

        if(file_exists(self::$pid_file)){
            syslog(LOG_INFO,'pid file ['.self::$pid_file.'] is exists');
            return;
        }

        if($process_name){
            self::$process_name = $process_name;
            self::$child_process_name = $process_name . '_child_worker';
        }else{
            self::$process_name = 'swoole_' . CURRENT_RUN_MODE . '_master_worker';
            self::$child_process_name = 'swoole_' . CURRENT_RUN_MODE . '_child_worker';
        }

        if(empty(self::$pid_file)){
            self::$pid_file = '/tmp/' . self::$process_name . '.pid';
        }

//        \swoole_process::daemon();
        \swoole_set_process_name(self::$process_name);

        self::get_pid();
        self::write_pid();
        self::registerSignal();
//        self::loadConfig();
        self::registerTimer();

//        defined('PHPKIT_RUN_DEBUG') && syslog(LOG_INFO,self::$process_name . ' start success');
    }

    /**
     * ֹͣ停止任务
     */
    public static function stop()
    {
        $pid = file_get_contents(self::$pid_file);
        if($pid){
            if(\swoole_process::kill($pid,0)){
                \swoole_process::kill($pid,SIGTERM);
            }else{
                @unlink(self::$pid_file);
            }
            defined('PHPKIT_RUN_DEBUG') && syslog(LOG_INFO,self::$process_name . ' exit success');
        }
    }

    public static function registerSignal()
    {

        \swoole_process::signal(SIGTERM, function ($signo) {
            echo "term end \n";
            self::exit2p( 'master process [' . self::$process_name . '] exit');
        });

        \swoole_process::signal(SIGCHLD, function ($signo) {

            while ($ret = \swoole_process::wait(false)) {
                var_dump($ret);

                $pid = $ret['pid'];
                if (isset(self::$worker_list[$pid])) {
                    $task = self::$worker_list[$pid];
                    $id = $task['id'];
                    $task['process']->close();
                    unset(self::$worker_list[$pid]);
                    if (isset(self::$unique_task_list[$id]) && self::$unique_task_list[$id] > 0) {
                        self::$unique_task_list[$id]--;
                    }
//                    defined('PHPKIT_RUN_DEBUG') && syslog(LOG_INFO,'child process exit:' . $pid);
                }
            };
        });

        \swoole_process::signal(SIGUSR1, function ($signo) {
            //TODO something
        });

        echo "task begin \r\n";

//        defined('PHPKIT_RUN_DEBUG') && syslog(LOG_INFO,'register signal success');
    }

    public static function registerTimer()
    {
        \swoole_timer_after((60-date("s"))*1000, function(){
            \swoole_timer_tick(60000,function($id){
                echo "start load \n";
                self::loadConfig();
//                defined('PHPKIT_RUN_DEBUG') && syslog(LOG_INFO,'reload config success');
            });
        });

        \swoole_timer_tick(1000,function($id){
            echo "tick \n";
            self::loadTask($id);
        });
    }

    /**
     * @var 退出处理
     */
    public static function exit2p($message)
    {
        @unlink(self::$pid_file);
        echo "mission out \r\n";
        exit();
    }

    public static function loadConfig()
    {
        $time = time();
        $config = Loader::getInstance()->config();
        foreach($config as $id=>$task){
            $ret = ParseCrontab::parse($task["rule"], $time);
            if ($ret === false) {

            } elseif (!empty($ret)) {
                TickTable::set_task($ret, array_merge($task, array("id" => $id)));
            }
        }

    }

    //载入任务
    public static function loadTask($timer_id)
    {
        $tasks = TickTable::get_task();
        if(empty($tasks)) return false;
        //defined('PHPKIT_RUN_DEBUG') && syslog(LOG_DEBUG,var_export($tasks,true));
        foreach($tasks as $task){
            if(isset($task['unique']) && $task['unique']){
                if (isset(self::$unique_task_list[$task["id"]]) && (self::$unique_task_list[$task["id"]] >= $task["unique"])) {
                    continue;
                }
                self::$unique_task_list[$task["id"]] = isset(self::$unique_task_list[$task["id"]]) ? (self::$unique_task_list[$task["id"]] + 1) : 0;
            }
            self::createTaskProcess($task['id'],$task);
        }
    }

    public static function createTaskProcess($id,$task)
    {
        $className = $task['execute'];
        $reflector = new \ReflectionClass($className);
        if(!$reflector->implementsInterface("App\\Tasks\\base\\ITask")){
            self::stop();
        }else{
            $process = new \swoole_process(function($worker)use($className,$task){
                $worker->name(self::$child_process_name . str_replace('\\','_',$className));
                $handler = new $className();
                $handler->doing($worker,$task['args']);
                $worker->exit(1);
            });
            $pid = $process->start();
            self::$worker_list[$pid] = [
                'className'=>$className,
                'id'=>$id,
                'process'=>$process,
                'task'=>$task
            ];
//            defined('PHPKIT_RUN_DEBUG') && syslog(LOG_INFO,'create child process success:' . $pid);
        }
    }

    /**
     * 获取当前进程pid
     */
    protected static function get_pid()
    {
        self::$pid = posix_getpid();
        echo self::$pid . "\n";
    }

    /**
     * 记录当前进程pid
     */
    protected static function write_pid()
    {
        file_put_contents(self::$pid_file,self::$pid);
    }

}













