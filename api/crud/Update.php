<?php
namespace API\crud;

use API\crud\GlobaleAction;
use API\Helper;
use GuzzleHttp\Psr7\Response;
use PDOException;
use Psr\Http\Message\ResponseInterface;

class Update extends GlobaleAction
{

    private int $id;

    public function __construct(string $id)
    {
        $this->id=(int)$id;
        parent::__construct();
    }

    public function __invoke():ResponseInterface
    {
        return $this->updateContent();
    }

    private function updateContent():ResponseInterface
    {
        $response=null;

        $body=file_get_contents("php://input");

        $decodeBody=json_decode($body,true);

        $valideKeys=Helper::selectValidKeys($decodeBody,["name","quantity"],true);

        if(!empty($valideKeys))
        {
            if(isset($valideKeys["quantity"]) && !is_int($valideKeys["quantity"]))
            {
                $response=new response(416,[],json_encode(["message"=>"Array key quantity should be number"]));
            }else 
            { 
                $response=$this->updateData($valideKeys);
            }

        }else 
        {
            $response=new Response(416,[],json_encode(["message"=>"missing key name and quantity"]));
        }

       
        return $response;
    }


   /**
    * Update one article
    *
    * @param array $valideKeys
    * @return ResponseInterface
    */
    private function updateData(array $valideKeys):ResponseInterface
    {

        try
        {
            $currentArticle=parent::getElement($this->id);

            if($currentArticle)
            {
                $setQuery=Helper::generateUpdateSetQuery(array_keys($valideKeys));

                $params=array_merge($valideKeys,["id"=>$this->id]);
    
                $query="UPDATE api SET $setQuery WHERE id=:id";
    
                $req=$this->pdo->prepare($query);
    
                $queryResponse=$req->execute($params);

                if($queryResponse)
                {
                    $currentArticle=parent::getElement($this->id);
                    
                    $mergeData=array_merge([$currentArticle],["update"=>true]);
    
                    return new Response(200,[],json_encode($mergeData));
                
                }else 
                {
                    return new Response(500,[],json_encode(["message"=>"Data are not update in DB"]));
                }


            }else 
            {
                return new Response(404,[],json_encode(["message"=>"id:{$this->id} not exist"]));
            }

        }catch(PDOException $e)
        {
            return new Response(500,[],json_encode(["message"=>$e->getMessage()]));
        }
        
    }
}


?>