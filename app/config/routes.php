<?php
// Définition des routes de l'application
$routes = [
    // Routes principales
    '/' => 'HomeController@index',

    // Routes utilisateur
    '/register' => 'UserController@register',
    '/login' => 'UserController@login',
    '/logout' => 'UserController@logout',
    '/profile' => 'UserController@profile',
    '/profile/update' => 'UserController@updateProfile',
    '/profile/change-password' => 'UserController@changePassword',
    '/profile/deactivate' => 'UserController@deactivateAccount',
    '/profile/delete' => 'UserController@deleteAccount',
    '/verify_code' => 'UserController@verify_code',
    '/request-reset-password' => 'UserController@requestResetPassword',
    '/reset-password' => 'UserController@resetPassword',

    // Routes événements
    '/events' => 'EventController@index',
    '/events/{id}' => 'EventController@show',

    // Routes réservations
    '/events/{id}/book' => 'ReservationController@create',
    '/reservations/{id}/pay' => 'ReservationController@pay',
    '/reservations/{id}/cancel' => 'ReservationController@cancel',

    // Routes avis
    '/events/{id}/review' => 'ReviewController@create',
    '/reviews/edit/{id}' => 'ReviewController@edit',
    '/reviews/delete/{id}' => 'ReviewController@delete',

    // Routes admin
    '/admin/dashboard' => 'AdminController@dashboard',
    '/admin/users' => 'AdminController@users',
    '/admin/users/edit/{id}' => 'AdminController@updateUser',
    '/admin/users/status/{id}' => 'AdminController@updateUserStatus',
    '/admin/users/role/{id}' => 'AdminController@updateUserRole',
    '/admin/events' => 'AdminController@events',
    '/admin/events/create' => 'AdminController@createEvent',
    '/admin/events/edit/{id}' => 'AdminController@updateEvent',
    '/admin/events/delete/{id}' => 'AdminController@deleteEvent',
    '/admin/reservations' => 'AdminController@reservations',
    '/admin/reviews' => 'AdminController@reviews',
    '/botman' => 'BotManController@handle'
];

// Affichage du contenu du fichier pour débogage
error_log("Routes chargées: " . print_r($routes, true));

return $routes;
