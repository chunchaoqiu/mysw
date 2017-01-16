<?php
/**
 * @package PHPKit.
 * @author: mawenpei
 * @date: 2016/3/3
 * @time: 18:44
 */

namespace App\tasks\base;



interface ITask
{
    public function run($arg=[]);
}
