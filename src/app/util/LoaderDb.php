<?php
namespace App\util;

class LoaderDb implements ILoader
{
    public function __construct($options)
    {
        //require $options['redbeanphp'];
        //\R::setup('mysql:host='.$options['mysql']['host'].';port='.$options['mysql']['port'].'dbname='.$options['mysql']['db'],$options['mysql']['user'],$options['mysql']['password']);
    }
    public function loadCrontabConfig()
    {

    }

    public function loadDaemonConfig()
    {

    }
}