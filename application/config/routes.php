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
$route['default_controller'] = 'Login_cnt';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['start_sesion'] = 'Login_cnt/start_sesion';
$route['logout'] = 'Login_cnt/logout';
$route['getPlanes'] = 'Siniestro_cnt/getPlanes';
$route['verdetalle/(:any)'] = 'Siniestro_cnt/verdetalle/$1';
$route['generar_orden'] = 'Siniestro_cnt/generar_orden';
$route['atenciones'] = 'Reportes_cnt/index';
$route['facturacion'] = 'Reportes_cnt/facturacion';
$route['detalle_cobertura/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Siniestro_cnt/detalle_cobertura/$1/$2/$3/$4/$5';
$route['guardar_medicamentos'] = 'Siniestro_cnt/guardar_medicamentos';
$route['reimprimir_pdf/(:any)'] = 'Siniestro_cnt/reimprimir_pdf/$1';
$route['reimprimir_cobertura/(:any)/(:any)'] = 'Siniestro_cnt/reimprimir_cobertura/$1/$2';
$route['reimprimir_pdf/(:any)/(:any)'] = 'Siniestro_cnt/reimprimir_pdf/$1/$2';
$route['guardar_cobertura'] = 'Siniestro_cnt/guardar_cobertura';
$route['PopUp/(:any)'] = 'Siniestro_cnt/PopUp/$1';
$route['reg_triaje/(:any)/(:any)'] = 'Siniestro_cnt/reg_triaje/$1/$2';
$route['guardar_triaje'] = 'Siniestro_cnt/guardar_triaje';
$route['guardar_medicamentos2'] = 'Siniestro_cnt/guardar_medicamentos2';
$route['guardar_medicamentos3'] = 'Siniestro_cnt/guardar_medicamentos3';
$route['reimprimir_atencion_copia/(:any)/(:any)'] = 'Siniestro_cnt/reimprimir_atencion_copia/$1/$2';