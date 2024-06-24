<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "index";
$route['404_override'] = '';


$route['tag/(.*)'] = 'tag/main/$1';

$route['g/r/(:num)'] = 'game/rank/$1';

$route['g'] = 'game';
$route['g/(:num)'] = 'game/play/$1';
$route['g/(:num)/(:num)'] = 'game/play/$1/$2';
$route['g/(.*)'] = 'game/$1';


$route['m/(.*)'] = 'more/$1';
$route['del/(:num)'] = 'game/delete/$1';
$route['fav/(:num)'] = 'game/favorite/$1';
$route['update/(:num)'] = 'make/update/$1';
$route['update/post/(:num)'] = 'make/update_post/$1';

$route['c'] = 'category';
$route['c/other'] = 'category/view/0';
$route['c/enter'] = 'category/view/1';
$route['c/science'] = 'category/view/2';
$route['c/arts'] = 'category/view/3';
$route['c/anime'] = 'category/view/4';
$route['c/sports'] = 'category/view/5';

$route['s'] = 'search/main';
$route['s/(.*)'] = 'search/main/$1';

$route['h'] = 'hot';
$route['h/(.*)'] = 'hot/$1';
$route['n'] = 'new';
$route['n/(.*)'] = 'new/$1';

$route['u'] = 'user';
$route['u/(.*)'] = 'user/$1';

$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';

/* End of file routes.php */
/* Location: ./application/config/routes.php */