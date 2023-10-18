<?php

session_start();

try {
    require __DIR__ . '/../vendor/autoload.php';
    
    $route = $_GET['route'] ?? '';
    $routes = require __DIR__ . '/../src/routes.php';
    
    $isRouteFound = false;
    foreach($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches);
        if(!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }
    
    if(!$isRouteFound) {
        throw new \Students\Exceptions\NotFoundException();
    }
    
    unset($matches[0]);
    
    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];
    
    $controller = new $controllerName();
    $controller->$actionName(...$matches);
} catch(\Students\Exceptions\DbException $e) {
    $view = new \Students\View\View(__DIR__ . '/../src/templates/errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
} catch(\Students\Exceptions\NotFoundException $e) {
    $view = new \Students\View\View(__DIR__ . '/../src/templates/errors');
    $view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
} catch(\Students\Exceptions\UnauthorizedException $e) {
    $view = new \Students\View\View(__DIR__ . '/../src/templates/errors');
    $view->renderHtml('401.php', ['error' => $e->getMessage()], 401);
} catch (\Students\Exceptions\ForbiddenException $e) {
    $view = new \Students\View\View(__DIR__ . '/../src/templates/errors');
    $view->renderHtml('403.php', ['error' => $e->getMessage()], 403);
}

