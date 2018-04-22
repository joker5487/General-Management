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
|	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['admin/user/list']          = 'adminController/User/user_list';
$route['admin/user/list/data']     = 'adminController/User/get_user_list';
$route['admin/user/opt']           = 'adminController/User/user_opt';
$route['admin/user/opt/(:num)']    = 'adminController/User/user_opt/$1';
$route['admin/user/info']          = 'adminController/User/get_user_info';
$route['admin/user/handle']        = 'adminController/user/user_handle';
$route['admin/upload']             = 'adminController/Upload/uploadFile';

$route['admin/excel/export']       = 'adminController/Excel/ExcelExport';
$route['admin/excel/import']       = 'adminController/Excel/ExcelImport';


// 暂未使用
$route['admin/test/index'] = 'adminController/Admin_Test/index';
$route['admin/files/get']  = 'adminController/User/get_files';
$route['admin/test/ci']  = 'test/index';
$route['admin/school/list']  = 'adminController/School/show_page';
$route['admin/school/add']  = 'adminController/School/add_school_info';
$route['admin/school/get']  = 'adminController/School/get_school_data';