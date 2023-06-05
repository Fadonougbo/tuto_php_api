<?php
namespace API\crud;

use API\crud\GlobaleAction;
use GuzzleHttp\Psr7\Response;
use PDOException;
use Psr\Http\Message\ResponseInterface;

class Read extends GlobaleAction
{

    public function __construct(private array $query)
    {
        parent::__construct();
    }

    public function __invoke():ResponseInterface
    {
        return $this->getAllArticles();
    }

    private function getAllArticles():ResponseInterface
    {
        $response=null;
        
        try
        {
            $req=$this->pdo->query("SELECT * FROM api");
            $data=$req->fetchAll();

            if($data)
            {
                $encodeData=json_encode($data);
                $response=new Response(200,[],$encodeData);
            }

        }catch(PDOException $e)
        {
            $message=json_encode(["message"=>$e->getMessage()]);
            $response=new Response(500,[],$message);
        }
        
        return $response;
    }

   
}


?>