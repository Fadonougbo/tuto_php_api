<?php 

namespace API;

use \PDO;

class DB
{
    private ?PDO $pdo=null;

    public  function getPdoConnection():PDO
    {
        if (empty($this->pdo))
        {
            $this->pdo=new PDO("mysql:host=localhost;dbname={$_ENV['DB_NAME']}","{$_ENV['DB_USER']}","{$_ENV['DB_PASSWORD']}",
            [
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ
            ]);
        }

        return $this->pdo;
    }
}

?>