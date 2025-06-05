<?php

use App\Controllers\Blog;
use App\Controllers\Home;
use App\Controllers\Image;
use App\Controllers\User;
use App\Controllers\Category;
use App\Controllers\VerificationController;
use App\Controllers\AuditLogController;
use App\Controllers\AdminAuditLogController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes (no auth required)
$routes->get('/', [Home::class, 'index']);
$routes->get('/register', [Home::class, 'register']);
$routes->post('/register', [Home::class, 'processRegistration']);
$routes->get('/verification-pending', [Home::class, 'verificationPending']);
$routes->get('/login', [Home::class, 'login']);
$routes->get('/about', [Home::class, 'about']);
$routes->get('/user-image/(:segment)', [Image::class, 'image/$1']);

// Authentication Routes
$routes->get('login', 'Home::login');
$routes->post('login', 'Home::processLogin');
$routes->get('logout', 'Home::logout');
$routes->get('forgot-password', 'Home::forgotPassword');
$routes->post('forgot-password', 'Home::processForgotPassword');
$routes->get('reset-password/(:segment)', 'Home::resetPassword/$1');
$routes->post('reset-password', 'Home::processResetPassword');

// Verification routes
$routes->get('verify-email/(:segment)', [VerificationController::class, 'verify/$1']);
$routes->get('verification/success', [VerificationController::class, 'success']);
$routes->get('verification/check-status', [VerificationController::class, 'checkStatus']);
$routes->get('resend-verification', [VerificationController::class, 'resendVerification']);

// Public blog routes (no auth required)
$routes->group('blogs', function($routes) {
    $routes->get('/', [Blog::class, 'index']);
    $routes->get('popular/', [Blog::class, 'popular']);
    $routes->get('category/(:segment)', [Blog::class, 'category/$1']);
    $routes->get('search', [Blog::class, 'search']);
    $routes->get('search-result', [Blog::class, 'searchResult']);
    $routes->get('view/(:segment)', [Blog::class, 'view/$1']);
    $routes->get('thumbnail/(:segment)', [Blog::class, 'thumbnail/$1']);
    $routes->get('image/(:segment)', [Blog::class, 'image/$1']);
});

// Protected routes (require auth)
$routes->group('', ['filter' => 'auth'], function($routes) {
    // Protected blog routes
    $routes->group('blogs', function($routes) {
        $routes->get('manage/', [Blog::class, 'manage']);
    $routes->get('add', [Blog::class, 'create']);
    $routes->post('add', [Blog::class, 'store']);
    $routes->get('edit/(:segment)', [Blog::class, 'update/$1']);
    $routes->post('edit/(:segment)', [Blog::class, 'save/$1']);
    $routes->post('delete/(:segment)', [Blog::class, 'delete/$1']);
    $routes->post('upload_image', [Blog::class, 'upload_image']);
    $routes->post('delete_image', [Blog::class, 'delete_image']);

    // Comments and Reactions
    $routes->post('comment/add', [Blog::class, 'addComment']);
    $routes->post('comment/reply', [Blog::class, 'addReply']);
    $routes->post('reaction/blog', [Blog::class, 'reactToBlog']);
    $routes->post('reaction/comment', [Blog::class, 'reactToComment']);
    });

    // Activity logs
    $routes->get('audit-logs', 'AuditLogController::index');
});

// Profile routes (accessible by both admin and regular users)
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/profile', [User::class, 'profile']);
    $routes->post('profile/update', [User::class, 'updateProfile']);
});

// Admin routes
$routes->group('', ['filter' => 'admin'], function($routes) {
    $routes->get('/dashboard', [Home::class, 'dashboard']);
    $routes->get('/users', [User::class, 'index']);
    $routes->get('/categories', [Category::class, 'index']);
    $routes->get('/admin/audit-logs', [AdminAuditLogController::class, 'index']);

    // User management routes
    $routes->group('users', function($routes) {
        $routes->post('add', [User::class, 'add']);
        $routes->post('edit/(:num)', [User::class, 'edit/$1']);
        $routes->post('delete/(:num)', [User::class, 'delete/$1']);
    });

    // Category management routes
    $routes->group('categories', function($routes) {
        $routes->post('add', [Category::class, 'add']);
        $routes->post('edit/(:num)', [Category::class, 'edit/$1']);
        $routes->post('delete/(:num)', [Category::class, 'delete/$1']);
    });
});

