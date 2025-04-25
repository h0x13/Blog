<?php

use App\Controllers\Blog;
use App\Controllers\Home;
use App\Controllers\Image;
use App\Controllers\User;
use App\Controllers\Category;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', [Home::class, 'index']);
$routes->get('/about', [Home::class, 'about']);
$routes->get('/dashboard', [Home::class, 'dashboard']);
$routes->get('user-image/(:any)', [Image::class, 'image/$1']);

// User routes
$routes->group('users', function($routes) {
    $routes->get('/', [User::class, 'index']);
    $routes->post('add', [User::class, 'add']);
    $routes->post('edit/(:num)', [User::class, 'edit/$1']);
    $routes->post('delete/(:num)', [User::class, 'delete/$1']);
});


// Blog routes
$routes->group('blogs', function($routes) {
    $routes->get('/', [Blog::class, 'index']);
    $routes->get('/(:num)', [Category::class, 'get/$1']);
});

$routes->group('categories', function($routes) {
    $routes->get('/', [Category::class, 'index']);
    $routes->post('add', [Category::class, 'add']);
    $routes->post('edit/(:num)', [Category::class, 'edit/$1']);
    $routes->post('delete/(:num)', [Category::class, 'delete/$1']);
});
