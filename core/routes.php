<?php

class Router {

    public function run() {

        // Default route
        $url = $_GET['url'] ?? 'recipe/home';

        // Clean and split
        $url = trim($url, '/');
        $parts = explode('/', $url);

        // Controller
        $controllerName = ucfirst($parts[0]) . 'Controller';
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

        // Method
        $method = $parts[1] ?? 'index';

        // Parameters
        $params = array_slice($parts, 2);

        // Check controller exists
        if (!file_exists($controllerFile)) {
            die('Controller not found');
        }

        require_once $controllerFile;
        $controller = new $controllerName();

        // Check method exists
        if (!method_exists($controller, $method)) {
            die('Method not found');
        }

        // Call method with params
        call_user_func_array([$controller, $method], $params);
    }
}