<?php
return [
    '/'                  => '/../app/views/home.php',
    // Auth
    '/login'             => '/../app/controllers/AuthController.php',
    '/register'          => '/../app/controllers/RegisterController.php',
    '/logout'            => '/../app/controllers/LogoutController.php',
    // User
    '/settings'          => '/../app/controllers/SettingsController.php',
    '/user/view' => '/../app/controllers/UserController.php',
    // '/profile' => '/../app/views/profile.php',
    // Posts
    '/post/create'       => '/../app/controllers/CreateController.php',
    '/post/view'         => '/../app/controllers/PostController.php',
    '/post/interaction'  => '/../app/controllers/PostInteractionController.php',
    // Reports
    '/report/create'      => '/../app/controllers/ReportController.php',
];
