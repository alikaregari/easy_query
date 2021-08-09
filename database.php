<?php
/**
 * Created by PhpStorm.
 * User: Ali Kargari
 * Date: 12/27/2018
 * Time: 9:37 PM
 */
class Database
{
    protected $pdo;
    private $server;
    private $username;
    private $password;
    private $database;
    public function __construct($server="localhost",$username="root",$password="",$database="ejirir_jahadifarjavani")
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
