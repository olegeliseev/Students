<?php 

return [
    '~^$~' => [\Students\Controllers\MainController::class, 'main'],
    // '~^?order=(.*)&sortBy=(.*)$~' => [\Students\Controllers\MainController::class, 'main'],
    '~^(\d+)$~' => [\Students\Controllers\MainController::class, 'main'],
    // '~^(\d+)/?order=(.*)&sortBy=(.*)$~' => [\Students\Controllers\MainController::class, 'main'],
    '~^(\d+)/?search=(.*)~' => [\Students\Controllers\MainController::class, 'main'],
    // '~^(\d+)/?search=(.*)&order=(.*)&sortBy=(.*)~' => [\Students\Controllers\MainController::class, 'main'],
    '~^users/register$~' => [\Students\Controllers\UsersController::class, 'register'],
    '~^users/login$~' => [\Students\Controllers\UsersController::class, 'login'],
    '~^users/logout$~' => [\Students\Controllers\UsersController::class, 'logout'],
    '~^users/(\d+)/edit$~' => [\Students\Controllers\UsersController::class, 'edit']
];