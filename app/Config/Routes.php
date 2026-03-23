<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/control', 'AuthController::login');
$routes->post('/control', 'AuthController::login');
$routes->get('/control/exit', 'AuthController::logout');

$routes->get('/', 'Home::index', ['filter' => 'auth']);

$routes->group('admin', ['filter' => 'auth'], static function ($routes): void {
    $routes->get('usuarios', 'Admin\\UsersController::index', ['filter' => 'permission:admin']);
    $routes->get('usuarios/data', 'Admin\\UsersController::list', ['filter' => 'permission:admin']);
    $routes->post('usuarios', 'Admin\\UsersController::create', ['filter' => 'permission:admin']);
    $routes->post('usuarios/(:num)', 'Admin\\UsersController::update/$1', ['filter' => 'permission:admin']);
    $routes->post('usuarios/(:num)/delete', 'Admin\\UsersController::delete/$1', ['filter' => 'permission:admin']);
});

$routes->group('clientes', ['filter' => 'auth'], static function ($routes): void {
    $routes->get('/', 'ClientesController::index', ['filter' => 'permission:clientes']);
    $routes->get('datatable', 'ClientesController::datatable', ['filter' => 'permission:clientes']);
    $routes->get('pedidos/(:num)', 'ClientesController::pedidos/$1', ['filter' => 'permission:clientes']);
});
