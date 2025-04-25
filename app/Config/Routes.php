<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/dashboard', 'Home::dashboard');
$routes->get('user-image/(:any)', 'Image::image/$1');

// User routes
$routes->group('users', function($routes) {
    $routes->get('/', 'User::index');
    $routes->post('add', 'User::add');
    $routes->post('edit/(:num)', 'User::edit/$1');
    $routes->post('delete/(:num)', 'User::delete/$1');
});