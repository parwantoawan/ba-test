<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Auth routes
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';

// Dashboard
$route['dashboard'] = 'dashboard/index';
$route['api/dashboard/stats'] = 'dashboard/stats';

// Employee API routes
$route['api/employees'] = 'employee/index';
$route['api/employees/list'] = 'employee/list_data';
$route['api/employees/get/(:num)'] = 'employee/get/$1';
$route['api/employees/create'] = 'employee/create';
$route['api/employees/update/(:num)'] = 'employee/update/$1';
$route['api/employees/delete/(:num)'] = 'employee/delete/$1';
$route['api/employees/(:num)/history'] = 'employee/history/$1';

// Employee pages
$route['employees'] = 'employee/page_list';
$route['employees/add'] = 'employee/page_form';
$route['employees/edit/(:num)'] = 'employee/page_form/$1';
$route['employees/history'] = 'employee/page_history';
$route['employees/history/(:num)'] = 'employee/page_history/$1';

// Jabatan API routes
$route['api/jabatan'] = 'jabatan/index';
$route['api/jabatan/list'] = 'jabatan/list_data';
$route['api/jabatan/get/(:num)'] = 'jabatan/get/$1';
$route['api/jabatan/create'] = 'jabatan/create';
$route['api/jabatan/update/(:num)'] = 'jabatan/update/$1';
$route['api/jabatan/delete/(:num)'] = 'jabatan/delete/$1';
$route['api/jabatan/all'] = 'jabatan/get_all';

// Jabatan pages
$route['jabatan'] = 'jabatan/page_list';
$route['jabatan/add'] = 'jabatan/page_form';
$route['jabatan/edit/(:num)'] = 'jabatan/page_form/$1';

// Position API routes
$route['api/positions'] = 'position/index';
$route['api/positions/list'] = 'position/list_data';
$route['api/positions/get/(:num)'] = 'position/get/$1';
$route['api/positions/all'] = 'position/get_all';
$route['api/positions/create'] = 'position/create';
$route['api/positions/update/(:num)'] = 'position/update/$1';
$route['api/positions/delete/(:num)'] = 'position/delete/$1';

// Position pages
$route['positions'] = 'position/page_list';
$route['positions/add'] = 'position/page_form';
$route['positions/edit/(:num)'] = 'position/page_form/$1';
