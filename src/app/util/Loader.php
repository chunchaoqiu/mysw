<?php
namespace App\util;

class Loader
{
    private static $instance;

    private $handle;

    private function __construct($options=null)
    {
        if(!$options){
            if(!defined('PHPKIT_CONSOLE_CONFIG_PATH')){
//                syslog(LOG_ERR,'console config file is not exists');
                echo "console config file is not exists \n";
                exit(1);
            }

            $options = include(PHPKIT_CONSOLE_CONFIG_PATH);
        }

        if(isset($options['source'])){
            switch($options['source']){
                case 'file':
                    $this->handle = new LoaderFile($options);
                    break;
                case 'mysql':
                    $this->handle = new LoaderDb($options);
                    break;
            }
        }
    }

    public static function getInstance($options = null)
    {
        if(!self::$instance){
            self::$instance = new Loader($options);
        }
        return self::$instance;
    }

    public function config()
    {
        switch(CURRENT_RUN_MODE){
            case 'daeman':
                return self::loadDaemonConfig();
                break;
            case 'crontab':
                return self::loadCrontabConfig();
                break;
        }
    }

    protected function loadCrontabConfig()
    {
        return $this->handle->loadCrontabConfig();
    }

    protected function loadDaemonConfig()
    {
        return $this->handle->loadDaemonConfig();
    }
}