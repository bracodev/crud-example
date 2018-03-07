<?php

define('PATH_ROOT', realpath(__DIR__).'/' );

if ( isset($_GET['url']) AND strlen($_GET['url']) > 0 )
{
    $url = $_GET['url'];			
    $url = explode('/', $url);

    $controller = array_shift($url);
    $method     = array_shift($url);
    $arguments  = $url; 

    $pathController = __DIR__ .'/controllers/'. $controller . 'Controller.php';

    if( ! isset($method) ) $method = 'index';
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ) $method = 'store';
    if( $_SERVER['REQUEST_METHOD'] == 'PUT' ) $method = 'update';
    if( $_SERVER['REQUEST_METHOD'] == 'DELETE' ){
        $method = 'destroy';
        $arguments = [explode('/', $_GET['url'])[1]];
    }

    if ( is_readable($pathController) ) 
    {
        require_once __DIR__ .'/core/autoload.php';        
        require_once $pathController;

        $controllerClass = ucfirst($controller).'Controller';

        $controller = new $controllerClass;

        if ( isset($arguments) ) {
            call_user_func_array(array($controller,$method),$arguments);
        }
        else {
            call_user_func(array($controller,$method));
        }//END IF
            
    }
}