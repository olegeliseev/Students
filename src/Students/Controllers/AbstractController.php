<?php

namespace Students\Controllers;
use Students\View\View;
use Students\Models\Users\UsersAuthService;

abstract class AbstractController {
    
    protected $view;
    protected $user;

    function __construct()
    {
        $this->user = UsersAuthService::getUserByToken();
        $this->view = new View(__DIR__ . '/../../templates');
        $this->view->setVar('user', $this->user);
    }
}