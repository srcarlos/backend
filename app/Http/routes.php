<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'AngularController@serveApp');
    Route::get('/unsupported-browser', 'AngularController@unsupported');
    Route::get('user/verify/{verificationCode}', ['uses' => 'Auth\AuthController@verifyUserEmail']);
    Route::get('auth/{provider}', ['uses' => 'Auth\AuthController@redirectToProvider']);
    Route::get('auth/{provider}/callback', ['uses' => 'Auth\AuthController@handleProviderCallback']);
    Route::get('/api/authenticate/user', 'Auth\AuthController@getAuthenticatedUser');
});

$api->group(['middleware' => ['api']], function ($api) {
    //$api->resource('auth', 'Auth\AuthController');

    // Password Reset Routes...
    $api->post('auth/password/email', 'Auth\PasswordResetController@sendResetLinkEmail');
    $api->get('auth/password/verify', 'Auth\PasswordResetController@verify');
    $api->post('auth/password/reset', 'Auth\PasswordResetController@reset');

    $api->post('auth/login', 'Auth\AuthController@postLogin');
});

$api->group(['middleware' => ['api', 'api.auth']], function ($api) {
    $api->get('users/me', 'UserController@getMe');
    $api->put('users/me', 'UserController@putMe');
});

$api->group(['prefix'=> 'users', 'middleware' => ['api']], function ($api) {
  //  $api->controller('users', 'UserController');
    $api->post('/', 'UserController@store');
    $api->get('/', 'UserController@getIndex');
    $api->delete('/user/{id}', 'UserController@deleteUser');
    $api->get('/show/{id}', 'UserController@getShow');
    $api->put('/show/', 'UserController@putShow');

    $api->post('/roles','UserController@postRoles');
    $api->get('/roles','UserController@getRoles');
    $api->get('/roles-show/{id}','UserController@getRolesShow');
    $api->put('/roles-show','UserController@putRolesShow');
    $api->delete('/roles/{id}','UserController@deleteRoles');

    $api->post('/permissions','UserController@postPermissions');
    $api->get('/permissions','UserController@getPermissions');
    $api->get('/permissions-show/{id}','UserController@getPermissionsShow');
    $api->put('/permissions-show','UserController@putPermissionsShow');
    $api->delete('/permissions/{id}','UserController@deletePermissions');
});

//Compañias
$api->group(['prefix'=> 'company', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','CompaniasController@store');
    $api->get('/','CompaniasController@index');
    $api->get('/{id}','CompaniasController@show');
    $api->post('/{id}','CompaniasController@update');
    $api->delete('/{id}','CompaniasController@destroy');

    //Segmentacion de centros de produccion por compañia
    $api->get('/{id}/centros','CompaniasController@centrosByCompania');
    $api->get('/dashboard/{id}','DashboardCompaniaController@dashboard');

});

//Categorias
$api->group(['prefix'=> 'categories', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/', 'CategoryController@store');
    $api->get('/','CategoryController@index');
    $api->get('/{id}', 'CategoryController@show');
    $api->put('/{id}', 'CategoryController@update');
    $api->delete('/{id}', 'CategoryController@destroy');
 //   $api->controller('/', 'CategoryController');
});
//Ciudades
$api->group(['prefix'=> 'cities', 'middleware' => ['api', 'api.auth']], function ($api) {
     $api->get('/{id}', 'CityController@show');
      $api->get('/byProvince/{id}', 'CityController@byProvince');
     $api->get('/','CityController@index');

   //  $api->controller('/', 'CityController');
});
//Provincias
$api->group(['prefix'=> 'provincies', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->get('/{id}', 'ProvinceController@show');
    $api->get('/byCountry/{id}', 'ProvinceController@byCountry');
    $api->get('/','ProvinceController@index');

 // $api->controller('/', 'ProvinceController');
});
//Paises
$api->group(['prefix'=> 'countries', 'middleware' => ['api', 'api.auth']], function ($api) {
     $api->get('/{id}', 'CountryController@show');
     $api->get('/','CountryController@index');

   //  $api->controller('/', 'CountryController');
});
//Usuarios
$api->group(['prefix'=> 'users', 'middleware' => ['api', 'api.auth']],function ($api){
    $api->get('/', 'UserController@getIndex')->middleware('role:admin.super');
    //$api->get('/sAdmin', 'UserController@getIndex')->middleware('role:admin.super');
});

//Impuestos
$api->group(['prefix'=> 'impuestos', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','ImpuestosController@store');
    $api->get('/','ImpuestosController@index');
    $api->get('/{id}','ImpuestosController@show');
    $api->put('/{id}','ImpuestosController@update');
    $api->delete('/{id}','ImpuestosController@destroy');
    $api->post('/search','ImpuestosController@search');
    $api->get('/export/csv','ImpuestosController@csv');
    $api->get('/export/pdf','ImpuestosController@pdf');
});

//Unidad de medida
$api->group(['prefix'=> 'unidad', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','UnidadMedidaController@store');
    $api->get('/','UnidadMedidaController@index');
    $api->get('/{id}','UnidadMedidaController@show');
    $api->put('/{id}','UnidadMedidaController@update');
    $api->delete('/{id}','UnidadMedidaController@destroy');
    $api->post('/search','UnidadMedidaController@search');
    $api->get('/export/csv','UnidadMedidaController@csv');
    $api->get('/export/pdf','UnidadMedidaController@pdf');
});

//Tipos de movimiento
$api->group(['prefix'=> 'tipo', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','TipoMovimientosController@store');
    $api->get('/','TipoMovimientosController@index');
    $api->get('/{id}','TipoMovimientosController@show');
    $api->put('/{id}','TipoMovimientosController@update');
    $api->delete('/{id}','TipoMovimientosController@destroy');
    $api->post('/search','TipoMovimientosController@search');
});

//Centros de produccion
$api->group(['prefix'=> 'centro', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','CentrosProduccionController@store');
    $api->get('/','CentrosProduccionController@index');
    $api->get('/{id}','CentrosProduccionController@show');
    $api->put('/{id}','CentrosProduccionController@update');
    $api->delete('/{id}','CentrosProduccionController@destroy');
    $api->post('/search','CentrosProduccionController@search');
    $api->get('/export/csv','CentrosProduccionController@csv');
    $api->get('/export/pdf','CentrosProduccionController@pdf');

    //Segmentacion de cocinas por centro de produccion
    $api->get('/{id}/cocinas','CentrosProduccionController@cocinasByCentro');
});

//Cocinas
$api->group(['prefix'=> 'cocina', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','CocinasController@store');
    $api->get('/','CocinasController@index');
    $api->get('/{id}','CocinasController@show');
    $api->put('/{id}','CocinasController@update');
    $api->delete('/{id}','CocinasController@destroy');

    $api->post('/search','CocinasController@search');
    $api->get('/export/csv','CocinasController@csv');
    $api->get('/export/pdf','CocinasController@pdf');

    //Segmentacion de bodegas por cocinas
    $api->get('/{id}/bodegas','CocinasController@bodegasByCocina');

    //Movimientos de ajuste por cocina
    $api->get('/{id}/movimiento_ajuste','CocinasController@ajustesByCocina');
});

//Metodo de pago
$api->group(['prefix'=> 'metodo', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','MetodosPagoController@store');
    $api->get('/','MetodosPagoController@index');
    $api->get('/{id}','MetodosPagoController@show');
    $api->put('/{id}','MetodosPagoController@update');
    $api->delete('/{id}','MetodosPagoController@destroy');
    $api->post('/search','MetodosPagoController@search');
    $api->get('/export/csv','MetodosPagoController@csv');
    $api->get('/export/pdf','MetodosPagoController@pdf');
});

//Bodegas
$api->group(['prefix'=> 'bodega', 'middleware' => ['api']], function ($api) {
    $api->post('/','BodegasController@store');
    $api->get('/','BodegasController@index');
    $api->get('/{id}','BodegasController@show');
    $api->put('/{id}','BodegasController@update');
    $api->delete('/{id}','BodegasController@destroy');
    $api->post('/search','BodegasController@search');
    $api->get('/export/csv','BodegasController@csv');
    $api->get('/export/pdf','BodegasController@pdf');

    //Segmentacion de secciones por bodegas
    $api->get('/{id}/seccion','BodegasController@seccionesByBodega');

    //Existencia en bodega
    $api->get('/{id}/insumos','BodegasController@insumosGET');
    $api->get('/{id}/existencias','BodegasController@insumosEnBodega');
    $api->get('/{bodega}/insumo/{insumo}','BodegasController@existencia');
    $api->post('/{id}/search','BodegasController@filtrarInsumos');

    //Listar Transformaciones
    $api->get('/{id}/transformaciones','BodegasController@transformaciones');

    //Listar Transferencias
    $api->get('/{id}/transferencias','BodegasController@transferencias');
});

//Secciones
$api->group(['prefix'=> 'seccion', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','SeccionController@store');
    $api->get('/','SeccionController@index');
    $api->get('/{id}','SeccionController@show');
    $api->put('/{id}','SeccionController@update');
    $api->delete('/{id}','SeccionController@destroy');

    //Segmentacion de posiciones por secciones
    $api->get('/{id}/posicion','SeccionController@posicionesBySeccion');
});

//Posiciones
$api->group(['prefix'=> 'posicion', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','PosicionController@store');
    $api->get('/','PosicionController@index');
    $api->get('/{id}','PosicionController@show');
    $api->put('/{id}','PosicionController@update');
    $api->delete('/{id}','PosicionController@destroy');
});

// Planes
$api->group(['prefix'=> 'planes', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','PlanController@store');
    $api->get('/','PlanController@index');
    $api->get('/{id}','PlanController@show');
    $api->put('/{id}','PlanController@update');
    $api->delete('/{id}','PlanController@destroy');
    
    $api->post('addTurno/{id}','PlanController@addTurno');
    $api->post('deleteTurno/{id}','PlanController@deleteTurno');
    $api->post('updateTurno/{id}','PlanController@updateTurno');
    $api->get('getListTurno/{id}','PlanController@getListTurno');
});

//Super Usuario
$api->group(['prefix'=> 'su', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','UserController@newSuperUser');
    $api->post('/{id}','UserController@updateSuperUser');
});

//Insumos
$api->group(['prefix'=> 'insumo', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','InsumosController@store');
    $api->get('/','InsumosController@index');
    $api->get('/{id}','InsumosController@show');
    $api->put('/{id}','InsumosController@update');
    $api->delete('/{id}','InsumosController@destroy');

    //Vincular Proveedores
    $api->post('/{id}/proveedor','InsumosController@vincularProveedor');
    $api->get('/{id}/proveedor','InsumosController@proveedores');
    $api->delete('/{insumo}/proveedor/{id}','InsumosController@desvincularProveedor');
});

//Insumos transformados
$api->group(['prefix'=> 'insumo_transformado', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','InsumosTransformadosController@store');
    $api->get('/','InsumosTransformadosController@indexIT');
    $api->get('/{id}','InsumosTransformadosController@show');
    $api->put('/{id}','InsumosTransformadosController@update');
    $api->delete('/{id}','InsumosTransformadosController@destroy');

    //Insumos base
    $api->get('/{id}/insumos_base','InsumosTransformadosController@insumos_base');
    $api->get('/{id}/not','InsumosTransformadosController@whereNotIn');
    $api->post('/{id}/insumo_base','InsumosTransformadosController@storeIB');
    $api->delete('/{insumo}/insumo_base/{insumo_base}','InsumosTransformadosController@eliminarInsumoBase');
    $api->post('/{insumo}/verificar','InsumosTransformadosController@validarInsumosBase');

    //Vincular Proveedores
    $api->post('/{id}/proveedor','InsumosTransformadosController@vincularProveedor');
    $api->get('/{id}/proveedor','InsumosTransformadosController@proveedores');
    $api->delete('/{insumot}/proveedor/{proveedor}','InsumosTransformadosController@desvincularProveedor');
});

//Ingredientes
$api->group(['prefix'=> 'ingrediente', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','IngredientesController@store');
    $api->get('/','IngredientesController@index');
    $api->get('/{id}','IngredientesController@show');
    $api->put('/{id}','IngredientesController@update');
    $api->delete('/{id}','IngredientesController@destroy');

    //Remover Insumo de ingrediente
    $api->delete('/{ingrediente}/insumo/{insumo}','IngredientesController@removerInsumo');
    
    $api->get('/{ingrediente}/insumo/','IngredientesController@getInsumos');
    $api->post('/{ingrediente}/insumo','IngredientesController@addInsumo');

});

//Platos
$api->group(['prefix'=> 'plato', 'middleware' => ['api']], function ($api) {
    $api->put('/{id}/ingredientes','PlatosController@updateIngredientes');
    $api->post('/','PlatosController@store');
    $api->get('/','PlatosController@index');
    $api->get('/{id}','PlatosController@show');
    $api->put('/{id}','PlatosController@update');
    $api->delete('/{id}','PlatosController@destroy');
    $api->get('/catalogo/platos','PlatosController@catalogo');
});

//Logo del Sistema
/*$api->group(['prefix'=> 'systemLogo', 'middleware' => ['api']], function ($api) {
    $api->post('/','SystemLogosController@store');
    //$api->get('/','SystemLogosController@index');
    //$api->get('/{id}','SystemLogosController@show');
    $api->put('/{id}','SystemLogosController@update');
    //$api->delete('/{id}','SystemLogosController@destroy');
    $api->get('/','SystemLogosController@getLogo');
});*/


// Planificacones
$api->group(['prefix'=> 'planificaciones', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','PlanificacionController@store');
    $api->get('/','PlanificacionController@index');
    $api->get('/{id}','PlanificacionController@show');
    $api->put('/{id}','PlanificacionController@update');
    $api->delete('/{id}','PlanificacionController@destroy');

    $api->post('/validarStock','PlanificacionController@validarStock');
    //Ordenes de compra por planificacion
    $api->get('/{id}/ordenes','PlanificacionController@ordenes');
    $api->get('/ordenes/count','PlanificacionController@indexConTotalOrdenes');

    $api->post('/verificarStatus','PlanificacionController@verificarStatus');
    $api->post('/ejecutar','PlanificacionController@ejecutar');
    $api->post('/ejecutarParcial','PlanificacionController@ejecutarParcial');
    $api->post('/ejecutarCompra','PlanificacionController@ejecutarCompra');

    $api->post('/verificarCerrar','PlanificacionController@verificarCerrar');
    $api->post('/cerrar','PlanificacionController@cerrar');



});

//Proveedores
$api->group(['prefix'=> 'proveedor', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','ProveedorController@store');
    $api->get('/','ProveedorController@index');
    $api->get('/by/estatus','ProveedorController@indexByEstatus');
    $api->get('/{id}','ProveedorController@show');
    $api->put('/{id}','ProveedorController@update');
    $api->delete('/{id}','ProveedorController@destroy');
    $api->post('/search','ProveedorController@search');
    $api->get('/export/csv','ProveedorController@csv');
    $api->get('/export/pdf','ProveedorController@pdf');
});

//Contactos
$api->group(['prefix'=> 'contacto', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/{proveedor}/store','ContactoController@store');
    $api->get('/{proveedor}','ContactoController@index');
    $api->get('/{proveedor}/show/{contacto}','ContactoController@show');
    $api->put('/{proveedor}/update/{contacto}','ContactoController@update');
    $api->delete('/{proveedor}/destroy/{contacto}','ContactoController@destroy');
});

//Cuentas
$api->group(['prefix'=> 'cuenta', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/{proveedor}/store','CuentaController@store');
    $api->get('/{proveedor}','CuentaController@index');
    $api->get('/{proveedor}/show/{cuenta}','CuentaController@show');
    $api->put('/{proveedor}/update/{cuenta}','CuentaController@update');
    $api->delete('/{proveedor}/destroy/{cuenta}','CuentaController@destroy');
});

//Clientes
$api->group(['prefix'=> 'cliente', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','ClienteController@store');
    $api->get('/','ClienteController@index');
    $api->get('/{id}','ClienteController@show');
    $api->put('/{id}','ClienteController@update');
    $api->delete('/{id}','ClienteController@destroy');
    $api->post('/search','ClienteController@search');
    //$api->get('/export/csv','ClienteController@csv');
    //$api->get('/export/pdf','ClienteController@pdf');

    $api->post('/{id}/asignar_plan','ClienteController@asignarPlan');
    $api->post('/{id}/remover_plan','ClienteController@removerPlan');
    $api->get('/plan/index','ClienteController@planes');
    $api->get('/credito/index','ClienteController@creditos');
    $api->post('/credito/search','ClienteController@creditosSearch');
});

//Planes de clientes
$api->group(['prefix'=>'cliente_plan', 'middleware' => ['api']], function($api) {
  $api->get('/', 'ClientePlanController@index');
  $api->get('/{id}', 'ClientePlanController@show');
  $api->put('/{id}/estado', 'ClientePlanController@estado');
  $api->put('/{id}/habilitar', 'ClientePlanController@habilitar');
  $api->put('/{id}/suspender', 'ClientePlanController@suspender');
  $api->put('/{id}/activar', 'ClientePlanController@activar');
});

//Factura de plan
$api->group(['prefix'=>'factura_plan', 'middleware' => ['api']], function($api) {
  $api->get('/', 'FacturaPlanController@index');
  $api->get('/{id}', 'FacturaPlanController@show');
  $api->put('/{id}/estado', 'FacturaPlanController@estado');
  $api->put('/{id}/habilitar', 'FacturaPlanController@habilitar');
  $api->put('/{id}/suspender', 'FacturaPlanController@suspender');
  $api->put('/{id}/activar', 'FacturaPlanController@activar');

  $api->post('/planificar','FacturaPlanController@planificar');

});

//Motorizados
$api->group(['prefix'=> 'motorizado', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','MotorizadoController@store');
    $api->get('/','MotorizadoController@index');
    $api->get('/{id}','MotorizadoController@show');
    $api->put('/{id}','MotorizadoController@update');
    $api->delete('/{id}','MotorizadoController@destroy');
    $api->post('/search','MotorizadoController@search');
    $api->get('/export/csv','MotorizadoController@csv');
    $api->get('/export/pdf','MotorizadoController@pdf');
});

//Zonas
$api->group(['prefix'=> 'zona', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','ZonaController@store');
    $api->get('/','ZonaController@index');
    $api->get('/{id}','ZonaController@show');
    $api->put('/{id}','ZonaController@update');
    $api->delete('/{id}','ZonaController@destroy');
    $api->post('/search','ZonaController@search');
    $api->get('/export/csv','ZonaController@csv');
    $api->get('/export/pdf','ZonaController@pdf');
});
//Motorizados-Zonas
$api->group(['prefix' => 'zona_motorizado', 'middleware' => ['api']], function($api) {
  $api->get('/motorizados/{id?}', 'ZonasMotorizadosController@availableMotorizados');
  $api->get('/zonas/{id?}', 'ZonasMotorizadosController@availableZonas');
  $api->get('/', 'ZonasMotorizadosController@index');
  $api->get('/{id}', 'ZonasMotorizadosController@show');
  $api->post('/', 'ZonasMotorizadosController@store');
  $api->put('/{id}', 'ZonasMotorizadosController@update');
  $api->delete('/{id}', 'ZonasMotorizadosController@destroy');
});

//Cotizaciones
$api->group(['prefix'=> 'cotizacion', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','CotizacionController@store');
    $api->get('/','CotizacionController@index');
    $api->get('/{id}','CotizacionController@show');
    $api->get('/mail/{id}','CotizacionController@mailSend');
    $api->put('/{id}','CotizacionController@update');
    $api->post('/search','CotizacionController@search');
});

//Facturacion??
$api->group(['prefix'=> 'factura', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/pagar','FacturaController@store');
    $api->get('/show/{id}','FacturaController@show');
});
//Credito
$api->group(['prefix'=> 'credito', 'middleware' => ['api', 'api.auth']], function ($api) {
  $api->get('/', 'CreditoController@index');
  $api->get('/{id}', 'CreditoController@show');
});

//Transformacion
$api->group(['prefix'=> 'transformacion', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','TransformacionController@store');
    $api->get('/{id}','TransformacionController@show');
    $api->get('/','TransformacionController@index');
    $api->put('/{id}','TransformacionController@update');
    $api->delete('/{id}','TransformacionController@destroy');
});

//Transferencias
$api->group(['prefix'=> 'transferencia', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','TransferenciaController@store');
    $api->get('/{id}','TransferenciaController@show');
    $api->put('/{id}','TransferenciaController@update');
    $api->delete('/{id}','TransferenciaController@destroy');
    $api->get('/aprobar/{id}','TransferenciaController@cambiarEstatus');
    $api->get('/conformar/{id}','TransferenciaController@cambiarEstatus');
    $api->get('/confirmar/{id}','TransferenciaController@cambiarEstatus');
});

//Ordenes
$api->group(['prefix'=> 'orden', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','OrdenHistoricoController@store');
    $api->get('/','OrdenHistoricoController@index');
    $api->get('/{id}','OrdenController@show');
    $api->put('/{id}','OrdenController@update');
    $api->delete('/{id}','OrdenController@destroy');

    $api->get('/orden_historico/{orden}','OrdenHistoricoController@getOrden');   
    $api->get('/{id}/validar-insumos','OrdenHistoricoController@validarInsumos');
    $api->delete('/{orden}/insumo/{insumo}','OrdenHistoricoController@eliminarInsumoOC');
    $api->post('/proveedores-sugeridos','OrdenController@sugerirProveedor');
    $api->get('/centro/{centro}/insumo/{insumo}','OrdenHistoricoController@existencia');

    $api->get('/aprobacion/{id}','OrdenHistoricoController@aprobarOC');
    $api->get('/aprobadas','OrdenHistoricoController@OCAprobadas');
    $api->get('/aprobadas/{id}','OrdenHistoricoController@OCAprobadas');
    $api->get('/descartar/{id}','OrdenHistoricoController@descartarOC');
});

//Ordenes de Produccion
$api->group(['prefix'=> 'ordenes_produccion', 'middleware' => ['api', 'api.auth']], function ($api) {

    $api->get('/{id}','OrdenProducionController@ordersByPlanificacionId');
    $api->get('/orden/{id}','OrdenProducionController@show');
    $api->get('/orden/{id}/insumosOC','OrdenProducionController@insumosOrdenCompra');
    $api->post('/orden/{id}/reservar','ReservaController@store');
    $api->post('/orden/{id}/verificarEstatus','OrdenProducionController@verificarEstatus');
    $api->post('/orden/{id}/ejecutar','OrdenProducionController@ejecutar');
    $api->get('/orden/{id}/cerrar','OrdenProducionController@cerrar');

    $api->post('/anexar','OrdenProducionController@anexar');


});

//Ordenes Bodegas
/*$api->group(['prefix'=> 'orden_bodega', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','OrdenBodegaController@store');
    $api->get('/bodega/{id}','OrdenBodegaController@index');
    $api->get('/{id}','OrdenBodegaController@show');
    $api->put('/{id}','OrdenBodegaController@update');
    $api->delete('/{id}','OrdenBodegaController@destroy');
});*/

//Prospectos
$api->group(['prefix'=> 'prospecto', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','CotizacionController@store');
    $api->get('/','CotizacionController@indexProspectos');
    $api->get('/{id}','CotizacionController@show');
    $api->get('/mail/{id}','CotizacionController@mailSend');
    $api->put('/{id}','CotizacionController@update');
    $api->post('/search','CotizacionController@search');
});


//Turnos
$api->group(['prefix'=> 'turno', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','TurnoController@store');
    $api->get('/','TurnoController@index');
    $api->get('/{id}','TurnoController@show');
    $api->put('/{id}','TurnoController@update');
    $api->delete('/{id}','TurnoController@destroy');
});

//Ingresos
$api->group(['prefix'=> 'ingreso', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/','OrdenIngresoController@store');
    $api->post('/distribucion/{id}','OrdenIngresoController@distribuirInsumos');
    $api->get('/','OrdenIngresoController@index');
    $api->get('/{id}','OrdenIngresoController@show');
    $api->put('/{id}','OrdenIngresoController@update');
    $api->delete('/{id}','OrdenIngresoController@destroy');
    $api->get('/confirmacion/{id}','OrdenIngresoController@altaDeInsumosOC');
});

//Movimientos de ajuste
$api->group(['prefix' => 'movimiento_ajuste','middleware' => ['api','api.auth']],function ($api){
    $api->post('/','MovimientoAjusteController@store');
    $api->get('/','MovimientoAjusteController@index');
    $api->get('/{id}','MovimientoAjusteController@show');
    $api->put('/{id}','MovimientoAjusteController@update');
});


//Dashboard 
$api->group(['prefix'=> 'reportes', 'middleware' => ['api', 'api.auth']], function ($api) {
    $api->post('/compania/{id}','DashboardCompaniaController@index');
    $api->post('/costos','DashboardCompaniaController@costos');
});



