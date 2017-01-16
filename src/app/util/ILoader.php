<?php
namespace App\util;

interface ILoader
{
    public function loadCrontabConfig();

    public function loadDaemonConfig();
}