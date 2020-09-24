<?php

require_once('vendor/autoload.php');
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
$config = require 'config/config.php';

use App\Controllers\PassengerController;
use App\Controllers\LuggageController;
use App\Controllers\LoginController;


$app = new \Slim\App($config);
include('dependencies.php');
$jwt = require 'config/jwt.php';
$app->add(new \Tuupola\Middleware\JwtAuthentication($jwt));

$app->group('/api/v1',function() use ($app){

    //LOGIN SERVICE
    $app->group('/users', function () use ($app) {

        $app->post('/user/login',LoginController::class . ':userLogin');
        $app->post('/passenger/login',LoginController::class . ':passengerLogin');

    });

    //PASSENGER
    $app->group('/passenger', function () use ($app) {

        $app->post('/register',PassengerController::class . ':registration');
        $app->post('/query',PassengerController::class . ':query');

    });

    //MAP LUGGAGE
    $app->group('/luggage', function () use ($app) {

        $app->post('/map',LuggageController::class . ':map');
        $app->post('/destinations',LuggageController::class . ':destinations');
        $app->post('/track',LuggageController::class . ':track');
        $app->post('/passenger/details',LuggageController::class . ':view_luggage_by_passenger');
        $app->post('/create/dispatch',LuggageController::class . ':createDispatch');
        $app->post('/view/dispatch',LuggageController::class . ':viewDispatch');
        $app->post('/log/delivery',LuggageController::class . ':logDelivery');
        

    });
    
});

$app->run();