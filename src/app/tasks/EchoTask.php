<?php
namespace App\tasks;

use App\util\FileLog;

class EchoTask extends base\BaseTask implements base\ITask
{
    public function run($args=[])
    {
        $cmd = $args['cmd'];
        exec($cmd,$output,$status);
//        syslog(LOG_INFO, 'scanning game price : ' . date('Y-m-d H:i:s'));
//        file_put_contents('/tmp/20170109.log', 'scanning game price : ' . date('Y-m-d H:i:s') . "\r\n", FILE_APPEND);
        file_put_contents(LOG_PATH, $output[0] . " [" . date('Y-m-d H:i:s') . "]" . "\r\n", FILE_APPEND);
        echo "exit code :" . $status . "\n";
        exit($status);
    }
}



