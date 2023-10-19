<?php

namespace Students\Services;

class Router 
{
    private $routes = [];

    public function get(string $url, array $fn) 
    {
        $this->routes['GET'][$url] = $fn;
    }

    public function post(string $url, array $fn)
    {
        $this->routes['POST'][$url] = $fn;
    }

    public function resolve()
    {
        // get url
        // get server request method
    }
}