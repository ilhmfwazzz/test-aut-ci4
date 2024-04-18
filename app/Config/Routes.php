<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Auth\AuthController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('register', 'Auth::showRegistrationForm');
$routes->post('register', 'Auth::register');
$routes->get('login', 'Auth::showLoginForm');
$routes->post('login/postLogin', 'Auth::postLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('forget-password', 'Auth::showForgotPassword');
$routes->get('reset-password', 'Auth::showResetPassword');
$routes->post('check-email', 'Auth::checkEmail');
$routes->get('reset_password/(:num)', 'Auth::resetPassword/$1');
$routes->post('reset_password/(:num)', 'Auth::updatePassword/$1');




