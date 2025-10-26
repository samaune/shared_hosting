<?php

require_once __DIR__ . '/stringer.php';

class PageRouter {
    private $work_dir = __DIR__ . "/../pages";
    public function __construct() {
        
        //str_replace("Router", "", get_class($this));
    }
    public function dispatch() {
        $uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($uri, PHP_URL_PATH);
        $path = trim($path, '/');

        // Default route
        if ($path === '') {
            $file_name = 'home';
            $classControllerName = 'HomePage';
            $method = 'index';
        } else {

            //$sub_path
            $parts = explode('/', $path);
            $file_name = $parts[0];

            $method = $parts[1] ?? 'index';

            $classControllerName = ucfirst($sub_path) . 'Page';
            
        }

        // Build controller path
        $controllerFile =  $this->work_dir . $class_path . "/$file_name.php";

        if (!file_exists($controllerFile)) {
            http_response_code(404);
            echo "<br/>File Path     : '$controllerFile' ";
            echo "<br/>Controller    : '$classControllerName' ";
            return;
        }

        require_once $controllerFile;
        $controller = new $classControllerName();

        if (!method_exists($controller, $method)) {
            http_response_code(404);
            echo "Method '$method' not found in '$controllerName'.";
            return;
        }

        $controller->$method();
    }
}
