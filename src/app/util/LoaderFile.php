<?php
namespace App\util;

class LoaderFile implements ILoader
{
    private $config;

    public function __construct($options)
    {
        $this->config = include($options['file']);
    }
    public function loadCrontabConfig()
    {
        return $this->config['crontab'];
    }

    public function loadDaemonConfig()
    {
        return $this->config['daemon'];
    }
}
