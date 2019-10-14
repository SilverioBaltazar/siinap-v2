<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('sicinar.login.loginInicio');
});

    Route::group(['prefix' => 'control-interno'], function() {
    Route::post('menu', 'usuariosController@actionLogin')->name('login');
    Route::get('status-sesion/expirada', 'usuariosController@actionExpirada')->name('expirada');
    Route::get('status-sesion/terminada', 'usuariosController@actionCerrarSesion')->name('terminada');

    Route::get('cedula-evaluacion/inicio', 'cuestionarioController@actionCuestionario')->name('cuestionario');
    Route::get('cedula-evaluacion/confirmacion', 'cuestionarioController@Val')->name('confirmacion');
    Route::get('cedula-evaluacion/confirmado', 'cuestionarioController@actionConfirmado')->name('confirmado');
    Route::get('cedula-evaluacion/verificar/{id}', 'cuestionarioController@actionVerificar')->name('verificar');
    Route::put('cedula-evaluacion/verificar/{id}', 'cuestionarioController@actionVerificando')->name('verificando');
    Route::post('cedula-evaluacion/nuevo', 'cuestionarioController@actionAltaCuestionario')->name('altaCuestionario');
    Route::get('cedula-evaluacion/editar', 'cuestionarioController@actionEditar')->name('evalEditar');
    //EDICION NORMA 1
    Route::get('cedula-evaluacion/{id}/editar/cedula-evaluacion/N1', 'cuestionarioController@actionObtenerEvaluacionN1')->name('EditarN1');
    Route::put('cedula-evaluacion/{id}/guardar/cedula-evaluacion/N1', 'cuestionarioController@actionGuardarEvaluacionN1')->name('ActualizarN1');
    //EDICION NORMA 2
    Route::get('cedula-evaluacion/{id}/editar/cedula-evaluacion/N2', 'cuestionarioController@actionObtenerEvaluacionN2')->name('EditarN2');
    Route::put('cedula-evaluacion/{id}/guardar/cedula-evaluacion/N2', 'cuestionarioController@actionGuardarEvaluacionN2')->name('ActualizarN2');
    //EDICION NORMA 3
    Route::get('cedula-evaluacion/{id}/editar/cedula-evaluacion/N3', 'cuestionarioController@actionObtenerEvaluacionN3')->name('EditarN3');
    Route::put('cedula-evaluacion/{id}/guardar/cedula-evaluacion/N3', 'cuestionarioController@actionGuardarEvaluacionN3')->name('ActualizarN3');
    //EDICION NORMA 3
    Route::get('cedula-evaluacion/{id}/editar/cedula-evaluacion/N4', 'cuestionarioController@actionObtenerEvaluacionN4')->name('EditarN4');
    Route::put('cedula-evaluacion/{id}/guardar/cedula-evaluacion/N4', 'cuestionarioController@actionGuardarEvaluacionN4')->name('ActualizarN4');
    //EDICION NORMA 3
    Route::get('cedula-evaluacion/{id}/editar/cedula-evaluacion/N5', 'cuestionarioController@actionObtenerEvaluacionN5')->name('EditarN5');
    Route::put('cedula-evaluacion/{id}/guardar/cedula-evaluacion/N5', 'cuestionarioController@actionGuardarEvaluacionN5')->name('ActualizarN5');
    //PROCESOS
	Route::get('procesos/nuevo','procesosController@actionVerAltaProcesos')->name('nuevoProceso');
	Route::post('procesos/nuevo/alta','procesosController@actionAltaProcesos')->name('altaProceso');
	Route::get('procesos/unidades/{id}','procesosController@actionUnidades')->name('unidades');
	Route::get('cuestionario/unidades/{id}','procesosController@actionUnidades');
    //Graficas
    //Route::get('procesos/ver/graficas','procesosController@Graficas')->name('verGraficas');

	Route::get('procesos/ver/todos','procesosController@actionVerProcesos')->name('verProcesos');
    Route::get('procesos/ver/sustantivos','procesosController@actionVerProcesosSustantivos')->name('verProcesosSust');
    Route::get('procesos/ver/administrativos','procesosController@actionVerProcesosAdministrativos')->name('verProcesosAdmin');
    Route::get('procesos/ver/institucionales','procesosController@actionVerProcesosInstitucionales')->name('verProcesosInst');
	Route::get('procesos/ver/todos/evaluaciones','procesosController@actionEvalProcesos')->name('evalProcesos');
    Route::get('procesos/gestion','procesosController@actionGestionProcesos')->name('procesosGestion');
    Route::get('procesos/gestion/administrativo','procesosController@actionGestionProcesosAdm')->name('procesosGestionAdm');
    Route::get('procesos/gestion/institucional','procesosController@actionGestionProcesosInst')->name('procesosGestionInst');
    Route::get('procesos/gestion/sustantivo','procesosController@actionGestionProcesosSust')->name('procesosGestionSust');
    Route::get('procesos/gestion/ver/{id}/informacion-general','procesosController@actionVerInfo')->name('procesoVerInfo');
    Route::get('procesos/gestion/unidades/administrativas','procesosController@actionGestionUnidad')->name('Gestunidades');
    Route::get('procesos/gestion/unidad/administrativa','procesosController@actionInfoUnidad')->name('unidadesInfo');
    Route::get('procesos/gestion/todos/{id}/activar','procesosController@actionActivarProcesos')->name('procesosGestionAct');
    Route::get('procesos/gestion/todos/{id}/desactivar','procesosController@actionDesactivarProcesos')->name('procesosGestionDes');
    Route::get('procesos/gestion/adm/{id}/activar','procesosController@actionActivarProcesosAdm')->name('procesosGestionActAdm');
    Route::get('procesos/gestion/adm/{id}/desactivar','procesosController@actionDesactivarProcesosAdm')->name('procesosGestionDesAdm');
    Route::get('procesos/gestion/inst/{id}/activar','procesosController@actionActivarProcesosInst')->name('procesosGestionActInst');
    Route::get('procesos/gestion/inst/{id}/desactivar','procesosController@actionDesactivarProcesosInst')->name('procesosGestionDesInst');
    Route::get('procesos/gestion/sust/{id}/activar','procesosController@actionActivarProcesosSust')->name('procesosGestionActSust');
    Route::get('procesos/gestion/sust/{id}/desactivar','procesosController@actionDesactivarProcesosSust')->name('procesosGestionDesSust');

	Route::get('downloadExcel','procesosController@export')->name('download');
    Route::get('ver/pdf/{id}','procesosController@verPDF')->name('Verpdf');
    //PLAN DE TRABAJO
    Route::get('plan-de-trabajo/nuevo','estrategiasController@actionNuevoPlan')->name('nuevoPlan');
    Route::post('plan-de-trabajo/nuevo/alta','estrategiasController@actionAltaNuevoPlan')->name('AltaNuevoPlan');
    Route::get('plan-de-trabajo/ver/todos','estrategiasController@actionVerPlan')->name('verPlan');
    Route::get('plan-de-trabajo/{id}/marcar/activo','estrategiasController@actionActivarPlan')->name('activarPlan');
    Route::get('plan-de-trabajo/{id}/marcar/inhactivo','estrategiasController@actionDesactivarPlan')->name('desactivarPlan');
    Route::get('plan-de-trabajo/{id}/marcar/pendiente','estrategiasController@actionPlanPendiente')->name('planPendiente');
    Route::get('plan-de-trabajo/{id}/marcar/concluido','estrategiasController@actionPlanConcluido')->name('planConcluido');
    Route::get('plan-de-trabajo/{id}/editar/plan-de-trabajo','estrategiasController@actionEditarPlan')->name('editarPlan');
    Route::get('plan-de-trabajo/{id}/editar/plan-de-trabajo/accion-de-mejora','estrategiasController@actionEditarAccion')->name('editarAccion');
    Route::put('plan-de-trabajo/{id}/editar/plan-de-trabajo/nueva/accion-de-mejora','estrategiasController@actionAltaAccion')->name('altaAccion');
    Route::get('plan-de-trabajo/{id}/ver/pdf','estrategiasController@actionVerPDF')->name('planPDF');
   
    // BACK OFFICE DEL SISTEMA
    Route::get('BackOffice/usuarios'                ,'usuariosController@actionNuevoUsuario')->name('nuevoUsuario');
    Route::post('BackOffice/usuarios/alta'          ,'usuariosController@actionAltaUsuario')->name('altaUsuario');
    Route::get('BackOffice/usuarios/todos'          ,'usuariosController@actionVerUsuario')->name('verUsuarios');
    Route::get('BackOffice/usuarios/{id}/editar'    ,'usuariosController@actionEditarUsuario')->name('editarUsuario');
    Route::put('BackOffice/usuarios/{id}/actualizar','usuariosController@actionActualizarUsuario')->name('actualizarUsuario');
    Route::get('BackOffice/usuarios/{id}/Borrar'    ,'usuariosController@actionBorrarUsuario')->name('borrarUsuario');    
    Route::get('BackOffice/usuario/{id}/activar'    ,'usuariosController@actionActivarUsuario')->name('activarUsuario');
    Route::get('BackOffice/usuario/{id}/desactivar' ,'usuariosController@actionDesactivarUsuario')->name('desactivarUsuario');
    //Catalogos
    //Procesos
    Route::get('proceso/nuevo'      ,'catalogosController@actionNuevoProceso')->name('nuevoProceso');
    Route::post('proceso/nuevo/alta','catalogosController@actionAltaNuevoProceso')->name('AltaNuevoProceso');
    Route::get('proceso/ver/todos'  ,'catalogosController@actionVerProceso')->name('verProceso');
    Route::get('proceso/{id}/editar/proceso','catalogosController@actionEditarProceso')->name('editarProceso');
    Route::put('proceso/{id}/actualizar'    ,'catalogosController@actionActualizarProceso')->name('actualizarProceso');
    Route::get('proceso/{id}/Borrar','catalogosController@actionBorrarProceso')->name('borrarProceso');    
    Route::get('proceso/excel'      ,'catalogosController@exportCatProcesosExcel')->name('downloadprocesos');
    Route::get('proceso/pdf'        ,'catalogosController@exportCatProcesosPdf')->name('catprocesosPDF');
    //Funciones de procesos
    Route::get('funcion/nuevo'      ,'catalogosfuncionesController@actionNuevaFuncion')->name('nuevaFuncion');
    Route::post('funcion/nuevo/alta','catalogosfuncionesController@actionAltaNuevaFuncion')->name('AltaNuevaFuncion');
    Route::get('funcion/ver/todos'  ,'catalogosfuncionesController@actionVerFuncion')->name('verFuncion');
    Route::get('funcion/{id}/editar/funcion','catalogosfuncionesController@actionEditarFuncion')->name('editarFuncion');
    Route::put('funcion/{id}/actualizar'    ,'catalogosfuncionesController@actionActualizarFuncion')->name('actualizarFuncion');
    Route::get('funcion/{id}/Borrar','catalogosfuncionesController@actionBorrarFuncion')->name('borrarFuncion');    
    Route::get('funcion/excel'      ,'catalogosfuncionesController@exportCatFuncionesExcel')->name('downloadfunciones');
    Route::get('funcion/pdf'        ,'catalogosfuncionesController@exportCatFuncionesPdf')->name('catfuncionesPDF');    
    //Actividades
    Route::get('actividad/nuevo'      ,'catalogostrxController@actionNuevaTrx')->name('nuevaTrx');
    Route::post('actividad/nuevo/alta','catalogostrxController@actionAltaNuevaTrx')->name('AltaNuevaTrx');
    Route::get('actividad/ver/todos'  ,'catalogostrxController@actionVerTrx')->name('verTrx');
    Route::get('actividad/{id}/editar/actividad','catalogostrxController@actionEditarTrx')->name('editarTrx');
    Route::put('actividad/{id}/actualizar'      ,'catalogostrxController@actionActualizarTrx')->name('actualizarTrx');
    Route::get('actividad/{id}/Borrar','catalogostrxController@actionBorrarTrx')->name('borrarTrx');    
    Route::get('actividad/excel'      ,'catalogostrxController@exportCatTrxExcel')->name('downloadtrx');
    Route::get('actividad/pdf'        ,'catalogostrxController@exportCatTrxPdf')->name('cattrxPDF');
    //Rubros sociales
    Route::get('rubro/nuevo'      ,'catalogosrubrosController@actionNuevoRubro')->name('nuevoRubro');
    Route::post('rubro/nuevo/alta','catalogosrubrosController@actionAltaNuevoRubro')->name('AltaNuevoRubro');
    Route::get('rubro/ver/todos'  ,'catalogosrubrosController@actionVerRubro')->name('verRubro');
    Route::get('rubro/{id}/editar/rubro','catalogosrubrosController@actionEditarRubro')->name('editarRubro');
    Route::put('rubro/{id}/actualizar'  ,'catalogosrubrosController@actionActualizarRubro')->name('actualizarRubro');
    Route::get('rubro/{id}/Borrar','catalogosrubrosController@actionBorrarRubro')->name('borrarRubro');    
    Route::get('rubro/excel'      ,'catalogosrubrosController@exportCatRubrosExcel')->name('downloadrubros');
    Route::get('rubro/pdf'        ,'catalogosrubrosController@exportCatRubrosPdf')->name('catrubrosPDF');    
    //Imnuebles edo.
    Route::get('inmuebleedo/nuevo'      ,'catalogosinmueblesedoController@actionNuevoInmuebleedo')->name('nuevoInmuebleedo');
    Route::post('inmuebleedo/nuevo/alta','catalogosinmueblesedoController@actionAltaNuevoInmuebleedo')->name('AltaNuevoInmuebleedo');
    Route::get('inmuebleedo/ver/todos'  ,'catalogosinmueblesedoController@actionVerInmuebleedo')->name('verInmuebleedo');
    Route::get('inmuebleedo/{id}/editar/rubro','catalogosinmueblesedoController@actionEditarInmuebleedo')->name('editarInmuebleedo');
    Route::put('inmuebleedo/{id}/actualizar'  ,'catalogosinmueblesedoController@actionActualizarInmuebleedo')->name('actualizarInmuebleedo');
    Route::get('inmuebleedo/{id}/Borrar','catalogosinmueblesedoController@actionBorrarInmuebleedo')->name('borrarInmuebleedo');
    Route::get('inmuebleedo/excel'      ,'catalogosinmueblesedoController@exportCatInmueblesedoExcel')->name('downloadinmueblesedo');
    Route::get('inmuebleedo/pdf'        ,'catalogosinmueblesedoController@exportCatInmueblesedoPdf')->name('catinmueblesedoPDF');
    //tipos de archivos
    Route::get('formato/nuevo'              ,'catformatosController@actionNuevoFormato')->name('nuevoFormato');
    Route::post('formato/nuevo/alta'        ,'catformatosController@actionAltaNuevoFormato')->name('AltaNuevoFormato');
    Route::get('formato/ver/todos'          ,'catformatosController@actionVerFormatos')->name('verFormatos');
    Route::get('formato/{id}/editar/formato','catformatosController@actionEditarFormato')->name('editarFormato');
    Route::put('formato/{id}/actualizar'    ,'catformatosController@actionActualizarFormato')->name('actualizarFormato');
    Route::get('formato/{id}/Borrar'        ,'catformatosController@actionBorrarFormato')->name('borrarFormato');    
    //Route::get('formato/excel'            ,'catformatosController@exportCatRubrosExcel')->name('downloadrubros');
    //Route::get('formato/pdf'              ,'catformatosController@exportCatRubrosPdf')->name('catrubrosPDF');     

    //catalogo de documentos
    Route::get('docto/buscar/todos'        ,'catdoctosController@actionBuscarDocto')->name('buscarDocto');    
    Route::get('docto/nuevo'               ,'catdoctosController@actionNuevoDocto')->name('nuevoDocto');
    Route::post('docto/nuevo/alta'         ,'catdoctosController@actionAltaNuevoDocto')->name('AltaNuevoDocto');
    Route::get('docto/ver/todos'           ,'catdoctosController@actionVerDoctos')->name('verDoctos');
    Route::get('docto/{id}/editar/formato' ,'catdoctosController@actionEditarDocto')->name('editarDocto');
    Route::put('docto/{id}/actualizar'     ,'catdoctosController@actionActualizarDocto')->name('actualizarDocto');    
    Route::get('docto/{id}/editar/formato1','catdoctosController1@actionEditarDocto1')->name('editarDocto1');
    Route::put('docto/{id}/actualizar1'    ,'catdoctosController1@actionActualizarDocto1')->name('actualizarDocto1');
    Route::get('docto/{id}/Borrar'         ,'catdoctosController@actionBorrarDocto')->name('borrarDocto');    
    //Route::get('docto/excel'             ,'catdoctosController@exportCatDoctosExcel')->name('catDoctosExcel');
    //Route::get('docto/pdf'               ,'catdoctosController@exportCatDoctosPdf')->name('catDoctosPDF');     

    //Municipios sedesem
    Route::get('municipio/ver/todos','catalogosmunicipiosController@actionVermunicipios')->name('verMunicipios');
    Route::get('municipio/excel'    ,'catalogosmunicipiosController@exportCatmunicipiosExcel')->name('downloadmunicipios');
    Route::get('municipio/pdf'      ,'catalogosmunicipiosController@exportCatmunicipiosPdf')->name('catmunicipiosPDF');
    
    //Instituciones de Asistencia Privada (IAPS)
    //IAPS Directorio
    Route::get('iaps/nueva'           ,'iapsController@actionNuevaIap')->name('nuevaIap');
    Route::post('iaps/nueva/alta'     ,'iapsController@actionAltaNuevaIap')->name('AltaNuevaIap');
    Route::get('iaps/ver/todas'       ,'iapsController@actionVerIap')->name('verIap');
    Route::get('iaps/buscar/todas'    ,'iapsController@actionBuscarIap')->name('buscarIap');    
    Route::get('iaps/{id}/editar/iaps','iapsController@actionEditarIap')->name('editarIap');
    Route::put('iaps/{id}/actualizar' ,'iapsController@actionActualizarIap')->name('actualizarIap');
    Route::get('iaps/{id}/Borrar'     ,'iapsController@actionBorrarIap')->name('borrarIap');
    Route::get('iaps/excel'           ,'iapsController@exportCatIapsExcel')->name('downloadiap');
    Route::get('iaps/pdf'             ,'iapsController@exportCatIapsPdf')->name('catiapPDF');
    //Route::get('/', 'UserController@index')->name('users');

    Route::get('iaps/{id}/editar/iaps1','iapsController1@actionEditarIap1')->name('editarIap1');
    Route::put('iaps/{id}/actualizar1' ,'iapsController1@actionActualizarIap1')->name('actualizarIap1'); 
    Route::get('iaps/{id}/editar/iaps2','iapsController2@actionEditarIap2')->name('editarIap2');
    Route::put('iaps/{id}/actualizar2' ,'iapsController2@actionActualizarIap2')->name('actualizarIap2');        

    //Numeralia
    Route::get('numeralia/ver/graficaxedo'   ,'iapsController@IapxEdo')->name('verGraficaxedo');
    Route::get('numeralia/ver/graficaxmpio'  ,'iapsController@IapxMpio')->name('verGraficaxmpio');
    Route::get('numeralia/ver/graficaxrubro' ,'iapsController@IapxRubro')->name('verGraficaxrubro');    
    Route::get('numeralia/ver/graficaxrubro2','iapsController@IapxRubro2')->name('verGraficaxrubro2'); 
    Route::get('numeralia/ver/graficadeBitacora','iapsController@Bitacora')->name('verGraficabitacora'); 
    Route::get('numeralia/ver/mapas'         ,'iapsController@Mapas')->name('verMapas');        
    Route::get('numeralia/ver/mapas2'        ,'iapsController@Mapas2')->name('verMapas2');        
    Route::get('numeralia/ver/mapas3'        ,'iapsController@Mapas3')->name('verMapas3');        
    
    //IAPS datos Juridicos
    Route::get('iapsjuridico/nueva'             ,'iapsjuridicoController@actionNuevaIapj')->name('nuevaIapj');
    Route::post('iapsjuridico/nueva/alta'       ,'iapsjuridicoController@actionAltaNuevaIapj')->name('AltaNuevaIapj');    
    Route::get('iapsjuridico/ver/todasj'        ,'iapsjuridicoController@actionVerIapj')->name('verIapj');
    Route::get('iapsjuridico/{id}/editar/iapsj' ,'iapsjuridicoController@actionEditarIapj')->name('editarIapj');
    Route::put('iapsjuridico/{id}/actualizarj'  ,'iapsjuridicoController@actionActualizarIapj')->name('actualizarIapj');    
    Route::get('iapsjuridico/{id}/editar/iapsj1','iapsjuridicoController@actionEditarIapj1')->name('editarIapj1');
    Route::put('iapsjuridico/{id}/actualizarj1' ,'iapsjuridicoController@actionActualizarIapj1')->name('actualizarIapj1');    
    Route::get('iapsjuridico/{id}/editar/iapsj2','iapsjuridicoController@actionEditarIapj2')->name('editarIapj2');
    Route::put('iapsjuridico/{id}/actualizarj2' ,'iapsjuridicoController@actionActualizarIapj2')->name('actualizarIapj2');    
    Route::get('iapsjuridico/{id}/Borrarj'      ,'iapsjuridicoController@actionBorrarIapj')->name('borrarIapj');
    //Route::get('iapsjuridico/excel'           ,'iapsjuridicoController@exportCatIapsExcel')->name('downloadiap');
    //Route::get('iapsjuridico/{id, file}/pdfj'   ,'iapsjuridicoController@exportAct_ConstPDF')->name('acta_constPDF');

    //IAPS Aportaciones monetarias
    Route::get('aportaciones/nueva'            ,'aportacionesController@actionNuevaApor')->name('nuevaApor');
    Route::post('aportaciones/nueva/alta'      ,'aportacionesController@actionAltaNuevaApor')->name('AltaNuevaApor');
    Route::get('aportaciones/ver/todas'        ,'aportacionesController@actionVerApor')->name('verApor');
    Route::get('aportaciones/buscar/todas'     ,'aportacionesController@actionBuscarApor')->name('buscarApor');
    Route::get('aportaciones/{id}/editar/iaps' ,'aportacionesController@actionEditarApor')->name('editarApor');
    Route::put('aportaciones/{id}/actualizar'  ,'aportacionesController@actionActualizarApor')->name('actualizarApor');
    Route::get('aportaciones/{id}/editar/iaps1','aportacionesController1@actionEditarApor1')->name('editarApor1');
    Route::put('aportaciones/{id}/actualizar1' ,'aportacionesController1@actionActualizarApor1')->name('actualizarApor1');    
    Route::get('aportaciones/{id}/Borrar'      ,'aportacionesController@actionBorrarApor')->name('borrarApor');
    //Route::get('aportaciones/excel'           ,'aportacionesController@exportAporExcel')->name('aporExcel');
    //Route::get('aportaciones/pdf'             ,'aportacionesController@exportAporPdf')->name('aporPDF');    

    //IAPS Informacion de asistencia social y contable
    Route::get('asistsocycont/nueva'             ,'asycController@actionNuevaAsyc')->name('nuevaAsyc');
    Route::post('asistsocycont/nueva/alta'       ,'asycController@actionAltaNuevaAsyc')->name('AltaNuevaAsyc');
    Route::get('asistsocycont/ver/todas'         ,'asycController@actionVerAsyc')->name('verAsyc');
    Route::get('asistsocycont/{id}/editar/asyc'  ,'asycController@actionEditarAsyc')->name('editarAsyc');
    Route::put('asistsocycont/{id}/actualizar'   ,'asycController@actionActualizarAsyc')->name('actualizarAsyc');
    Route::get('asistsocycont/{id}/editar/asyc2' ,'asycController@actionEditarAsyc2')->name('editarAsyc2');
    Route::put('asistsocycont/{id}/actualizar2'  ,'asycController@actionActualizarAsyc2')->name('actualizarAsyc2');
    Route::get('asistsocycont/{id}/editar/asyc3' ,'asycController@actionEditarAsyc3')->name('editarAsyc3');
    Route::put('asistsocycont/{id}/actualizar3'  ,'asycController@actionActualizarAsyc3')->name('actualizarAsyc3');
    Route::get('asistsocycont/{id}/editar/asyc4' ,'asycController@actionEditarAsyc4')->name('editarAsyc4');
    Route::put('asistsocycont/{id}/actualizar4'  ,'asycController@actionActualizarAsyc4')->name('actualizarAsyc4');
    Route::get('asistsocycont/{id}/editar/asyc5' ,'asycController@actionEditarAsyc5')->name('editarAsyc5');
    Route::put('asistsocycont/{id}/actualizar5'  ,'asycController@actionActualizarAsyc5')->name('actualizarAsyc5');    
    Route::get('asistsocycont/{id}/editar/asyc6' ,'asycController@actionEditarAsyc6')->name('editarAsyc6');
    Route::put('asistsocycont/{id}/actualizar6'  ,'asycController@actionActualizarAsyc6')->name('actualizarAsyc6');
    Route::get('asistsocycont/{id}/editar/asyc7' ,'asycController@actionEditarAsyc7')->name('editarAsyc7');
    Route::put('asistsocycont/{id}/actualizar7'  ,'asycController@actionActualizarAsyc7')->name('actualizarAsyc7');
    Route::get('asistsocycont/{id}/editar/asyc8' ,'asycController@actionEditarAsyc8')->name('editarAsyc8');
    Route::put('asistsocycont/{id}/actualizar8'  ,'asycController@actionActualizarAsyc8')->name('actualizarAsyc8');
    Route::get('asistsocycont/{id}/editar/asyc9' ,'asycController@actionEditarAsyc9')->name('editarAsyc9');
    Route::put('asistsocycont/{id}/actualizar9'  ,'asycController@actionActualizarAsyc9')->name('actualizarAsyc9');
    Route::get('asistsocycont/{id}/editar/asyc10','asycController@actionEditarAsyc10')->name('editarAsyc10');
    Route::put('asistsocycont/{id}/actualizar10' ,'asycController@actionActualizarAsyc10')->name('actualizarAsyc10');    
    Route::get('asistconycont/{id}/Borrar'       ,'asycController@actionBorrarAsyc')->name('borrarAsyc');
    //Route::get('aportaciones/excel'           ,'aportacionesController@exportAporExcel')->name('aporExcel');
    //Route::get('aportaciones/pdf'             ,'aportacionesController@exportAporPdf')->name('aporPDF');        

    //Cursos de capacitación
    Route::get('cursos/nuevo'            ,'cursosController@actionNuevoCurso')->name('nuevoCurso');
    Route::post('cursos/nuevo/alta'      ,'cursosController@actionAltaNuevoCurso')->name('AltaNuevoCurso');
    Route::get('cursos/ver/todos'        ,'cursosController@actionVerCursos')->name('verCursos');
    Route::get('cursos/{id}/editar/curso','cursosController@actionEditarCurso')->name('editarCurso');
    Route::put('cursos/{id}/actualizar'  ,'cursosController@actionActualizarCurso')->name('actualizarCurso');
    Route::get('cursos/{id}/Borrar'      ,'cursosController@actionBorrarCurso')->name('borrarCurso');
    //Route::get('aportaciones/excel'           ,'aportacionesController@exportAporExcel')->name('aporExcel');
    //Route::get('aportaciones/pdf'             ,'aportacionesController@exportAporPdf')->name('aporPDF');    

    //IAPS Diigencias
    Route::get('progdil/nuevo'           ,'progdilController@actionNuevoProgdil')->name('nuevoProgdil');
    Route::post('progdil/nuevo/alta'     ,'progdilController@actionAltaNuevoProgdil')->name('AltaNuevoProgdil');
    Route::get('progdil/ver/todas'       ,'progdilController@actionVerProgdil')->name('verProgdil');
    Route::get('progdil/buscar/todas'    ,'progdilController@actionBuscarProgdil')->name('buscarProgdil');    
    Route::get('progdil/{id}/editar/progdilig','progdilController@actionEditarProgdil')->name('editarProgdil');
    Route::put('progdil/{id}/actualizar' ,'progdilController@actionActualizarProgdil')->name('actualizarProgdil');
    Route::get('progdil/{id}/Borrar'     ,'progdilController@actionBorrarProgdil')->name('borrarProgdil');
    //Route::get('progdil/excel'           ,'progdilController@exportProgdilExcel')->name('ProgdilExcel');
    Route::get('progdil/{id}/pdf'        ,'progdilController@actionMandamientoPDF')->name('mandamientoPDF');

});

