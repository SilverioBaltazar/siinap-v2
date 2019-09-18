<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\iapsRequest;
use App\Http\Requests\iapsjuridicoRequest;
use App\regIapModel;
use App\regIapJuridicoModel;
use App\regBitacoraModel;
use App\regMunicipioModel;
use App\regRubroModel;
use App\regEntidadesModel;
// Exportar a excel 
use App\Exports\ExcelExportCatIAPS;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class iapsController extends Controller
{

    public function actionNuevaIap(){
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

        //$regmunicipio = regMunicipioModel::obCatMunicipios();
        //$regrubro     = regRubroModel::obCatRubros();

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')                            
                           ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                           ->get();

        $regmunicipio = regMunicipioModel::join('CAT_ENTIDADES_FEDERATIVAS','CAT_ENTIDADES_FEDERATIVAS.ENTIDADFEDERATIVA_ID', '=', 'CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','CAT_ENTIDADES_FEDERATIVAS.ENTIDADFEDERATIVA_DESC','CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9,15,22])
                        ->orderBy('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();

        //$regmunicipio = regMunicipioModel::select('ENTIDADFEDERATIVAID','MUNICIPIOID','MUNICIPIONOMBRE')
        //                   ->wherein('ENTIDADFEDERATIVAID',[9,15,22])
        //                   ->orderBy('MUNICIPIOID','asc')
        //                   ->get();
        
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')
                            ->orderBy('RUBRO_ID','asc')
                            ->get();  
        $regiapjuridico =regIapJuridicoModel::select('IAP_ID','IAP_ACT_CONST','IAP_RFC','IAP_RPP','ANIO_ID','IAP_FVP','INM_ID','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                         ->get();                             

        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC', 'IAP_CALLE','IAP_NUM','IAP_COLONIA','MUNICIPIO_ID',          'ENTIDADFEDERATIVA_ID','RUBRO_ID','IAP_REGCONS','IAP_RFC','IAP_CP','IAP_FECCONS','IAP_TELEFONO','IAP_EMAIL','IAP_SWEB','IAP_PRES','IAP_REPLEGAL','IAP_SRIO','IAP_TESORERO','IAP_OBJSOC', 'GRUPO_ID', 'IAP_STATUS', 'IAP_FECCERTIFIC','IAP_FOTO1','IAP_FOTO2','IAP_GEOREF_LATITUD','IAP_GEOREF_LONGITUD','IP',
            'LOGIN','FECHA_M','IP_M','LOGIN_M')
                                     ->orderBy('IAP_ID','asc')->get();
        //dd($unidades);
        return view('sicinar.iaps.nuevaIap',compact('regrubro','regmunicipio','regentidades','regiap','nombre','usuario','estructura','id_estructura','regiapjuridico'));
    }

    public function actionAltaNuevaIap(Request $request){
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

        /************ ALTA DE IAP *****************************/ 
        // Antes de sumar
        //echo settype($request->iap_iap_georef_latitud, 'double'); //Convertir a doble presicion      
        //echo var_dump($cifra_monetaria); // Te dirá el tipo de variable
        //$latitud = 0;
        //$latitud = number_format($request->iap_iap_georef_latitud,10);
        //if (isset($request->iap_iap_georef_latitud)&&is_double($request->iap_iap_georef_latitud)&&!empty($request->iap_iap_georef_latitud)) {
          //$latitud = settype($request->iap_iap_georef_latitud, 'double');
        //}
        //$latitud  = settype($request->iap_iap_georef_latitud, 'double'); //Convertir a doble presicion              
        //$longitud = 0;
        //if (isset($request->iap_iap_georef_longitud)&&is_double($request->iap_iap_georef_longitud)&&!empty($request->iap_iap_georef_longitud)) {
           //$longitud = settype($request->iap_iap_georef_longitud, 'double'); //Convertir a doble presicion 
        //}        

     
        //https://ajgallego.gitbooks.io/laravel-5/content/capitulo_4_datos_de_entrada.html
        // video https://www.youtube.com/watch?v=1Z7oson-G8M
        // Mover el fichero a la ruta conservando el nombre original:         
        //$request->file('photo')->move($destinationPath);
        // Mover el fichero a la ruta con un nuevo nombre:
        //$request->file('photo')->move($destinationPath, $fileName);

        //$name2 = $request->file('iap_foto2')->getClientOriginalName();
        //$name1 = $request->file('iap_foto1')->getClientOriginalName();    ok
        $iap_id = regIapModel::max('IAP_ID');
        $iap_id = $iap_id+1;

        $nuevaiap = new regIapModel();
        $name1 =null;
        //Comprobar  si el campo foto1 tiene un archivo asignado:
        if($request->hasFile('iap_foto1')){
           $name1 = $iap_id.'_'.$request->file('iap_foto1')->getClientOriginalName(); 
           //$file->move(public_path().'/images/', $name1);
           //sube el archivo a la carpeta del servidor public/images/
           $request->file('iap_foto1')->move(public_path().'/images/', $name1);
        }
        $name2 =null;
        //Comprobar  si el campo foto2 tiene un archivo asignado:        
        if($request->hasFile('iap_foto2')){
           $name2 = $iap_id.'_'.$request->file('iap_foto2')->getClientOriginalName(); 
           //sube el archivo a la carpeta del servidor public/images/
           $request->file('iap_foto2')->move(public_path().'/images/', $name2);
        }

        $nuevaiap->IAP_ID      = $iap_id;
        $nuevaiap->IAP_DESC    = strtoupper($request->iap_desc);
        $nuevaiap->IAP_CALLE   = strtoupper($request->iap_calle);
        $nuevaiap->IAP_NUM     = strtoupper($request->iap_num);
        $nuevaiap->IAP_COLONIA = strtoupper($request->iap_colonia);
        $nuevaiap->MUNICIPIO_ID= $request->municipio_id;
        $nuevaiap->ENTIDADFEDERATIVA_ID = $request->entidadfederativa_id;
        $nuevaiap->RUBRO_ID    = $request->rubro_id;
        $nuevaiap->IAP_REGCONS = strtoupper($request->iap_regcons);
        $nuevaiap->IAP_RFC     = strtoupper($request->iap_rfc);
        $nuevaiap->IAP_CP      = $request->iap_cp;
        $nuevaiap->IAP_FECCONS = date('Y/m/d', strtotime($request->iap_feccons));
        $nuevaiap->IAP_TELEFONO= strtoupper($request->iap_telefono);
        $nuevaiap->IAP_EMAIL   = strtolower($request->iap_email);
        $nuevaiap->IAP_SWEB    = strtolower($request->iap_sweb);
        $nuevaiap->IAP_PRES    = strtoupper($request->iap_pres);
        $nuevaiap->IAP_REPLEGAL= strtoupper($request->iap_replegal);
        $nuevaiap->IAP_SRIO    = strtoupper($request->iap_srio);        
        $nuevaiap->IAP_TESORERO= strtoupper($request->iap_tesorero);
        $nuevaiap->IAP_OBJSOC  = strtoupper($request->iap_objsoc);
        //$nuevaiap->IAP_GEOREF_LATITUD  = $latitud; 
        //$nuevaiap->IAP_GEOREF_LATITUD  = bcdiv($request->iap_iap_georef_latitud, '1',10);
        $nuevaiap->IAP_GEOREF_LONGITUD = number_format($request->iap_iap_georef_longitud,10);
        //$nuevaiap->IAP_FOTO1   = $request->iap_foto1;
        $nuevaiap->IAP_FOTO1   = $name1;
        $nuevaiap->IAP_FOTO2   = $name2;
        $nuevaiap->IP          = $ip;
        $nuevaiap->LOGIN       = $nombre;         // Usuario ;
        $nuevaiap->save();

        if($nuevaiap->save() == true){
            toastr()->success('La IAP dada de alta correctamente.','IAP dado de alta!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
            //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','estructura','id_estructura','rango','preguntas','apartados'));
        }else{
            toastr()->error('Error inesperado al dar de alta la IAP. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //return back();
            //return redirect()->route('nuevoProceso');
        }

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
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $iap_id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $iap_id;         // Folio    
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
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $iap_id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         

            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $iap_id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/ 

        return redirect()->route('verIap');
    }

    
    public function actionVerIap(){
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
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')
                            ->orderBy('RUBRO_ID','asc')
                            ->get();                         

        $regiap = regIapModel::select('IAP_ID', 'IAP_DESC', 'IAP_CALLE','IAP_NUM','IAP_COLONIA','MUNICIPIO_ID',          'ENTIDADFEDERATIVA_ID','RUBRO_ID','IAP_REGCONS','IAP_RFC','IAP_CP','IAP_FECCONS','IAP_TELEFONO','IAP_EMAIL','IAP_SWEB','IAP_PRES','IAP_REPLEGAL','IAP_SRIO','IAP_TESORERO','IAP_OBJSOC','GRUPO_ID',
            'IAP_FOTO1','IAP_FOTO2','IAP_GEOREF_LATITUD','IAP_GEOREF_LONGITUD','IAP_STATUS',
            'IAP_FECCERTIFIC','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
            ->orderBy('IAP_ID','ASC')
            ->paginate(30);
        if($regiap->count() <= 0){
            toastr()->error('No existen registros de IAPS dadas de alta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaIap');
        }
        return view('sicinar.iaps.verIap',compact('nombre','usuario','estructura','id_estructura','regiap','regentidades', 'regmunicipio', 'regrubro'));

    }

    public function actionEditarIap($id){
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

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')                            
                           ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                           ->get();

        $regmunicipio = regMunicipioModel::join('CAT_ENTIDADES_FEDERATIVAS','CAT_ENTIDADES_FEDERATIVAS.ENTIDADFEDERATIVA_ID', '=', 'CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','CAT_ENTIDADES_FEDERATIVAS.ENTIDADFEDERATIVA_DESC','CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9,15,22])
                        ->orderBy('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();

        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')
                            ->orderBy('RUBRO_ID','asc')
                            ->get();        

        $regiap = regIapModel::select('IAP_ID', 'IAP_DESC', 'IAP_CALLE','IAP_NUM','IAP_COLONIA','MUNICIPIO_ID',          'ENTIDADFEDERATIVA_ID','RUBRO_ID','IAP_REGCONS','IAP_RFC','IAP_CP','IAP_FECCONS','IAP_TELEFONO','IAP_EMAIL','IAP_SWEB','IAP_PRES','IAP_REPLEGAL','IAP_SRIO','IAP_TESORERO','IAP_OBJSOC','GRUPO_ID','IAP_STATUS','IAP_FECCERTIFIC', 'IAP_GEOREF_LATITUD', 'IAP_GEOREF_LONGITUD', 'IAP_FOTO1', 'IAP_FOTO2', 'IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
            ->where('IAP_ID',$id)
            ->orderBy('IAP_ID','ASC')
            ->first();
        if($regiap->count() <= 0){
            toastr()->error('No existe registros de IAPS.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaIap');
        }
        return view('sicinar.iaps.editarIap',compact('nombre','usuario','estructura','id_estructura','regiap','regentidades','regmunicipio','regrubro'));

    }

    public function actionActualizarIap(iapsRequest $request, $id){
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
        $xfuncion_id  =      9001;
        $xtrx_id      =       146;    //Actualizar IAPS        

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
        $regiap = regIapModel::where('IAP_ID',$id);
        if($regiap->count() <= 0)
            toastr()->error('No existe IAP.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //                'IAP_GEOREF_LATITUD' => $request->iap_georef_latitud, 
            //    'IAP_GEOREF_LONGITUD'=> $request->iap_georef_longitud, 
            //    'IAP_FOTO1'   => $request->iap_foto1, 
            //    'IAP_FOTO2'   => $request->iap_foto2,
            $name1 =null;
            //if (isset($request->iap_foto1)||empty($request->iap_foto1)||is_null($reques->iap_foto1)) {
            //if (isset($request->iap_foto1)||empty($request->iap_foto1)||is_null($reques->iap_foto1)) {
            //if(isset($_PUT['submit'])){
            //   if(!empty($_PUT['iap_foto1'])){
            if(isset($request->iap_foto1)){
                if(!empty($request->iap_foto1)){
                    //Comprobar  si el campo foto1 tiene un archivo asignado:
                    if($request->hasFile('iap_foto1')){
                      $name1 = $id.'_'.$request->file('iap_foto1')->getClientOriginalName(); 
                      //sube el archivo a la carpeta del servidor public/images/
                      $request->file('iap_foto1')->move(public_path().'/images/', $name1);
                    }
                }
            }
            else
                $name1 = $request->iap_foto1;

            $name2 =null;
            if (isset($request->iap_foto2)||empty($request->iap_foto2)||is_null($reques->iap_foto2)) {
               //Comprobar  si el campo foto2 tiene un archivo asignado:        
               if($request->hasFile('iap_foto2')){
                   $name2 = $id.'_'.$request->file('iap_foto2')->getClientOriginalName(); 
                   //sube el archivo a la carpeta del servidor public/images/
                   $request->file('iap_foto2')->move(public_path().'/images/', $name2);
               }
            }
            else
                $name2 = $request->iap_foto2;


            $regiap = regIapModel::where('IAP_ID',$id)        
            ->update([                
                'IAP_DESC'    => strtoupper($request->iap_desc),
                'IAP_CALLE'   => strtoupper($request->iap_calle),
                'IAP_NUM'     => strtoupper($request->iap_num),
                'IAP_COLONIA' => strtoupper($request->iap_colonia),
                'ENTIDADFEDERATIVA_ID' => $request->entidadfederativa_id,                
                'MUNICIPIO_ID'=> $request->municipio_id,
                'RUBRO_ID'    => $request->rubro_id,
                'IAP_REGCONS' => strtoupper($request->iap_regcons),
                'IAP_RFC'     => strtoupper($request->iap_rfc),
                'IAP_CP'      => $request->iap_cp,
                'IAP_FECCONS' => date('Y/m/d', strtotime($request->iap_feccons)), //$request->iap_feccons
                'IAP_TELEFONO'=> strtoupper($request->iap_telefono),
                'IAP_EMAIL'   => strtolower($request->iap_email),
                'IAP_SWEB'    => strtolower($request->iap_sweb),
                'IAP_PRES'    => strtoupper($request->iap_pres),
                'IAP_REPLEGAL'=> strtoupper($request->iap_replegal),
                'IAP_SRIO'    => strtoupper($request->iap_srio),        
                'IAP_TESORERO'=> strtoupper($request->iap_tesorero),
                'IAP_OBJSOC'  => strtoupper($request->iap_objsoc),
                //'IAP_FOTO1'   => $name1,
                //'IAP_FOTO2'   => $name2,
                'IAP_STATUS'  => $request->iap_status,                
                'IP_M'        => $ip,
                'LOGIN_M'     => $nombre,
                'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
            ]);
            toastr()->success('IAP actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verIap');
        //return view('sicinar.catalogos.verProceso',compact('nombre','usuario','estructura','id_estructura','regproceso'));

    }

public function actionBorrarIap($id){
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
        $xfuncion_id  =      9001;
        $xtrx_id      =       147;     // Baja de IAP

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
        /************ Elimina la IAP **************************************/
        $regiap = regIapModel::select('IAP_ID','IAP_DESC','IAP_CALLE','IAP_NUM','IAP_COLONIA','MUNICIPIO_ID', 'ENTIDADFEDERATIVA_ID','RUBRO_ID','IAP_REGCONS','IAP_RFC','IAP_CP','IAP_FECCONS','IAP_TELEFONO','IAP_EMAIL','IAP_SWEB','IAP_PRES','IAP_REPLEGAL','IAP_SRIO','IAP_TESORERO','IAP_OBJSOC','GRUPO_ID','IAP_STATUS','IAP_FECCERTIFIC','IAP_GEOREF_LATITUD', 'IAP_GEOREF_LONGITUD', 'IAP_FOTO1', 'IAP_FOTO2', 'IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                              ->where('IAP_ID',$id);
        //                    ->find('RUBRO_ID',$id);
        if($regiap->count() <= 0)
            toastr()->error('No existe IAP.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regiap->delete();
            toastr()->success('IAP ha sido eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************* Termina de eliminar  la IAP **********************************/
        return redirect()->route('verIap');
    }    

    // exportar a formato catalogo de IAPs a formato excel
    public function exportCatIapsExcel(){
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
        
        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         9;
        $xfuncion_id  =      9001;
        $xtrx_id      =       148;            // Exportar a formato Excel
        $id           =         0;

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

        return Excel::download(new ExcelExportCatIAPS, 'Cat_IAPS_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato catalogo de IAPS a formato PDF
    public function exportCatIapsPdf(){
        set_time_limit(0);
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 0);

        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $estructura    = session()->get('estructura');
        $id_estruc     = session()->get('id_estructura');
        $id_estructura = rtrim($id_estruc," ");
        $rango         = session()->get('rango');
        $ip           = session()->get('ip');

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         9;
        $xfuncion_id  =      9001;
        $xtrx_id      =       149;       //Exportar a formato PDF
        $id           =         0;

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

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')                            
                                           ->get();
        $regmunicipio = regMunicipioModel::select('ENTIDADFEDERATIVAID', 'MUNICIPIOID', 'MUNICIPIONOMBRE')
                                         ->wherein('ENTIDADFEDERATIVAID',[9,15,22])
                                         ->get();                           
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')
                                     ->get();                         
        $regiap = regIapModel::select('IAP_ID','IAP_DESC','IAP_CALLE','IAP_NUM', 'IAP_COLONIA','IAP_TELEFONO',
                                      'IAP_STATUS', 'IAP_FECREG')
                                ->orderBy('IAP_ID','ASC')
                                ->get();
        //$regiap = regIapModel::join('CAT_MUNICIPIOS_SEDESEM','CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','JP_IAPS.MUNICIPIO_ID')
        //                      ->wherein('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9,15,22])
        //                ->join('CAT_ENTIDADES_FEDERATIVAS','CAT_ENTIDADES_FEDERATIVAS.ENTIDADFEDERATIVA_ID', '=', 
        //                                                                 'CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
        //              ->join('JP_CAT_RUBROS'         ,'JP_CAT_RUBROS.RUBRO_ID','=','JP_IAPS.RUBRO_ID')
        //              ->select('JP_IAPS.IAP_ID', 'JP_IAPS.IAP_DESC',    'JP_IAPS.IAP_CALLE', 'JP_IAPS.IAP_NUM',
        //                  'JP_IAPS.IAP_COLONIA', 
        //                  'JP_IAPS.ENTIDADFEDERATIVA_ID','CAT_ENTIDADES_FEDERATIVAS.ENTIDADFEDERATIVA_DESC',
        //                  'JP_IAPS.MUNICIPIO_ID','CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE',         
        //                  'JP_IAPS.RUBRO_ID',    'JP_CAT_RUBROS.RUBRO_DESC', 'JP_IAPS.IAP_REGCONS', 'JP_IAPS.IAP_RFC',    
        //                  'JP_IAPS.IAP_CP',      'JP_IAPS.IAP_FECCONS',      'JP_IAPS.IAP_TELEFONO','JP_IAPS.IAP_EMAIL',   
        //                  'JP_IAPS.IAP_SWEB',    'JP_IAPS.IAP_PRES',         'JP_IAPS.IAP_REPLEGAL','JP_IAPS.IAP_SRIO',    
        //                  'JP_IAPS.IAP_TESORERO','JP_IAPS.IAP_OBJSOC',       'JP_IAPS.IAP_STATUS',  
        //                  'JP_IAPS.IAP_FECCERTIFIC','JP_IAPS.IAP_FECREG')
        //                  ->orderBy('JP_IAPS.IAP_ID','ASC')
        //                  ->get();                                 
        if($regiap->count() <= 0){
            toastr()->error('No existen registros en el catalogo de IAPS.','Uppss!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verIap');
        }
        $pdf = PDF::loadView('sicinar.pdf.catiapsPDF', compact('nombre','usuario','regentidades','regmunicipio','regrubro','regiap'));
        //$options = new Options();
        //$options->set('defaultFont', 'Courier');
        //$pdf->set_option('defaultFont', 'Courier');
        $pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');          
        //$pdf->setPaper('A4','portrait');

        // Output the generated PDF to Browser
        return $pdf->stream('CatalogoDeIAPS');
    }

    // Gráfica de IAP por municipio
    public function IapxMpio(){
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

        $regtotxmpio=regIapModel::join('CAT_MUNICIPIOS_SEDESEM',[['CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','JP_IAPS.MUNICIPIO_ID']])
                         ->selectRaw('COUNT(*) AS TOTALXMPIO')
                               ->get();

        $regiap=regIapModel::join('CAT_MUNICIPIOS_SEDESEM',[['CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','JP_IAPS.MUNICIPIO_ID']])
                      ->selectRaw('JP_IAPS.MUNICIPIO_ID, CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO,COUNT(*) AS TOTAL')
                        ->groupBy('JP_IAPS.MUNICIPIO_ID', 'CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->orderBy('JP_IAPS.MUNICIPIO_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapsxmpio',compact('regiap','regtotxmpio','nombre','usuario','estructura','id_estructura','rango'));
    }

    // Gráfica de IAP por Rubro social
    public function IapxRubro(){
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

        $regtotxrubro=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_IAPS.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regiap=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_IAPS.RUBRO_ID')
                      ->selectRaw('JP_IAPS.RUBRO_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_IAPS.RUBRO_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('JP_IAPS.RUBRO_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapsxrubro',compact('regiap','regtotxrubro','nombre','usuario','estructura','id_estructura','rango'));
    }

    // Gráfica de IAP por Rubro social
    public function IapxRubro2(){
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

        $regtotxrubro=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_IAPS.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regiap=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_IAPS.RUBRO_ID')
                      ->selectRaw('JP_IAPS.RUBRO_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_IAPS.RUBRO_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('JP_IAPS.RUBRO_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.graficadeprueba',compact('regiap','regtotxrubro','nombre','usuario','estructura','id_estructura','rango'));
    }

    // Mapas
    public function Mapas(){
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

        $regtotxrubro=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_IAPS.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regiap=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_IAPS.RUBRO_ID')
                      ->selectRaw('JP_IAPS.RUBRO_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_IAPS.RUBRO_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('JP_IAPS.RUBRO_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba',compact('regiap','regtotxrubro','nombre','usuario','estructura','id_estructura','rango'));
    }

    // Mapas
    public function Mapas2(){
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

        $regtotxrubro=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_IAPS.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regiap=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_IAPS.RUBRO_ID')
                      ->selectRaw('JP_IAPS.RUBRO_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_IAPS.RUBRO_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('JP_IAPS.RUBRO_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba2',compact('regiap','regtotxrubro','nombre','usuario','estructura','id_estructura','rango'));
    }

    // Mapas
    public function Mapas3(){
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

        $regtotxrubro=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_IAPS.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regiap=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_IAPS.RUBRO_ID')
                      ->selectRaw('JP_IAPS.RUBRO_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_IAPS.RUBRO_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('JP_IAPS.RUBRO_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba3',compact('regiap','regtotxrubro','nombre','usuario','estructura','id_estructura','rango'));
    }


}
