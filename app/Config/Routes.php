<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::dashboard');

// Company
$routes->get('/api/fetchCompanyList', 'Home::fetchCompanyList');

$routes->get('/company_upsert', 'Home::company_upsert');
$routes->get('/company_upsert/(:num)', 'Home::company_upsert/$1');

$routes->post('/company_submit', 'Home::company_submit');
$routes->post('/company_del', 'Home::company_del');


// User
$routes->get('/api/fetchUserList', 'Home::fetchUserList');

$routes->get('/user_upsert', 'Home::user_upsert');
$routes->get('/user_upsert/(:num)', 'Home::user_upsert/$1');

$routes->post('/user_submit', 'Home::user_submit');
$routes->post('/user_del', 'Home::user_del');