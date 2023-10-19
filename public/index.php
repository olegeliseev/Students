<?php

try {
    require __DIR__ . '/../vendor/autoload.php';

    $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
        $r->get('/',                        [\Students\Controllers\MainController::class,  'main']);
        $r->get('/{pageNum:\d+}',           [\Students\Controllers\MainController::class,  'main']);
        $r->get('/users/register',          [\Students\Controllers\UsersController::class, 'register']);
        $r->get('/users/login',             [\Students\Controllers\UsersController::class, 'login']);
        $r->get('/users/logout',            [\Students\Controllers\UsersController::class, 'logout']);
        $r->get('/users/{id:\d+}/edit',     [\Students\Controllers\UsersController::class, 'edit']);
        $r->post('/users/login',            [\Students\Controllers\UsersController::class, 'login']);
        $r->post('/users/register',         [\Students\Controllers\UsersController::class, 'register']);
        $r->post('/users/{id:\d+}/edit',    [\Students\Controllers\UsersController::class, 'edit']);
    });

    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];

    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }
    $uri = rawurldecode($uri);

    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            throw new \Students\Exceptions\NotFoundException();
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            throw new \Students\Exceptions\MethodNotAllowedException("Запрошенный http метод не разрешен для данного url!");
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            $controllerName = $handler[0];
            $actionName = $handler[1];

            $controller = new $controllerName();
            $controller->$actionName(...$vars);
            break;
    }
} catch (\Students\Exceptions\DbException $e) {
    $view = new \Students\View\View(__DIR__ . '/../src/templates/errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
} catch (\Students\Exceptions\NotFoundException $e) {
    $view = new \Students\View\View(__DIR__ . '/../src/templates/errors');
    $view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
} catch (\Students\Exceptions\UnauthorizedException $e) {
    $view = new \Students\View\View(__DIR__ . '/../src/templates/errors');
    $view->renderHtml('401.php', ['error' => $e->getMessage()], 401);
} catch (\Students\Exceptions\ForbiddenException $e) {
    $view = new \Students\View\View(__DIR__ . '/../src/templates/errors');
    $view->renderHtml('403.php', ['error' => $e->getMessage()], 403);
} catch (\Students\Exceptions\MethodNotAllowedException $e) {
    $view = new \Students\View\View(__DIR__ . '/../src/templates/errors');
    $view->renderHtml('405.php', ['error' => $e->getMessage(), 'allowedMethods' => $allowedMethods], 405);
}
