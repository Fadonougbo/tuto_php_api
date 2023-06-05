<?php
namespace API\crud;

use API\crud\GlobaleAction;
use API\Helper;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class Create extends GlobaleAction
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __invoke():ResponseInterface
    {
        return $this->createContent();
    }

    public function createContent():ResponseInterface
    {
        $response=null;

        $body=file_get_contents("php://input");

        $decodeBody=json_decode($body,true);

        $valideKeys=Helper::selectValidKeys($decodeBody,["name","quantity"],true);

        if($valideKeys)
        {
            if(count($valideKeys)<2)
            {
                $response=$this->missingKeyMessage($valideKeys);
            }else 
            {

                if(!is_int($valideKeys["quantity"]))
                {
                    $response=new response(416,[],json_encode(["message"=>"Array key quantity should be number"]));
                }else 
                {
                    
                    $response=$this->insertData($valideKeys,$decodeBody);
        
                }
            }


        }else 
        {
            $response=new Response(416,[],json_encode(["message"=>"missing key name and quantity"]));
        }

       
        return $response;
    }

    /**
     * Message if one key is not available
     *
     * @param array $valideKeys
     * @return ResponseInterface
     */
    private function missingKeyMessage(array $valideKeys):ResponseInterface
    {
        $exeptedKey=!array_key_exists("name",$valideKeys)?"name":"quantity";
        return new response(416,[],json_encode(["message"=>"missing key $exeptedKey"]));
    }

    /**
     * Insert data in DB
     *
     * @param string $columns
     * @param string $values
     * @param array $decodeBody
     * @return ResponseInterface
     */
    private function insertData(array $valideKeys,array $decodeBody):ResponseInterface
    {
        $implodeValideKeys=implode(",",array_keys($valideKeys));

        $transformToString=array_map(function($el)
        {
            return "'$el'";
            
        },array_values($valideKeys));

        $implodeValues=implode(",",$transformToString);

        /*******************/

        $query="INSERT INTO api ($implodeValideKeys) VALUES ($implodeValues)";
        
        $req=$this->pdo->prepare($query);

        $queryResponse=$req->execute([]);

        if($queryResponse)
        {
            $mergeData=array_merge($decodeBody,["create"=>true]);

           return new Response(201,[],json_encode($mergeData));
        }else 
        {
            return new Response(500,[],json_encode(["message"=>"Data are not insert in DB"]));
        }
    }
}


?>