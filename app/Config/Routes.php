<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/codigob/(:any)', 'CodigoBarras::test/$1');
$routes->setAutoRoute(true);