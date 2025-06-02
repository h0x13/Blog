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
$routes->get('/register', [Home::class, 'register']);
$routes->get('/login', [Home::class, 'login']);
$routes->get('/about', [Home::class, 'about']);
$routes->get('/dashboard', [Home::class, 'dashboard']);
$routes->get('user-image/(:segment)', [Image::class, 'image/$1']);
$routes->get('/profile', [User::class, 'profile']);

// User routes
$routes->group('users', function($routes) {
    $routes->get('/', [User::class, 'index']);
    $routes->post('add', [User::class, 'add']);
    $routes->post('edit/(:num)', [User::class, 'edit/$1']);
    $routes->post('delete/(:num)', [User::class, 'delete/$1']);
});


$routes->group('categories', function($routes) {
    $routes->get('/', [Category::class, 'index']);
    $routes->post('add', [Category::class, 'add']);
    $routes->post('edit/(:num)', [Category::class, 'edit/$1']);
    $routes->post('delete/(:num)', [Category::class, 'delete/$1']);
});


// Blog routes
$routes->group('blogs', function($routes) {
    $routes->get('/', [Blog::class, 'index']);
    $routes->get('popular/', [Blog::class, 'popular']);
    $routes->get('manage/', [Blog::class, 'manage']);
    // $routes->get('popular/', [Blog::class, 'index']);
    $routes->get('view/(:segment)', [Blog::class, 'view/$1']);
    $routes->get('add', [Blog::class, 'create']);
    $routes->post('add', [Blog::class, 'store']);
    $routes->get('edit/(:segment)', [Blog::class, 'update/$1']);
    $routes->post('edit/(:segment)', [Blog::class, 'save/$1']);
    $routes->post('delete/(:segment)', [Blog::class, 'delete/$1']);

    $routes->get('thumbnail/(:segment)', [Blog::class, 'thumbnail/$1']);
    $routes->get('image/(:segment)', [Blog::class, 'image/$1']);
    $routes->post('upload_image', [Blog::class, 'upload_image']);
    $routes->post('delete_image', [Blog::class, 'delete_image']);

    // $routes->get('saves/', [Blog::class, 'index']);
});

