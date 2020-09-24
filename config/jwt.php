<?php

return [

    "path"       => "/api",
    "ignore"     => [
                        "/api/v1/users/user/login",
                        "/api/v1/users/passenger/login",
                    ],
    "header"     => "Auth-Token",
    "regexp"     => "/(.*)/",
    "algorithm"  => ["HS256", "HS384"],
    "secure"     => false,
    "relaxed"    => ["localhost"],
    "secret"     => base64_decode((string) getenv('APP_KEY')),
    "error"      => function ($response, $arguments)
    {

        $data["status"]  = "error";
        $data["message"] = $arguments["message"];
        return $response->withStatus(401)->withJson($data);
                
    },

    "before" => function ($request,$arguments) use ($container)
    {
        $container["jwt"] = $arguments["decoded"];
    }
    
];