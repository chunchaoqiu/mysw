<?php
namespace App\util;

class DB
{

    static $_instance;
    private $table;
    private $columns;

    public static function getInstance()
    {
        $config = include(PHPKIT_CONSOLE_CONFIG_PATH);
        $dbname = $config['mysql']['db'];
        $host = $config['mysql']['host'];
        $port = $config['mysql']['port'];
        $username = $config['mysql']['user'];
        $password = $config['mysql']['password'];
        $dsn = "mysql:dbname={$dbname};host={$host};port={$port}";

        if (empty(self::$_instance))
        {
            self::$_instance = new \PDO($dsn, $username, $password);
        }
        return self::$_instance;
    }

//    public function query(){
//
//
//
//    }
//
//    public function table($table){
//        $this->table = $table;
//    }
//
//    public function select($columns){
//        $this->columns = $columns;
//    }
//
//    public function where($args){
//
//    }
//
//    public static function flush()
//    {
//        self::getInstance()->flush();
//    }
//
//    /**
//     *
//     */
//    public static function log($value)
//    {
//        $text="";
//        $text .= is_scalar($value) ? $value : json_encode($value);
//        self::getInstance()->put($text);
//    }

}