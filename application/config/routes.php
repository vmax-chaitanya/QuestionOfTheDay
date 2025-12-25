<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Admin Auth Routes
// $route['login'] = 'admin/AuthController';
$route['admin/login'] = 'admin/AuthController';
$route['admin/auth/login'] = 'admin/AuthController/login';
$route['admin/logout'] = 'admin/AuthController/logout';

// Admin Question of the Day Routes
$route['admin/question-of-the-day'] = 'admin/questionOfTheDayController';
$route['admin/question-of-the-day/add'] = 'admin/questionOfTheDayController/add';
$route['admin/question-of-the-day/edit/(:num)'] = 'admin/questionOfTheDayController/edit/$1';
$route['admin/question-of-the-day/save'] = 'admin/questionOfTheDayController/save';
$route['admin/question-of-the-day/delete/(:num)'] = 'admin/questionOfTheDayController/delete/$1';
$route['admin/question-of-the-day/upload-image'] = 'admin/questionOfTheDayController/upload_image';
$route['admin/qod'] = 'admin/questionOfTheDayController';
$route['admin/qod/add'] = 'admin/questionOfTheDayController/add';
$route['admin/qod/edit/(:num)'] = 'admin/questionOfTheDayController/edit/$1';
$route['admin/qod/save'] = 'admin/questionOfTheDayController/save';
$route['admin/qod/delete/(:num)'] = 'admin/questionOfTheDayController/delete/$1';
$route['admin/qod/upload-image'] = 'admin/questionOfTheDayController/upload_image';

// Public Question routes
// View a specific Question of the Day by ID (used for previous questions list)
$route['question/(:num)'] = 'home/question/$1';
// Handle submission of answers (no login, browser-based uniqueness)
$route['submit-answer'] = 'home/submit_answer';
// Handle like/dislike for comments (AJAX)
$route['toggle-comment-like'] = 'home/toggle_comment_like';
