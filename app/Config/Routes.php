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


// Category routes
$routes->get('/categories', 'Categories::index');
$routes->post('categories/save', 'Categories::save');
$routes->get('categories/edit/(:segment)', 'Categories::edit/$1');
$routes->post('categories/update', 'Categories::update');
$routes->delete('categories/delete/(:num)', 'Categories::delete/$1');
$routes->post('categories/fetchRecords', 'Categories::fetchRecords');


// Supplier routes
$routes->get('/suppliers', 'Suppliers::index');
$routes->post('suppliers/save', 'Suppliers::save');
$routes->get('suppliers/edit/(:segment)', 'Suppliers::edit/$1');
$routes->post('suppliers/update', 'Suppliers::update');
$routes->delete('suppliers/delete/(:num)', 'Suppliers::delete/$1');
$routes->post('suppliers/fetchRecords', 'Suppliers::fetchRecords');


// Product routes
$routes->get('/products', 'Products::index');
$routes->post('products/save', 'Products::save');
$routes->get('products/edit/(:segment)', 'Products::edit/$1');
$routes->post('products/update', 'Products::update');
$routes->delete('products/delete/(:num)', 'Products::delete/$1');
$routes->post('products/fetchRecords', 'Products::fetchRecords');
   

// Transaction routes
$routes->get('/transactions', 'Transactions::index');
$routes->post('transactions/save', 'Transactions::save');
$routes->get('transactions/edit/(:segment)', 'Transactions::edit/$1');
$routes->post('transactions/update', 'Transactions::update');
$routes->delete('transactions/delete/(:num)', 'Transactions::delete/$1');
$routes->post('transactions/fetchRecords', 'Transactions::fetchRecords');

// Logs routes for admin
$routes->get('/log', 'Logs::log');