<?php

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule){

    return $capsule;

};

$container["logs_directory"] = __DIR__.'/src/Logs';

$container["root_directory"] = __DIR__;

$container["jwt"] = function ($container) {

    return new StdClass;

};

$container['view'] = function ($container) {
	
    $view = new \Slim\Views\Twig('views', [
        //'cache' => 'views'
        'cache' => false
	]);

	$router = $container->get('router');
	$uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new Slim\Views\TwigExtension($router, $uri));
    
	return $view;

};

$container["validator"] = function ($container) {

    return new Awurth\SlimValidation\Validator();

};