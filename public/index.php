<?php

require "../vendor/autoload.php";

use Dotenv\Dotenv;
use GuzzleHttp\Psr7\Response;
use function Http\Response\send;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$router=new AltoRouter();

$router->map("GET","/articles","","all_article");
$router->map("POST","/post/article","","post_article");
$router->map("PUT","/put/article/[i:id]","","put_article");
$router->map('DELETE',"/delete/article/[i:id]","","delete_article");

$match=$router->match();

if ($match)
{
    require "../api/home.php";
}else 
{
    $response=new Response(404,["Content-Type"=>"application/json"],json_encode(["message"=>"Page not found"]));
    send($response);
}

?>