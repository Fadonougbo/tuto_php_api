<?php

namespace API;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ServerRequestInterface;
use \DateTime;
use Exception;

class JwtVerification
{

    public  function tokenIsValide(ServerRequestInterface $server):bool
    {

        try{

        

        if(isset( ($server->getServerParams())["HTTP_AUTHORIZATION"] ))
        {
            $userToken=($server->getServerParams()["HTTP_AUTHORIZATION"]);

            $token=preg_replace("/.+\s(?=.+)/","",$userToken);

            $decodeJwt=JWT::decode($token,new Key("{$_ENV['JWT_KEY']}", 'HS256'));

           return  isset($decodeJwt->date)?!($this->dateIsNotValide($decodeJwt->date)):false;

        }else 
        {
            return false;
        }

        }catch(Exception $e)
        {
            return false;
        }

    }

    private function dateIsNotValide(Int $timstamp):bool
    {
        $currentTimestamp=( new DateTime() )->getTimestamp();

        return $currentTimestamp>$timstamp;
    }
}

?>