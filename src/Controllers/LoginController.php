<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Respect\Validation\Validator as V;
use Illuminate\Hashing\BcryptHasher;
use App\Helpers\Validator;
use \Tuupola\Base62Proxy;
use \Firebase\JWT\JWT;
use App\Models\Passenger;
use App\Models\User;
use \DateTime;

class LoginController{

    //user types
    //1 iis for user
    //2 is for passenger

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function userLogin($request, $response){

        $employee_id = trim(stripslashes($request->getParam('employee_id')));
        $password = trim(stripslashes($request->getParam('password')));

        if($request->isPost()){

            $validate = $this->container->get('validator');
            $validate->validate($request,[

                'employee_id' => V::notBlank(), //'required|email|exists:users,email',
                'password' => V::notBlank() //  'required|string',
            ]);

            if($validate->isValid()){
                $user = User::where('employee_id',$employee_id)
                                ->where('status','Active')
                                ->first();
                
                if($user){

                    /*
                    $checkSubscription = new Validator($this->container,$request,$response);
                    $subStatus = $checkSubscription->checkSubscription($teller->merchant_id);
                    if($subStatus == 3){

                        $responseMessage = new \StdClass();
                        $responseMessage->message = 'subscription due';
                        $responseMessage->reason = 'Account has been suspended. Kindly subscribe to access this service';
                        $responseMessage->state = false;
                        return $response->withStatus(200)->withJson($responseMessage);

                    }
                    */
                    $hasher = new BcryptHasher();
                    if($hasher->check($password,$user->password)){

                        $now       = new DateTime();
                        $future    = new DateTime("now +2 hours");
                        $jti       = Base62Proxy::encode(openssl_random_pseudo_bytes(16));
                        $secret    = base64_decode((string) getenv('APP_KEY'));
                        $serveName = "https://localhost";
                        $payload   = new \StdClass();
                        $payload->iss = $serveName;
                        $payload->jti = $jti;
                        $payload->iat = $now->getTimeStamp();
                        $payload->exp = $future->getTimeStamp();

                        $data = new \StdClass();
                        $data->id = $user->id;
                        $data->firstname = $user->firstname;
                        $data->lastname = $user->lastname;
                        $data->employee_id = $user->employee_id;
                        $data->role_id = $user->role_id;

                        $payload->data = $data;
                        $token  = JWT::encode($payload, $secret, "HS256");

                        $responseMessage = new \StdClass();
                        $responseMessage->message = 'resource found';
                        $responseMessage->state = true;
                        $responseMessage->token = $token;
                        $responseMessage->user = $user;

                        return $response->withStatus(200)->withJson($responseMessage);


                    }else{

                        $responseMessage = new \StdClass();
                        $responseMessage->message = 'resource not found';
                        $responseMessage->reason = 'Could not find account with provided details..';
                        $responseMessage->state = false;
                        return $response->withStatus(200)->withJson($responseMessage);
                        
                    }
                }else{

                    $responseMessage = new \StdClass();
                    $responseMessage->message = 'resource not found';
                    $responseMessage->reason = 'Account does not exist or is disabled';
                    $responseMessage->state = false;
                    return $response->withStatus(200)->withJson($responseMessage);

                }
            }else{
                $responseMessage = new \StdClass();
                $responseMessage->message = 'request error';
                $responseMessage->reason = 'Ensure that all required params are provided';
                $responseMessage->state = false;
                return $response->withStatus(200)->withJson($responseMessage);
            }
        }else{
            $responseMessage = new \StdClass();
            $responseMessage->message = 'request error';
            $responseMessage->reason = 'Ensure that all required params are provided';
            $responseMessage->state = false;
            return $response->withStatus(200)->withJson($responseMessage);
        }
    }

    public function passengerLogin($request, $response){

        $passport = trim(stripslashes($request->getParam('passport')));
        $password = trim(stripslashes($request->getParam('password')));

        if($request->isPost()){

            $validate = $this->container->get('validator');
            $validate->validate($request,[

                'passport' => V::notBlank(), //'required|email|exists:users,email',
                'password' => V::notBlank() //  'required|string',
            ]);

            if($validate->isValid()){

                $passenger = Passenger::where('passport_number',$passport)->first();
                if($passenger){
                    $hasher = new BcryptHasher();
                    if($hasher->check($password,$passenger->password)){

                        $now       = new DateTime();
                        $future    = new DateTime("now +2 hours");
                        $jti       = Base62Proxy::encode(openssl_random_pseudo_bytes(16));
                        $secret    = base64_decode((string) getenv('APP_KEY'));
                        $serveName = "https://localhost";
                        $payload   = new \StdClass();
                        $payload->iss = $serveName;
                        $payload->jti = $jti;
                        $payload->iat = $now->getTimeStamp();
                        $payload->exp = $future->getTimeStamp();

                        $data = new \StdClass();
                        $data->id = $passenger->id;
                        $data->firstname = $passenger->firstname;
                        $data->lastname = $passenger->lastname;
                        $data->passport = $passenger->passport_number;

                        $payload->data = $data;
                        $token  = JWT::encode($payload, $secret, "HS256");

                        $responseMessage = new \StdClass();
                        $responseMessage->message = 'resource found';
                        $responseMessage->state = true;
                        $responseMessage->token = $token;
                        $responseMessage->passenger = $passenger;

                        return $response->withStatus(200)->withJson($responseMessage);


                    }else{

                        $responseMessage = new \StdClass();
                        $responseMessage->message = 'resource not found';
                        $responseMessage->reason = 'Could not find account with provided details..';
                        $responseMessage->state = false;
                        return $response->withStatus(200)->withJson($responseMessage);
                        
                    }
                }else{

                    $responseMessage = new \StdClass();
                    $responseMessage->message = 'resource not found';
                    $responseMessage->reason = 'Account does not exist or is disabled';
                    $responseMessage->state = false;
                    return $response->withStatus(200)->withJson($responseMessage);

                }
            }else{
                $responseMessage = new \StdClass();
                $responseMessage->message = 'request error';
                $responseMessage->reason = 'Ensure that all required params are provided';
                $responseMessage->state = false;
                return $response->withStatus(200)->withJson($responseMessage);
            }
        }else{
            $responseMessage = new \StdClass();
            $responseMessage->message = 'request error';
            $responseMessage->reason = 'Ensure that all required params are provided';
            $responseMessage->state = false;
            return $response->withStatus(200)->withJson($responseMessage);
        }
    }
}