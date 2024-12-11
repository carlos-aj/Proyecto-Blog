<?php
namespace Lib;
class Router {

    private static $routes = [];
    public static function add(string $method, string $action, Callable $controller):void{
        $action = trim($action, '/');

        self::$routes[$method][$action] = $controller;

    }


    public static function dispatch():void {
        $method = $_SERVER['REQUEST_METHOD'];

        $action = preg_replace('#blog_mvc#','',$_SERVER['REQUEST_URI']);
        $action = trim($action, '/');

        $param = null;
        preg_match('#[0-9]+$#', $action, $match);

        if(!empty($match)){

            $param = $match[0];
            $action=preg_replace('#'.$match[0].'#',':id',$action);
        }

        $fn = self::$routes[$method][$action]??null;

        if($fn){
            $callback = self::$routes[$method][$action];
            echo call_user_func($callback, $param);
        }else{
            header('Location: /blog_mvc/error/');
        }




    }
}