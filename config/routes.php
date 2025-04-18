<?php
return [
    '/'                          => '/../app/views/home.php',
    // Auth
    '/login'                     => '/../app/controllers/AuthController.php',
    '/register'                  => '/../app/controllers/RegisterController.php',
    '/logout'                    => '/../app/controllers/LogoutController.php',
    // User
    '/settings'                  => '/../app/controllers/SettingsController.php',
    '/user/view'                 => '/../app/controllers/UserController.php',
    '/profile'                   => '/../app/controllers/ProfileController.php',
    # Media
    '/media/register'            => '/../app/controllers/MediaRegisterController.php',
    '/media/view'                => '/../app/controllers/MediaController.php',
    // Subscriptions
    '/subscriptions'             => '/../app/controllers/SubscriptionsController.php',
    '/subscriptions/unsubscribe' => '/../app/controllers/SubscriptionsUnsubscribeController.php',
    '/subscribe'                 => '/../app/controllers/SubscribeController.php',
    // Posts
    '/news/create'               => '/../app/controllers/NewsCreateController.php',
    '/event/create'               => '/../app/controllers/CreateController.php',
    '/post/view'                 => '/../app/controllers/PostController.php',
    '/post/interaction'          => '/../app/controllers/PostInteractionController.php',
    '/feed'                      => '/../app/controllers/FeedController.php',
    // Reports
    '/report/create'             => '/../app/controllers/ReportController.php',
];
