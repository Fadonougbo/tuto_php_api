<?php
namespace API\crud;

use API\DB;
use \PDO;

Abstract class GlobaleAction
{
    public PDO $pdo;

    public function __construct()
    {
        $this->pdo=(new DB())->getPdoConnection();
    }

    protected function getElement(int $id)
    {
        $query="SELECT * FROM api WHERE id=:id";

        $req=$this->pdo->prepare($query);
        $req->execute(["id"=>$id]);

        return $req->fetch();
    }
}


?>