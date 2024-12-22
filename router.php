<?php

class Router {
    private $routes = [];

    // Add route to the router
    public function add($action, $callback) {
        $this->routes[$action] = $callback;
    }

    // Dispatch the route based on the action
    public function dispatch($action) {
        if (isset($this->routes[$action])) {
            call_user_func($this->routes[$action]);
        } else {
            echo "404 - Page Not Found";
        }
    }
}
