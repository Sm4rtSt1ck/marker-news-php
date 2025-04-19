<?php
return [
    '/'                          => '/../app/views/home.php',
    // Auth
    '/register'                  => '/../app/controllers/auth/RegisterController.php',
    '/login'                     => '/../app/controllers/auth/LoginController.php',
    '/logout'                    => '/../app/controllers/auth/LogoutController.php',
    // User
    '/user/view'                 => '/../app/controllers/user/UserController.php',
    '/profile'                   => '/../app/controllers/user/ProfileController.php',
    '/settings'                  => '/../app/controllers/user/SettingsController.php',
    # Media
    '/media/register'            => '/../app/controllers/media/MediaRegisterController.php',
    '/media/view'                => '/../app/controllers/media/MediaController.php',
    // Subscriptions
    '/subscriptions'             => '/../app/controllers/subscriptions/SubscriptionsController.php',
    '/subscriptions/unsubscribe' => '/../app/controllers/subscriptions/SubscriptionsUnsubscribeController.php',
    '/subscribe'                 => '/../app/controllers/subscriptions/SubscribeController.php',
    // Posts
    '/news/create'               => '/../app/controllers/post/NewsCreateController.php',
    '/event/create'              => '/../app/controllers/post/EventCreateController.php',
    '/post/view'                 => '/../app/controllers/post/PostController.php',
    '/post/interaction'          => '/../app/controllers/post/PostInteractionController.php',
    '/feed'                      => '/../app/controllers/post/FeedController.php',
    // Reports
    '/report/create'             => '/../app/controllers/report/ReportController.php',
    // Admin
    // '/admin'                     => '/../app/controllers/admin/AdminController.php',
];
