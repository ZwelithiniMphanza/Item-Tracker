<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Respect\Validation\Validator as V;
use Illuminate\Hashing\BcryptHasher;

use App\Models\Passenger;
use App\Models\User;

class PassengerController{

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function registration($request, $response){
        
        //will register customer details then if passport number already exists then do not register new passenger
        $firstname = trim(stripslashes($request->getParam('firstname')));
        $lastname = trim(stripslashes($request->getParam('lastname')));
        $passport = trim(stripslashes($request->getParam('passport_number')));
        $mobile = trim(stripslashes($request->getParam('mobile')));
        $address = trim(stripslashes($request->getParam('address')));
        $password = trim(stripslashes($request->getParam('password')));

        $passenger = Passenger::where('passport_number',$passport)->first();
        if($passenger){

            $responseMessage = new \StdClass();
            $responseMessage->message = 'passenger already exists';
            $responseMessage->state = false;
            $responseMessage->passenger = $passenger;

            return $response->withStatus(200)->withJson($responseMessage);

        }else{

            $hasher = new BcryptHasher();
            $passenger = Passenger::create([
                'firstname'   =>  $firstname,
                'lastname'  =>  $lastname,
                'passport_number'  =>  $passport,
                'mobile'  =>  $mobile,
                'address' =>  $address,
                'password'   =>  $hasher->make($password),
            
            ]);

            $responseMessage = new \StdClass();
            $responseMessage->message = 'resource created';
            $responseMessage->state = true;
            $responseMessage->passenger = $passenger;

            return $response->withStatus(200)->withJson($responseMessage);
        }

    }

    public function query($request, $response){

        $passport = trim(stripslashes($request->getParam('passport')));
        $passenger = Passenger::where('passport_number',$passport)->first();
        if($passenger){

            $responseMessage = new \StdClass();
            $responseMessage->message = 'resource found';
            $responseMessage->state = true;
            $responseMessage->passenger = $passenger;

            return $response->withStatus(200)->withJson($responseMessage);

        }else{

            $responseMessage = new \StdClass();
            $responseMessage->message = 'resource not found';
            $responseMessage->state = false;
            $responseMessage->passenger = null;

            return $response->withStatus(200)->withJson($responseMessage);
        }
    }
}