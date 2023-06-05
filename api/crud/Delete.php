<?php
namespace API\crud;

use API\crud\GlobaleAction;
use GuzzleHttp\Psr7\Response;
use PDOException;
use Psr\Http\Message\ResponseInterface;

class Delete extends GlobaleAction
{

    private int $id;

    public function __construct(string $id)
    {
        $this->id=(int)$id;

        parent::__construct();
    }

    public function __invoke():ResponseInterface
    {
        return $this->deleteContent();
    }

    private function deleteContent():ResponseInterface
    {
        $response=null;

        if($this->id && is_int($this->id) )
        {
            $response=$this->deleteData($this->id);
        }else 
        {
            $response=new Response(416,[],json_encode(["message"=>"id undefined"]));
        }

        return $response;
    }


    private function deleteData(int $id):ResponseInterface
    {

        try
        {

            $query="DELETE FROM api  WHERE id=:id";

            $req=$this->pdo->prepare($query);

            $queryResponse=$req->execute(["id"=>$id]);

            if($queryResponse)
            {

                return new Response(200,[],json_encode(["delete"=>true]));
            
            }else 
            {
                return new Response(500,[],json_encode(["message"=>"Data are not delete in DB"]));
            }

        }catch(PDOException $e)
        {
            return new Response(500,[],json_encode(["message"=>$e->getMessage()]));
        }
        
    }
}


?>