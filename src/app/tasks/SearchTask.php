<?php
namespace App\tasks;

//use App\util\FileLog;
use App\util\DB;

class SearchTask extends base\BaseTask implements base\ITask
{
    public function run($args=[])
    {
//        $cmd = $args['cmd'];
//        exec($cmd,$output,$status);
////        syslog(LOG_INFO, 'scanning game price : ' . date('Y-m-d H:i:s'));
////        file_put_contents('/tmp/20170109.log', 'scanning game price : ' . date('Y-m-d H:i:s') . "\r\n", FILE_APPEND);
//        file_put_contents(LOG_PATH, $output[0] . " [" . date('Y-m-d H:i:s') . "]" . "\r\n", FILE_APPEND);
//        echo "exit code :" . $status . "\n";
//        exit($status);

        echo "start at:" . date("Y-m-d H:i:s") . "\n";
        sleep(10);
        $sth = DB::getInstance()->prepare('select * from products where id = ?');
        $sth->execute([1]);
        $data = $sth->fetchAll();
        file_put_contents(LOG_PATH, json_encode($data) . " [" . date('Y-m-d H:i:s') . "]" . "\r\n", FILE_APPEND);
        echo "end at:" . date("Y-m-d H:i:s") . "\n";
        exit(0);
    }
}



