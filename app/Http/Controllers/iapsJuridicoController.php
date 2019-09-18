<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\iapsRequest;
use App\Http\Requests\iapsjuridicoRequest;
use App\regIapModel;
use App\regIapJuridicoModel;
use App\regVigenciaModel;
use App\regInmuebleedoModel;
//use APP\regAniosModel;
use APP\reganioModel;
use App\regBitacoraModel;
use App\regMunicipioModel;
use App\regRubroModel;
use App\regEntidadesModel;

// Exportar a excel 
//use App\Exports\ExcelExportCatIAPS;
//use Maatwebsite\Excel\Facades\Excel;
/// Exportar a pdf
use PDF;
///use Options;

class iapsjuridicoController extends Controller
{

    public function actionNuevaIapj(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $estructura   = session()->get('estructura');
        $id_estruc    = session()->get('id_estructura');
        $id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        //$regmunicipio= regMunicipioModel::obCatMunicipios();
        //$regrubro    = regRubroModel::obCatRubros();
        $regentidades  = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')->get();
        $regmunicipio  = regMunicipioModel::join('CAT_ENTIDADES_FEDERATIVAS','CAT_ENTIDADES_FEDERATIVAS.ENTIDADFEDERATIVA_ID', '=', 'CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','CAT_ENTIDADES_FEDERATIVAS.ENTIDADFEDERATIVA_DESC','CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9,15,22])
                        ->orderBy('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();
        $regrubro      = regRubroModel::select('RUBRO_ID','RUBRO_DESC')->get();  
        $regvigencia   = regVigenciaModel::select('ANIO_ID', 'ANIO_DESC')->get();
        $reginmuebles  = regInmuebleedoModel::select('INM_ID','INM_DESC')->get();
        $regiap        = regIapModel::select('IAP_ID','IAP_DESC','IAP_CALLE','IAP_NUM','IAP_COLONIA','IAP_STATUS')->get();
        $regiapjuridico=regIapJuridicoModel::select('IAP_ID','IAP_ACT_CONST','IAP_RFC','IAP_RPP','ANIO_ID',
                         'IAP_FVP','INM_ID','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('IAP_ID','ASC')
                        ->get();
        //dd($unidades);
        return view('sicinar.iaps.nuevaIapj',compact('regrubro','regmunicipio','regentidades','regvigencia','reginmuebles','regiap','nombre','usuario','estructura','id_estructura','regiapjuridico'));
    }

    public function actionAltaNuevaIapj(Request $request){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $estructura   = session()->get('estructura');
        $id_estruc    = session()->get('id_estructura');
        $id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        /************ Obtenemos la IP ***************************/                
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }        

        /************* Registrar en archivo de datos juridicos ************/
        $nuevaiapjuridico = new regIapJuridicoModel();    
        
        $name1 =null;
        //Comprobar  si el campo foto1 tiene un archivo asignado:
        if($request->hasFile('iap_act_const')){
           $name1 = $request->iap_id.'_'.$request->file('iap_act_const')->getClientOriginalName(); 
           //$file->move(public_path().'/images/', $name1);
           //sube el archivo a la carpeta del servidor public/images/
           $request->file('iap_act_const')->move(public_path().'/images/', $name1);
        }
        $name2 =null;
        //Comprobar  si el campo rfc tiene un archivo asignado:        
        if($request->hasFile('iap_rfc')){
           $name2 = $request->iap_id.'_'.$request->file('iap_rfc')->getClientOriginalName(); 
           //sube el archivo a la carpeta del servidor public/images/
           $request->file('iap_rfc')->move(public_path().'/images/', $name2);
        }        
        $nuevaiapjuridico->IAP_ID  = $request->iap_id;
        $nuevaiapjuridico->IAP_ACT_CONST= $name1;
        $nuevaiapjuridico->IAP_RFC = $name2;
        $nuevaiapjuridico->IAP_RPP = $request->iap_rpp;
        $nuevaiapjuridico->ANIO_ID = $request->anio_id;
        $nuevaiapjuridico->IAP_FVP = date('Y/m/d', strtotime($request->iap_fvp));
        $nuevaiapjuridico->INM_ID  = $request->inm_id;

        $nuevaiapjuridico->IP     = $ip;             // IP
        $nuevaiapjuridico->LOGIN  = $nombre;         // Usuario           
        $nuevaiapjuridico->save();

        if($nuevaiapjuridico->save() == true){
            toastr()->success('Datos jurídicos dados de alta correctamente.','dado de alta!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
            //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','estructura','id_estructura','rango','preguntas','apartados'));
        }else{
            toastr()->error('Error inesperado al registrar datos jurídicos. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //return back();
            //return redirect()->route('nuevoProceso');
        }
        /************* Termina registro de datos juridicos ****************/

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         9;
        $xfuncion_id  =      9001;
        $xtrx_id      =       145;    //Alta de IAP

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                    ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $request->iap_id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $request->iap_id;// Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $request->iap_id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************           
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $request->iap_id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/ 
        return redirect()->route('verIapj');
    }

    
    public function actionVerIapj(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $estructura   = session()->get('estructura');
        $id_estruc    = session()->get('id_estructura');
        $id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')                            
                           ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                           ->get();

        $regmunicipio = regMunicipioModel::join('CAT_ENTIDADES_FEDERATIVAS','CAT_ENTIDADES_FEDERATIVAS.ENTIDADFEDERATIVA_ID', '=', 'CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','CAT_ENTIDADES_FEDERATIVAS.ENTIDADFEDERATIVA_DESC','CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9,15,22])
                        ->orderBy('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();

        //$regrubro     = regRubroModel::obtCatRubros();
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')->get();  
        $regvigencia = regVigenciaModel::select('ANIO_ID', 'ANIO_DESC')->get();
        $reginmuebles  = regInmuebleedoModel::select('INM_ID','INM_DESC')->get();
        $regiap = regIapModel::select('IAP_ID', 'IAP_DESC', 'IAP_CALLE','IAP_NUM','IAP_COLONIA','IAP_STATUS')->get();

        $regiapjuridico =regIapJuridicoModel::select('IAP_ID','IAP_ACT_CONST','IAP_RFC','IAP_RPP','ANIO_ID',
                         'IAP_FVP','INM_ID','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                         ->orderBy('IAP_ID','ASC')
                         ->paginate(30);
        if($regiap->count() <= 0){
            toastr()->error('No existen registros de datos jurídicos dadas de alta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaIapj');
        }
        return view('sicinar.iaps.verIapJuridico',compact('nombre','usuario','estructura','id_estructura','regiap','regentidades', 'regmunicipio', 'regrubro','regiapjuridico','regvigencia','reginmuebles'));
    }

    public function actionEditarIapj($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $estructura    = session()->get('estructura');
        $id_estruc     = session()->get('id_estructura');
        $id_estructura = rtrim($id_estruc," ");
        $rango         = session()->get('rango');

        $regvigencia = regVigenciaModel::select('ANIO_ID', 'ANIO_DESC')->get();

        $reginmuebles  = regInmuebleedoModel::select('INM_ID','INM_DESC')->get();        

        $regiap = regIapModel::select('IAP_ID', 'IAP_DESC', 'IAP_CALLE','IAP_NUM','IAP_COLONIA','MUNICIPIO_ID',          'ENTIDADFEDERATIVA_ID','RUBRO_ID','IAP_REGCONS','IAP_RFC','IAP_CP','IAP_FECCONS','IAP_TELEFONO','IAP_EMAIL','IAP_SWEB','IAP_PRES','IAP_REPLEGAL','IAP_SRIO','IAP_TESORERO','IAP_OBJSOC','GRUPO_ID','IAP_STATUS','IAP_FECCERTIFIC', 'IAP_GEOREF_LATITUD', 'IAP_GEOREF_LONGITUD', 'IAP_FOTO1', 'IAP_FOTO2', 'IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
            ->where('IAP_ID',$id)
            ->get();

        $regiapjuridico = regIapJuridicoModel::select('IAP_ID','IAP_ACT_CONST','IAP_RFC','IAP_RPP','ANIO_ID','IAP_FVP','INM_ID','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
            ->where('IAP_ID',$id)
            ->first();
        if($regiapjuridico->count() <= 0){
            toastr()->error('No existe apartado de datos juridicos de la IAPS.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verIapJuridico');
        }
        return view('sicinar.iaps.editarIapJuridico',compact('nombre','usuario','estructura','id_estructura','regiap','regvigencia','reginmuebles','regiapjuridico'));

    }

    public function actionActualizarIapj(iapsjuridicoRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $estructura    = session()->get('estructura');
        $id_estruc     = session()->get('id_estructura');
        $id_estructura = rtrim($id_estruc," ");
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         9;
        $xfuncion_id  =      9002;
        $xtrx_id      =       151;    //Actualizar datos juridicos        

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $id;             // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************                    

            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/         

        // **************** actualizar ******************************
        $regiapjuridico = regIapJuridicoModel::where('IAP_ID',$id);
        if($regiapjuridico->count() <= 0)
            toastr()->error('No existe apartado de datos juridicos.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            $regiapjuridico = regIapJuridicoModel::where('IAP_ID',$id)        
            ->update([                
                'INM_ID'       => $request->inm_id,
                'ANIO_ID'      => $request->anio_id,
                'IAP_FVP'      => date('Y/m/d', strtotime($request->iap_fvp)), //$request->iap_feccons
                'IAP_RPP'      => $request->iap_rpp,
                'IP_M'        => $ip,
                'LOGIN_M'     => $nombre,
                'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
            ]);
            toastr()->success('Datos jurídicos actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verIapj');
        //return view('sicinar.catalogos.verProceso',compact('nombre','usuario','estructura','id_estructura','regproceso'));
    }

    public function actionEditarIapj1($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $estructura    = session()->get('estructura');
        $id_estruc     = session()->get('id_estructura');
        $id_estructura = rtrim($id_estruc," ");
        $rango         = session()->get('rango');

        $regvigencia = regVigenciaModel::select('ANIO_ID', 'ANIO_DESC')->get();

        $reginmuebles  = regInmuebleedoModel::select('INM_ID','INM_DESC')->get();        

        $regiap = regIapModel::select('IAP_ID', 'IAP_DESC', 'IAP_CALLE','IAP_NUM','IAP_COLONIA','MUNICIPIO_ID',          'ENTIDADFEDERATIVA_ID','RUBRO_ID','IAP_REGCONS','IAP_RFC','IAP_CP','IAP_FECCONS','IAP_TELEFONO','IAP_EMAIL','IAP_SWEB','IAP_PRES','IAP_REPLEGAL','IAP_SRIO','IAP_TESORERO','IAP_OBJSOC','GRUPO_ID','IAP_STATUS','IAP_FECCERTIFIC', 'IAP_GEOREF_LATITUD', 'IAP_GEOREF_LONGITUD', 'IAP_FOTO1', 'IAP_FOTO2', 'IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
            ->where('IAP_ID',$id)
            ->get();

        $regiapjuridico = regIapJuridicoModel::select('IAP_ID','IAP_ACT_CONST','IAP_RFC','IAP_RPP','ANIO_ID','IAP_FVP','INM_ID','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
            ->where('IAP_ID',$id)
            ->first();
        if($regiapjuridico->count() <= 0){
            toastr()->error('No existe apartado de datos juridicos de la IAPS.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verIapj');
        }
        return view('sicinar.iaps.editarIapJuridico1',compact('nombre','usuario','estructura','id_estructura','regiap','regvigencia','reginmuebles','regiapjuridico'));

    }

    public function actionActualizarIapj1(iapsjuridicoRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $estructura    = session()->get('estructura');
        $id_estruc     = session()->get('id_estructura');
        $id_estructura = rtrim($id_estruc," ");
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         9;
        $xfuncion_id  =      9002;
        $xtrx_id      =       151;    //Actualizar datos juridicos        

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $id;             // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/         

        // **************** actualizar ******************************
        $regiapjuridico = regIapJuridicoModel::where('IAP_ID',$id);
        if($regiapjuridico->count() <= 0)
            toastr()->error('No existe apartado de datos juridicos.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{

            $name01 =null;
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d01 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('iap_act_const')){
                echo "Escribió en el campo de texto 3: " .'-'. $request->iap_act_const .'-'. "<br><br>"; 
                $name01 = $id.'_'.$request->file('iap_act_const')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_act_const')->move(public_path().'/images/', $name01);


                $regiapjuridico = regIapJuridicoModel::where('IAP_ID',$id)        
                                  ->update([                
                    'IAP_ACT_CONST'=> $name01,
                    'IP_M'        => $ip,
                    'LOGIN_M'     => $nombre,
                    'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                ]);
                toastr()->success('Datos jurídicos-Acta constituiva actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
        }
        return redirect()->route('verIapj');
        //return view('sicinar.catalogos.verProceso',compact('nombre','usuario','estructura','id_estructura','regproceso'));
    }    

public function actionEditarIapj2($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $estructura    = session()->get('estructura');
        $id_estruc     = session()->get('id_estructura');
        $id_estructura = rtrim($id_estruc," ");
        $rango         = session()->get('rango');

        $regvigencia = regVigenciaModel::select('ANIO_ID', 'ANIO_DESC')->get();

        $reginmuebles  = regInmuebleedoModel::select('INM_ID','INM_DESC')->get();        

        $regiap = regIapModel::select('IAP_ID', 'IAP_DESC', 'IAP_CALLE','IAP_NUM','IAP_COLONIA','MUNICIPIO_ID',          'ENTIDADFEDERATIVA_ID','RUBRO_ID','IAP_REGCONS','IAP_RFC','IAP_CP','IAP_FECCONS','IAP_TELEFONO','IAP_EMAIL','IAP_SWEB','IAP_PRES','IAP_REPLEGAL','IAP_SRIO','IAP_TESORERO','IAP_OBJSOC','GRUPO_ID','IAP_STATUS','IAP_FECCERTIFIC', 'IAP_GEOREF_LATITUD', 'IAP_GEOREF_LONGITUD', 'IAP_FOTO1', 'IAP_FOTO2', 'IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
            ->where('IAP_ID',$id)
            ->get();

        $regiapjuridico = regIapJuridicoModel::select('IAP_ID','IAP_ACT_CONST','IAP_RFC','IAP_RPP','ANIO_ID','IAP_FVP','INM_ID','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
            ->where('IAP_ID',$id)
            ->first();
        if($regiapjuridico->count() <= 0){
            toastr()->error('No existe apartado de datos juridicos de la IAPS.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verIapj');
        }
        return view('sicinar.iaps.editarIapJuridico2',compact('nombre','usuario','estructura','id_estructura','regiap','regvigencia','reginmuebles','regiapjuridico'));

    }

    public function actionActualizarIapj2(iapsjuridicoRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $estructura    = session()->get('estructura');
        $id_estruc     = session()->get('id_estructura');
        $id_estructura = rtrim($id_estruc," ");
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         9;
        $xfuncion_id  =      9002;
        $xtrx_id      =       151;    //Actualizar datos juridicos        

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $id;             // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         

            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/         

        // **************** actualizar ******************************
        $regiapjuridico = regIapJuridicoModel::where('IAP_ID',$id);
        if($regiapjuridico->count() <= 0)
            toastr()->error('No existe apartado de datos juridicos.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{

            $name02 =null;
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d01 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('iap_rfc')){
                echo "Escribió en el campo de texto 2: " .'-'. $request->iap_rfc .'-'. "<br><br>"; 
                $name02 = $id.'_'.$request->file('iap_rfc')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_rfc')->move(public_path().'/images/', $name02);


                $regiapjuridico = regIapJuridicoModel::where('IAP_ID',$id)        
                                  ->update([                
                    'IAP_RFC'     => $name02,
                    'IP_M'        => $ip,
                    'LOGIN_M'     => $nombre,
                    'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                ]);
                toastr()->success('Datos jurídicos-RFC ante el SAT actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
        }
        return redirect()->route('verIapj');
        //return view('sicinar.catalogos.verProceso',compact('nombre','usuario','estructura','id_estructura','regproceso'));
    }    


    public function actionBorrarIapj($id){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $estructura   = session()->get('estructura');
        $id_estruc    = session()->get('id_estructura');
        $id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        //echo 'Ya entre aboorar registro..........';
        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         9;
        $xfuncion_id  =      9002;
        $xtrx_id      =       152;     // Baja de datos juridicos

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $id;             // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         

            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/          

        /************ Elimina de datos juridicos ***************/
        $regiapjuridico = regIapJuridicoModel::where('IAP_ID',$id);
        if($regiapjuridico->count() <= 0)
            toastr()->error('No existe apartado de datos jurídicos.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regiapjuridico->delete();
            toastr()->success('Reg. de trx de inf. de datos jurídicos eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************* Termina de eliminar  la IAP **********************************/
        return redirect()->route('verIapj');
    }    


}
