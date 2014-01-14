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

$route['registracion'] = 'user_manager_controller/register';
$route['activate'] = 'user_manager_controller/activate';
$route['ingresar'] = 'user_manager_controller/login';
$route['salir'] = 'user_manager_controller/logout'; 
$route['perfil'] = 'user_manager_controller/show_profile';
$route['editar-perfil'] = 'user_manager_controller/edit_profile';
$route['cambiar-password'] = 'user_manager_controller/reset_pass';
$route['reiniciar-password'] = 'user_manager_controller/reset';

$route['admin/users'] = 'user_admin_controller/list_users';
$route['admin/users/(:num)'] = 'user_admin_controller/list_users/$1';

/* Dashboard */
$route['dashboard'] = 'dashboard_controller/index';
$route['dashboard/categoria/(:num)'] = 'dashboard_controller/index';
$route['dashboard/reservas'] = 'dashboard_controller/getresevation';

/* habitaciones y cabañas */
$route['habitaciones'] = 'resort_controller/resort_list';
$route['habitaciones/(:num)'] = 'resort_controller/resort_list/$1';


/* Habitacion */
$route['habitaciones/add'] = 'resort_controller/manager_habitacion';
$route['habitaciones/insert'] = 'resort_controller/manager_habitacion';
$route['habitaciones/success/(:num)'] = 'resort_controller/manager_habitacion';
$route['habitaciones/insert_validation'] = 'resort_controller/manager_habitacion';
$route['habitaciones/ajax_list_info'] = 'resort_controller/manager_habitacion';
$route['habitaciones/ajax_list'] = 'resort_controller/manager_habitacion';
$route['habitaciones/edit/(:num)'] = 'resort_controller/manager_habitacion';
$route['habitaciones/update/(:num)'] = 'resort_controller/manager_habitacion';
$route['habitaciones/update_validation/(:num)'] = 'resort_controller/manager_habitacion';
$route['habitaciones/delete/(:num)'] = 'resort_controller/manager_habitacion';
$route['habitaciones/export'] = 'resort_controller/manager_habitacion';
$route['habitaciones/print'] = 'resort_controller/manager_habitacion';
$route['habitaciones'] = 'resort_controller/manager_habitacion';
$route['habitaciones/galeria'] = 'resort_controller/addGallery';
$route['habitaciones/galeria/(:any)'] = 'resort_controller/addGallery';



$route['resort/getresorts'] = 'resort_controller/getresorts';
$route['resort/getreservations'] = 'resort_controller/getreservations';
$route['resort/check-availability'] = 'resort_controller/checkAvailability';
$route['resort/getPaxAnsConsumos'] = 'resort_controller/getPaxAnsConsumos';


/* Reservas */
$route['reservation/add'] = 'reservation_controller/add_reservation';
$route['reservation/delete'] = 'reservation_controller/delete_reservation';
$route['reservation/update'] = 'reservation_controller/update_reservation';
$route['reservation/remove-pax'] = 'reservation_controller/remove_pax';
$route['reservation/add-pax'] = 'reservation_controller/add_pax';
$route['reservation/remove-consumo'] = 'reservation_controller/remove_consumo';
$route['reservation/add-consumo'] = 'reservation_controller/add_consumo';
$route['reservation/check-inn'] = 'reservation_controller/checkInnProcess';
$route['reservation/check-out'] = 'reservation_controller/checkOutProcess';
$route['reservation/confirmarProcess'] = 'reservation_controller/confirmarProcess';
$route['reservation/send-email'] = 'reservation_controller/sendEmail';

/* reservas desde el back */
$route['reservas/add'] = 'reservation_controller/manager_reservation';
$route['reservas/insert'] = 'reservation_controller/manager_reservation';
$route['reservas/success/(:num)'] = 'reservation_controller/manager_reservation';
$route['reservas/insert_validation'] = 'reservation_controller/manager_reservation';
$route['reservas/ajax_list_info'] = 'reservation_controller/manager_reservation';
$route['reservas/ajax_list'] = 'reservation_controller/manager_reservation';
$route['reservas/edit/(:num)'] = 'reservation_controller/manager_reservation';
$route['reservas/update/(:num)'] = 'reservation_controller/manager_reservation';
$route['reservas/update_validation/(:num)'] = 'reservation_controller/manager_reservation';
$route['reservas/delete/(:num)'] = 'reservation_controller/manager_reservation';
$route['reservas/export'] = 'reservation_controller/manager_reservation';
$route['reservas/print'] = 'reservation_controller/manager_reservation';
$route['reservas'] = 'reservation_controller/manager_reservation';





/* categorias */
$route['categorias/add'] = 'category_controller/manager_category';
$route['categorias/insert'] = 'category_controller/manager_category';
$route['categorias/success/(:num)'] = 'category_controller/manager_category';
$route['categorias/insert_validation'] = 'category_controller/manager_category';
$route['categorias/ajax_list_info'] = 'category_controller/manager_category';
$route['categorias/ajax_list'] = 'category_controller/manager_category';
$route['categorias/edit/(:num)'] = 'category_controller/manager_category';
$route['categorias/update/(:num)'] = 'category_controller/manager_category';
$route['categorias/update_validation/(:num)'] = 'category_controller/manager_category';
$route['categorias/delete/(:num)'] = 'category_controller/manager_category';
$route['categorias/export'] = 'category_controller/manager_category';
$route['categorias/print'] = 'category_controller/manager_category';
$route['categorias'] = 'category_controller/manager_category';

/* categorias */
$route['servicios/add'] = 'services_controller/manager_service';
$route['servicios/insert'] = 'services_controller/manager_service';
$route['servicios/success/(:num)'] = 'services_controller/manager_service';
$route['servicios/insert_validation'] = 'services_controller/manager_service';
$route['servicios/ajax_list_info'] = 'services_controller/manager_service';
$route['servicios/ajax_list'] = 'services_controller/manager_service';
$route['servicios/edit/(:num)'] = 'services_controller/manager_service';
$route['servicios/update/(:num)'] = 'services_controller/manager_service';
$route['servicios/update_validation/(:num)'] = 'services_controller/manager_service';
$route['servicios/export'] = 'services_controller/manager_service';
$route['servicios/delete/(:num)'] = 'services_controller/manager_service';
$route['servicios/print'] = 'services_controller/manager_service';
$route['servicios'] = 'services_controller/manager_service';

/* Formas de Pago */
$route['forma-de-pago/add'] = 'payment_controller/manager_payment';
$route['forma-de-pago/insert'] = 'payment_controller/manager_payment';
$route['forma-de-pago/success/(:num)'] = 'payment_controller/manager_payment';
$route['forma-de-pago/insert_validation'] = 'payment_controller/manager_payment';
$route['forma-de-pago/ajax_list_info'] = 'payment_controller/manager_payment';
$route['forma-de-pago/ajax_list'] = 'payment_controller/manager_payment';
$route['forma-de-pago/edit/(:num)'] = 'payment_controller/manager_payment';
$route['forma-de-pago/update/(:num)'] = 'payment_controller/manager_payment';
$route['forma-de-pago/update_validation/(:num)'] = 'payment_controller/manager_payment';
$route['forma-de-pago/export'] = 'payment_controller/manager_payment';
$route['forma-de-pago/delete/(:num)'] = 'payment_controller/manager_payment';
$route['forma-de-pago/print'] = 'payment_controller/manager_payment';
$route['forma-de-pago'] = 'payment_controller/manager_payment';

/* Lista de Precios */
$route['lista-de-precio/add'] = 'price_controller/manager_price';
$route['lista-de-precio/insert'] = 'price_controller/manager_price';
$route['lista-de-precio/success/(:num)'] = 'price_controller/manager_price';
$route['lista-de-precio/insert_validation'] = 'price_controller/manager_price';
$route['lista-de-precio/ajax_list_info'] = 'price_controller/manager_price';
$route['lista-de-precio/ajax_list'] = 'price_controller/manager_price';
$route['lista-de-precio/edit/(:num)'] = 'price_controller/manager_price';
$route['lista-de-precio/update/(:num)'] = 'price_controller/manager_price';
$route['lista-de-precio/update_validation/(:num)'] = 'price_controller/manager_price';
$route['lista-de-precio/export'] = 'price_controller/manager_price';
$route['lista-de-precio/delete/(:num)'] = 'price_controller/manager_price';
$route['lista-de-precio/print'] = 'price_controller/manager_price';
$route['lista-de-precio/(:num)'] = 'price_controller/manager_price';
$route['lista-de-precio'] = 'price_controller/manager_price';

$route['precios/unificados/(:any)'] = 'price_controller/unit_prices';
$route['precios/individuales/(:any)'] = 'price_controller/individual_prices';
$route['precios/precio-por-categoria/(:any)'] = 'price_controller/category_prices';


$route['default_controller'] = "user_manager_controller/login";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */