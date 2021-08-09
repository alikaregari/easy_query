<?php
/**
 * Created by PhpStorm.
 * User: Ali Kargari
 */
class Database
{
    protected $pdo;
    private $server;
    private $username;
    private $password;
    private $database;
    public function __construct($server="localhost",$username="root",$password="",$database="")
    {
        $this->server=$server;
        $this->username=$username;
        $this->password=$password;
        $this->database=$database;
        $this->connection();
    }
    public function connection()
    {
        try
        {
            $this->pdo=new \PDO("mysql:host={$this->server};dbname={$this->database}","$this->username",$this->password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        }
        catch (\Exception $e)
        {
           die($e->getMessage());
        }
    }
}
