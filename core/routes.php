<?php

class Router {

    public function run() {

        $url = $_GET['url'] ?? 'recipe/index';
        $url = explode('/', trim($url, '/'));

        $controllerName = ucfirst($url[0]) . 'Controller';
        $method = $url[1] ?? 'index';
        $params = array_slice($url, 2);

        require_once "../app/controllers/$controllerName.php";
        $controller = new $controllerName();

        call_user_func_array([$controller, $method], $params);
    }
    
}