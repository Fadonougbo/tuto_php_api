<?php

use API\crud\Create;
use API\crud\Delete;
use API\crud\Read;
use API\crud\Update;
use API\JwtVerification;
use Firebase\JWT\JWT;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;

use function Http\Response\send;

$request=ServerRequest::fromGlobals();

$response=new Response();

$method=$request->getMethod();

$id=$id=$match["params"]["id"]??null;

$serverQuery=$request->getQueryParams();


$isValide=(new JwtVerification())->tokenIsValide($request);

if(!$isValide)
{
    $response=new Response(200,["Content-Type"=>"application/json"],json_encode(["message"=>"Token is invalide"]));

    send($response);

    die();
}

if($method==="GET")
{
    $read=new Read($serverQuery);
    $response=$read();
    /**
     * Pour générer un token
     */
    /* $interval=new DateInterval("P2D");
    $date=(new DateTime())->add($interval)->getTimestamp();

    $payload=["date"=>$date];

    $token=JWT::encode($payload,"{$_ENV['JWT_KEY']}","HS256");

    var_dump($token); */

}else if($method==="POST")
{
    $create=new Create();
    $response=$create();

}else if($method==="PUT")
{
    $update=new Update($id);
    $response=$update();

}else if($method==="DELETE")
{
    $delete=new Delete($id);
    $response=$delete();
}
else 
{
    $reponse=new Response(405,[],json_encode(["message"=>"Method not exist"]));
}

$response=$response->withHeader("Content-Type","application/json");

send($response);

?>