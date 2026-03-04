<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->post('/auth', 'Auth::auth');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/logout', 'Auth::logout');

// User Acounts routes

$routes->get('/users', 'Users::index');
$routes->post('users/save', 'Users::save');
$routes->get('users/edit/(:segment)', 'Users::edit/$1');
$routes->post('users/update', 'Users::update');
$routes->delete('users/delete/(:num)', 'Users::delete/$1');
$routes->post('users/fetchRecords', 'Users::fetchRecords');

// Person routes
$routes->get('/person', 'Person::index');
$routes->post('person/save', 'Person::save');
$routes->get('person/edit/(:segment)', 'Person::edit/$1');
$routes->post('person/update', 'Person::update');
$routes->delete('person/delete/(:num)', 'Person::delete/$1');
$routes->post('person/fetchRecords', 'Person::fetchRecords');

// Students routes
$routes->get('/parents', 'Parents::index');
$routes->post('parents/save', 'Parents::save');
$routes->get('parents/edit/(:segment)', 'Parents::edit/$1');
$routes->post('parents/update', 'Parents::update');
$routes->delete('parents/delete/(:num)', 'Parents::delete/$1');
$routes->post('parents/fetchRecords', 'Parents::fetchRecords');

// Students routes
$routes->get('/students', 'Students::index');
$routes->post('students/save', 'Students::save');
$routes->get('students/edit/(:segment)', 'Students::edit/$1');
$routes->post('students/update', 'Students::update');
$routes->delete('students/delete/(:num)', 'Students::delete/$1');
$routes->post('students/fetchRecords', 'Students::fetchRecords');

// Logs routes for admin
$routes->get('/log', 'Logs::log');