<?php
//**************************************************************/
//* File:       asycController.php
//* Proyecto:   Sistema SIINAP.V2 JAPEM
//¨Función:     Clases para el modulo de asistencia social y Contable
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: septiembre 2019
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\asycRequest;
use App\regIapModel;
use App\regBitacoraModel;
use App\regPfiscalesModel;
use App\regAsistContableModel;
use App\regPerModel;
use App\regNumerosModel;
// Exportar a excel 
//use App\Exports\ExcelExportCatIAPS;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class asycController extends Controller
{

    //******** Mostrar los documentos de asistencia social y contable *****//
    public function actionVerAsyc(){
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

        $regper       = regPerModel::select('PER_ID', 'PER_DESC')->get();
        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();        
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->get();

        $regasyc = regAsistContableModel::select('IAP_FOLIO','IAP_ID','PERIODO_ID',
            'IAP_D01','PER01_ID','NUM01_ID','IAP_EDO01','IAP_D02','PER02_ID','NUM02_ID','IAP_EDO02',
            'IAP_D03','PER03_ID','NUM03_ID','IAP_EDO03','IAP_D04','PER04_ID','NUM04_ID','IAP_EDO04',
            'IAP_D05','PER05_ID','NUM05_ID','IAP_EDO05','IAP_D06','PER06_ID','NUM06_ID','IAP_EDO06',
            'IAP_D07','PER07_ID','NUM07_ID','IAP_EDO07','IAP_D08','PER08_ID','NUM08_ID','IAP_EDO08',
            'IAP_D09','PER09_ID','NUM09_ID','IAP_EDO09','IAP_D10','PER10_ID','NUM10_ID','IAP_EDO10',
            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('IAP_ID','ASC')
                        ->orderBy('PERIODO_ID','ASC')
                        ->paginate(30);
        if($regasyc->count() <= 0){
            toastr()->error('No existen registros de asistencia social y contables.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaAsyc');
        }
        return view('sicinar.asyc.verAsyc',compact('nombre','usuario','estructura','id_estructura','regiap','regper', 'regnumeros', 'regperiodos','regasyc'));
    }


    public function actionNuevaAsyc(){
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

        $regper       = regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();
        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')
                        ->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->orderBy('PERIODO_ID','asc')
                        ->get();        
       $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();                                
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->orderBy('IAP_DESC','asc')
                        ->get();

        $regasyc = regAsistContableModel::select('IAP_FOLIO','IAP_ID','PERIODO_ID',
            'IAP_D01','PER01_ID','NUM01_ID','IAP_EDO01','IAP_D02','PER02_ID','NUM02_ID','IAP_EDO02',
            'IAP_D03','PER03_ID','NUM03_ID','IAP_EDO03','IAP_D04','PER04_ID','NUM04_ID','IAP_EDO04',
            'IAP_D05','PER05_ID','NUM05_ID','IAP_EDO05','IAP_D06','PER06_ID','NUM06_ID','IAP_EDO06',
            'IAP_D07','PER07_ID','NUM07_ID','IAP_EDO07','IAP_D08','PER08_ID','NUM08_ID','IAP_EDO08',
            'IAP_D09','PER09_ID','NUM09_ID','IAP_EDO09','IAP_D10','PER10_ID','NUM10_ID','IAP_EDO10',
            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('IAP_FOLIO','ASC')
                        ->get();        
        //dd($unidades);
        return view('sicinar.asyc.nuevaAsyc',compact('regper','regnumeros','regiap','regperiodos','regperiodicidad','nombre','usuario','estructura','id_estructura','regasyc'));
    }

    public function actionAltaNuevaAsyc(Request $request){
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

        /************ Registro de la aportación monetaria *****************************/ 
        $iap_folio = regAsistContableModel::max('IAP_FOLIO');
        $iap_folio = $iap_folio+1;
        $nuevaasyc = new regAsistContableModel();

        $file01 =null;
        if(isset($request->iap_d01)){
            if(!empty($request->iap_d01)){
                //Comprobar  si el campo act_const tiene un archivo asignado:
                if($request->hasFile('iap_d01')){
                    $file01=$iap_folio.'_'.$request->file('iap_d01')->getClientOriginalName();
                    //sube el archivo a la carpeta del servidor public/images/
                    $request->file('iap_d01')->move(public_path().'/images/', $file01);
                }
            }
        }
        $file02 =null;
        if(isset($request->iap_d02)){
            if(!empty($request->iap_d02)){
                //Comprobar  si el campo act_const tiene un archivo asignado:
                if($request->hasFile('iap_d02')){
                    $file02=$iap_folio.'_'.$request->file('iap_d02')->getClientOriginalName();
                    //sube el archivo a la carpeta del servidor public/images/
                    $request->file('iap_d02')->move(public_path().'/images/', $file02);
                }
            }
        }
        $file03 =null;
        if(isset($request->iap_d03)){
            if(!empty($request->iap_d03)){
                //Comprobar  si el campo act_const tiene un archivo asignado:
                if($request->hasFile('iap_d03')){
                    $file03=$iap_folio.'_'.$request->file('iap_d03')->getClientOriginalName();
                    //sube el archivo a la carpeta del servidor public/images/
                    $request->file('iap_d03')->move(public_path().'/images/', $file03);
                }
            }
        }
        $file04 =null;
        if(isset($request->iap_d04)){
            if(!empty($request->iap_d04)){
                //Comprobar  si el campo act_const tiene un archivo asignado:
                if($request->hasFile('iap_d04')){
                    $file04=$iap_folio.'_'.$request->file('iap_d04')->getClientOriginalName();
                    //sube el archivo a la carpeta del servidor public/images/
                    $request->file('iap_d04')->move(public_path().'/images/', $file04);
                }
            }
        }
        $file05 =null;
        if(isset($request->iap_d05)){
            if(!empty($request->iap_d05)){
                //Comprobar  si el campo act_const tiene un archivo asignado:
                if($request->hasFile('iap_d05')){
                    $file05=$iap_folio.'_'.$request->file('iap_d05')->getClientOriginalName();
                    //sube el archivo a la carpeta del servidor public/images/
                    $request->file('iap_d05')->move(public_path().'/images/', $file05);
                }
            }
        }
        $file06 =null;
        if(isset($request->iap_d06)){
            if(!empty($request->iap_d06)){
                //Comprobar  si el campo act_const tiene un archivo asignado:
                if($request->hasFile('iap_d06')){
                    $file06=$iap_folio.'_'.$request->file('iap_d06')->getClientOriginalName();
                    //sube el archivo a la carpeta del servidor public/images/
                    $request->file('iap_d06')->move(public_path().'/images/', $file06);
                }
            }
        }
        $file07 =null;
        if(isset($request->iap_d07)){
            if(!empty($request->iap_d07)){
                //Comprobar  si el campo act_const tiene un archivo asignado:
                if($request->hasFile('iap_d07')){
                    $file07=$iap_folio.'_'.$request->file('iap_d07')->getClientOriginalName();
                    //sube el archivo a la carpeta del servidor public/images/
                    $request->file('iap_d07')->move(public_path().'/images/', $file07);
                }
            }
        }
        $file08 =null;
        if(isset($request->iap_d08)){
            if(!empty($request->iap_d08)){
                //Comprobar  si el campo act_const tiene un archivo asignado:
                if($request->hasFile('iap_d08')){
                    $file08=$iap_folio.'_'.$request->file('iap_d08')->getClientOriginalName();
                    //sube el archivo a la carpeta del servidor public/images/
                    $request->file('iap_d08')->move(public_path().'/images/', $file08);
                }
            }
        }
        //else
        //    $iap_d08 = $request->iap_d08;

        $nuevaasyc->IAP_FOLIO    = $iap_folio;
        $nuevaasyc->PERIODO_ID   = $request->periodo_id;
        $nuevaasyc->IAP_ID       = $request->iap_id;        

        $nuevaasyc->IAP_D01      = $file01;        
        $nuevaasyc->NUM01_ID     = $request->num01_id;
        $nuevaasyc->PER01_ID     = $request->per01_id;        
        $nuevaasyc->IAP_EDO01    = $request->iap_edo01;        

        $nuevaasyc->IAP_D02      = $file02;        
        $nuevaasyc->NUM02_ID     = $request->num02_id;
        $nuevaasyc->PER02_ID     = $request->per02_id;        
        $nuevaasyc->IAP_EDO02    = $request->iap_edo02; 

        $nuevaasyc->IAP_D03      = $file03;        
        $nuevaasyc->NUM03_ID     = $request->num03_id;
        $nuevaasyc->PER03_ID     = $request->per03_id;        
        $nuevaasyc->IAP_EDO03    = $request->iap_edo03;         

        $nuevaasyc->IAP_D04      = $file04;        
        $nuevaasyc->NUM04_ID     = $request->num04_id;
        $nuevaasyc->PER04_ID     = $request->per04_id;        
        $nuevaasyc->IAP_EDO04    = $request->iap_edo04;

        $nuevaasyc->IAP_D05      = $file05;  
        $nuevaasyc->NUM05_ID     = $request->num05_id;
        $nuevaasyc->PER05_ID     = $request->per05_id;        
        $nuevaasyc->IAP_EDO05    = $request->iap_edo05;        

        $nuevaasyc->IAP_D06      = $file06;        
        $nuevaasyc->NUM06_ID     = $request->num06_id;
        $nuevaasyc->PER06_ID     = $request->per06_id;        
        $nuevaasyc->IAP_EDO06    = $request->iap_edo06; 

        $nuevaasyc->IAP_D07      = $file07;        
        $nuevaasyc->NUM07_ID     = $request->num07_id;
        $nuevaasyc->PER07_ID     = $request->per07_id;        
        $nuevaasyc->IAP_EDO07    = $request->iap_edo07;         

        $nuevaasyc->IAP_D08      = $file08;        
        $nuevaasyc->NUM08_ID     = $request->num08_id;
        $nuevaasyc->PER08_ID     = $request->per08_id;        
        $nuevaasyc->IAP_EDO08    = $request->iap_edo08;                 

        $nuevaasyc->IP            = $ip;
        $nuevaasyc->LOGIN         = $nombre;         // Usuario ;
        $nuevaasyc->save();

        if($nuevaasyc->save() == true){
            toastr()->success('Información de asistencia social y contable registrada correctamente.',' dada de alta!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaAsyc');
            //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','estructura','id_estructura','rango','preguntas','apartados'));
        }else{
            toastr()->error('Error inesperado al registrar información de asistencia social y contable. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //return back();
            //return redirect()->route('nuevaAsyc');
        }

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         9;
        $xfuncion_id  =      9003;
        $xtrx_id      =       160;    //Registro de información de asistencia y contable

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $iap_folio])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $iap_folio;      // Folio    
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
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $iap_folio])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************               
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $iap_folio])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/ 
        return redirect()->route('verAsyc');
    }


    /****************** Editar registro de aportación monetaria **********/
    public function actionEditarAsyc($id){
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

        $regper       = regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();
        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')
                        ->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->orderBy('PERIODO_ID','asc')
                        ->get();        
       $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();                                
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->orderBy('IAP_DESC','asc')
                        ->get();

        $regasyc = regAsistContableModel::select('IAP_FOLIO','IAP_ID','PERIODO_ID',
            'IAP_D01','PER01_ID','NUM01_ID','IAP_EDO01','IAP_D02','PER02_ID','NUM02_ID','IAP_EDO02',
            'IAP_D03','PER03_ID','NUM03_ID','IAP_EDO03','IAP_D04','PER04_ID','NUM04_ID','IAP_EDO04',
            'IAP_D05','PER05_ID','NUM05_ID','IAP_EDO05','IAP_D06','PER06_ID','NUM06_ID','IAP_EDO06',
            'IAP_D07','PER07_ID','NUM07_ID','IAP_EDO07','IAP_D08','PER08_ID','NUM08_ID','IAP_EDO08',
            'IAP_D09','PER09_ID','NUM09_ID','IAP_EDO09','IAP_D10','PER10_ID','NUM10_ID','IAP_EDO10',
            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                    ->where('IAP_FOLIO', $id)
                    ->first();
        if($regasyc->count() <= 0){
            toastr()->error('No existe registro de información de asistencia social y contable.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaAsyc');
        }
        return view('sicinar.asyc.editarAsyc',compact('nombre','usuario','estructura','id_estructura','regiap','regasyc','regper','regnumeros', 'regperiodos','regperiodicidad'));

    }

    
    public function actionActualizarAsyc(asycRequest $request, $id){
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
        $xfuncion_id  =      9003;
        $xtrx_id      =       161;    //Actualizar aportacion monetaria        

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
        $regasyc = regAsistContableModel::where('IAP_FOLIO',$id);
        if($regasyc->count() <= 0)
            toastr()->error('No existe llave de datos de asistencia social y contable.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            $name01 =null;
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d01 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('iap_d01')){
                echo "Escribió en el campo de texto 3: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
                $name01 = $id.'_'.$request->file('iap_d01')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d01')->move(public_path().'/images/', $name01);

                // ************* Actualizamos registro **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                
                            'IAP_D01'       => $name01,                  
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                            
                            'PER01_ID'      => $request->per01_id,
                            'NUM01_ID'      => $request->num01_id,                
                            'IAP_EDO01'     => $request->iap_edo01,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                            ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro sin foto **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([  
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                            
                            'PER01_ID'      => $request->per01_id,
                            'NUM01_ID'      => $request->num01_id,                
                            'IAP_EDO01'     => $request->iap_edo01,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                             ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }    
            //$regasyc = regAsistContableModel::select('IAP_D01')->where('IAP_FOLIO', $id)
            //$regasyc = regAsistContableModel::select('IAP_ID','IAP_D01')->where('IAP_FOLIO','=', $id)
            //$regasyc = regAsistContableModel::select('IAP_ID','IAP_D01')->where(['IAP_FOLIO' => $id])
            //           ->first();
            //if($regasyc->count() <= 0) 
            //  toastr()->error('No existe información de asistencia social y contable 3.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
            //else 
            //   $name01 = $regasyc->IAP_D01; 
        }
        return redirect()->route('verAsyc');
        
    }


    /****************** Editar inf. de asistencia social y contable **********/
    public function actionEditarAsyc2($id){
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


        $regper       = regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();
        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')
                        ->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->orderBy('PERIODO_ID','asc')
                        ->get();        
       $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();                                
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->orderBy('IAP_DESC','asc')
                        ->get();

        $regasyc = regAsistContableModel::select('IAP_FOLIO','IAP_ID','PERIODO_ID',
            'IAP_D01','PER01_ID','NUM01_ID','IAP_EDO01','IAP_D02','PER02_ID','NUM02_ID','IAP_EDO02',
            'IAP_D03','PER03_ID','NUM03_ID','IAP_EDO03','IAP_D04','PER04_ID','NUM04_ID','IAP_EDO04',
            'IAP_D05','PER05_ID','NUM05_ID','IAP_EDO05','IAP_D06','PER06_ID','NUM06_ID','IAP_EDO06',
            'IAP_D07','PER07_ID','NUM07_ID','IAP_EDO07','IAP_D08','PER08_ID','NUM08_ID','IAP_EDO08',
            'IAP_D09','PER09_ID','NUM09_ID','IAP_EDO09','IAP_D10','PER10_ID','NUM10_ID','IAP_EDO10',
            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                    ->where('IAP_FOLIO', $id)
                    ->first();
        if($regasyc->count() <= 0){
            toastr()->error('No existe registro de información de asistencia social y contable.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaAsyc');
        }
        return view('sicinar.asyc.editarAsyc2',compact('nombre','usuario','estructura','id_estructura','regiap','regasyc','regper','regnumeros', 'regperiodos','regperiodicidad'));
    }

    public function actionActualizarAsyc2(asycRequest $request, $id){
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
        $xfuncion_id  =      9003;
        $xtrx_id      =       161;    //Actualizar aportacion monetaria        

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
        $regasyc = regAsistContableModel::where('IAP_FOLIO',$id);
        if($regasyc->count() <= 0)
            toastr()->error('No existe llave de datos de asistencia social y contable.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            $name02 =null;
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d01 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('iap_d02')){
                echo "Escribió en el campo de texto 3: " .'-'. $request->iap_d02 .'-'. "<br><br>"; 
                $name02 = $id.'_'.$request->file('iap_d02')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d02')->move(public_path().'/images/', $name02);

                // ************* Actualizamos registro **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                 
                            'IAP_D02'       => $name02,                  
                            'PER02_ID'      => $request->per02_id,
                            'NUM02_ID'      => $request->num02_id,                
                            'IAP_EDO02'     => $request->iap_edo02,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                            ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro sin foto **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                 
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                 
                            'PER02_ID'      => $request->per02_id,
                            'NUM02_ID'      => $request->num02_id,                
                            'IAP_EDO02'     => $request->iap_edo02,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                             ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }    
            //$regasyc = regAsistContableModel::select('IAP_D01')->where('IAP_FOLIO', $id)
            //$regasyc = regAsistContableModel::select('IAP_ID','IAP_D01')->where('IAP_FOLIO','=', $id)
            //$regasyc = regAsistContableModel::select('IAP_ID','IAP_D01')->where(['IAP_FOLIO' => $id])
            //           ->first();
            //if($regasyc->count() <= 0) 
            //  toastr()->error('No existe información de asistencia social y contable 3.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
            //else 
            //   $name01 = $regasyc->IAP_D01; 
        }
        return redirect()->route('verAsyc');
        
    }    

    /****************** Editar inf. de asistencia social y contable **********/
    public function actionEditarAsyc3($id){
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


        $regper       = regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();
        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')
                        ->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->orderBy('PERIODO_ID','asc')
                        ->get();        
       $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();                                
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->orderBy('IAP_DESC','asc')
                        ->get();

        $regasyc = regAsistContableModel::select('IAP_FOLIO','IAP_ID','PERIODO_ID',
            'IAP_D01','PER01_ID','NUM01_ID','IAP_EDO01','IAP_D02','PER02_ID','NUM02_ID','IAP_EDO02',
            'IAP_D03','PER03_ID','NUM03_ID','IAP_EDO03','IAP_D04','PER04_ID','NUM04_ID','IAP_EDO04',
            'IAP_D05','PER05_ID','NUM05_ID','IAP_EDO05','IAP_D06','PER06_ID','NUM06_ID','IAP_EDO06',
            'IAP_D07','PER07_ID','NUM07_ID','IAP_EDO07','IAP_D08','PER08_ID','NUM08_ID','IAP_EDO08',
            'IAP_D09','PER09_ID','NUM09_ID','IAP_EDO09','IAP_D10','PER10_ID','NUM10_ID','IAP_EDO10',
            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                    ->where('IAP_FOLIO', $id)
                    ->first();
        if($regasyc->count() <= 0){
            toastr()->error('No existe registro de información de asistencia social y contable.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaAsyc');
        }
        return view('sicinar.asyc.editarAsyc3',compact('nombre','usuario','estructura','id_estructura','regiap','regasyc','regper','regnumeros', 'regperiodos','regperiodicidad'));
    }


    public function actionActualizarAsyc3(asycRequest $request, $id){
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
        $xfuncion_id  =      9003;
        $xtrx_id      =       161;    //Actualizar aportacion monetaria        

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
        $regasyc = regAsistContableModel::where('IAP_FOLIO',$id);
        if($regasyc->count() <= 0)
            toastr()->error('No existe llave de datos de asistencia social y contable.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            $name03 =null;
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d01 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('iap_d03')){
                echo "Escribió en el campo de texto 3: " .'-'. $request->iap_d03 .'-'. "<br><br>"; 
                $name03 = $id.'_'.$request->file('iap_d03')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d03')->move(public_path().'/images/', $name03);

                // ************* Actualizamos registro **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                 
                            'IAP_D03'       => $name03,                  
                            'PER03_ID'      => $request->per03_id,
                            'NUM03_ID'      => $request->num03_id,                
                            'IAP_EDO03'     => $request->iap_edo03,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                            ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro sin foto **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                 
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                 
                            'PER03_ID'      => $request->per03_id,
                            'NUM03_ID'      => $request->num03_id,                
                            'IAP_EDO03'     => $request->iap_edo03,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                             ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }    
        }
        return redirect()->route('verAsyc');    
    }    

    /****************** Editar inf. de asistencia social y contable **********/
    public function actionEditarAsyc4($id){
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


        $regper       = regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();
        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')
                        ->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->orderBy('PERIODO_ID','asc')
                        ->get();        
       $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();                                
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->orderBy('IAP_DESC','asc')
                        ->get();

        $regasyc = regAsistContableModel::select('IAP_FOLIO','IAP_ID','PERIODO_ID',
            'IAP_D01','PER01_ID','NUM01_ID','IAP_EDO01','IAP_D02','PER02_ID','NUM02_ID','IAP_EDO02',
            'IAP_D03','PER03_ID','NUM03_ID','IAP_EDO03','IAP_D04','PER04_ID','NUM04_ID','IAP_EDO04',
            'IAP_D05','PER05_ID','NUM05_ID','IAP_EDO05','IAP_D06','PER06_ID','NUM06_ID','IAP_EDO06',
            'IAP_D07','PER07_ID','NUM07_ID','IAP_EDO07','IAP_D08','PER08_ID','NUM08_ID','IAP_EDO08',
            'IAP_D09','PER09_ID','NUM09_ID','IAP_EDO09','IAP_D10','PER10_ID','NUM10_ID','IAP_EDO10',
            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                    ->where('IAP_FOLIO', $id)
                    ->first();
        if($regasyc->count() <= 0){
            toastr()->error('No existe registro de información de asistencia social y contable.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaAsyc');
        }
        return view('sicinar.asyc.editarAsyc4',compact('nombre','usuario','estructura','id_estructura','regiap','regasyc','regper','regnumeros', 'regperiodos','regperiodicidad'));
    }


    public function actionActualizarAsyc4(asycRequest $request, $id){
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
        $xfuncion_id  =      9003;
        $xtrx_id      =       161;    //Actualizar aportacion monetaria        

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
        $regasyc = regAsistContableModel::where('IAP_FOLIO',$id);
        if($regasyc->count() <= 0)
            toastr()->error('No existe llave de datos de asistencia social y contable.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            $name04 =null;
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d01 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('iap_d04')){
                echo "Escribió en el campo de texto 4: " .'-'. $request->iap_d04 .'-'. "<br><br>"; 
                $name04 = $id.'_'.$request->file('iap_d04')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d04')->move(public_path().'/images/', $name04);

                // ************* Actualizamos registro **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                 
                            'IAP_D04'       => $name04,                  
                            'PER04_ID'      => $request->per04_id,
                            'NUM04_ID'      => $request->num04_id,                
                            'IAP_EDO04'     => $request->iap_edo04,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                            ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro sin foto **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                 
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                 
                            'PER04_ID'      => $request->per04_id,
                            'NUM04_ID'      => $request->num04_id,                
                            'IAP_EDO04'     => $request->iap_edo04,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                             ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }    
        }
        return redirect()->route('verAsyc');    
    }    

    /****************** Editar inf. de asistencia social y contable **********/
    public function actionEditarAsyc5($id){
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


        $regper       = regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();
        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')
                        ->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->orderBy('PERIODO_ID','asc')
                        ->get();        
       $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();                                
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->orderBy('IAP_DESC','asc')
                        ->get();

        $regasyc = regAsistContableModel::select('IAP_FOLIO','IAP_ID','PERIODO_ID',
            'IAP_D01','PER01_ID','NUM01_ID','IAP_EDO01','IAP_D02','PER02_ID','NUM02_ID','IAP_EDO02',
            'IAP_D03','PER03_ID','NUM03_ID','IAP_EDO03','IAP_D04','PER04_ID','NUM04_ID','IAP_EDO04',
            'IAP_D05','PER05_ID','NUM05_ID','IAP_EDO05','IAP_D06','PER06_ID','NUM06_ID','IAP_EDO06',
            'IAP_D07','PER07_ID','NUM07_ID','IAP_EDO07','IAP_D08','PER08_ID','NUM08_ID','IAP_EDO08',
            'IAP_D09','PER09_ID','NUM09_ID','IAP_EDO09','IAP_D10','PER10_ID','NUM10_ID','IAP_EDO10',
            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                    ->where('IAP_FOLIO', $id)
                    ->first();
        if($regasyc->count() <= 0){
            toastr()->error('No existe registro de información de asistencia social y contable.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaAsyc');
        }
        return view('sicinar.asyc.editarAsyc5',compact('nombre','usuario','estructura','id_estructura','regiap','regasyc','regper','regnumeros', 'regperiodos','regperiodicidad'));
    }

    public function actionActualizarAsyc5(asycRequest $request, $id){
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
        $xfuncion_id  =      9003;
        $xtrx_id      =       161;    //Actualizar aportacion monetaria        

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
        $regasyc = regAsistContableModel::where('IAP_FOLIO',$id);
        if($regasyc->count() <= 0)
            toastr()->error('No existe llave de datos de asistencia social y contable.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            $name05 =null;
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d01 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('iap_d05')){
                echo "Escribió en el campo de texto 5: " .'-'. $request->iap_d05 .'-'. "<br><br>"; 
                $name05 = $id.'_'.$request->file('iap_d05')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d05')->move(public_path().'/images/', $name05);

                // ************* Actualizamos registro **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                 
                            'IAP_D05'       => $name05,                  
                            'PER05_ID'      => $request->per05_id,
                            'NUM05_ID'      => $request->num05_id,                
                            'IAP_EDO05'     => $request->iap_edo05,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                            ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro sin foto **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                 
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                 
                            'PER05_ID'      => $request->per05_id,
                            'NUM05_ID'      => $request->num05_id,                
                            'IAP_EDO05'     => $request->iap_edo05,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                             ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }    
        }
        return redirect()->route('verAsyc');
    }    

    /****************** Editar inf. de asistencia social y contable **********/
    public function actionEditarAsyc6($id){
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


        $regper       = regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();
        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')
                        ->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->orderBy('PERIODO_ID','asc')
                        ->get();        
       $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();                                
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->orderBy('IAP_DESC','asc')
                        ->get();

        $regasyc = regAsistContableModel::select('IAP_FOLIO','IAP_ID','PERIODO_ID',
            'IAP_D01','PER01_ID','NUM01_ID','IAP_EDO01','IAP_D02','PER02_ID','NUM02_ID','IAP_EDO02',
            'IAP_D03','PER03_ID','NUM03_ID','IAP_EDO03','IAP_D04','PER04_ID','NUM04_ID','IAP_EDO04',
            'IAP_D05','PER05_ID','NUM05_ID','IAP_EDO05','IAP_D06','PER06_ID','NUM06_ID','IAP_EDO06',
            'IAP_D07','PER07_ID','NUM07_ID','IAP_EDO07','IAP_D08','PER08_ID','NUM08_ID','IAP_EDO08',
            'IAP_D09','PER09_ID','NUM09_ID','IAP_EDO09','IAP_D10','PER10_ID','NUM10_ID','IAP_EDO10',
            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                    ->where('IAP_FOLIO', $id)
                    ->first();
        if($regasyc->count() <= 0){
            toastr()->error('No existe registro de información de asistencia social y contable.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaAsyc');
        }
        return view('sicinar.asyc.editarAsyc6',compact('nombre','usuario','estructura','id_estructura','regiap','regasyc','regper','regnumeros', 'regperiodos','regperiodicidad'));
    }

    public function actionActualizarAsyc6(asycRequest $request, $id){
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
        $xfuncion_id  =      9003;
        $xtrx_id      =       161;    //Actualizar aportacion monetaria        

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
        $regasyc = regAsistContableModel::where('IAP_FOLIO',$id);
        if($regasyc->count() <= 0)
            toastr()->error('No existe llave de datos de asistencia social y contable.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            $name06 =null;
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d01 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('iap_d06')){
                echo "Escribió en el campo de texto 6: " .'-'. $request->iap_d06 .'-'. "<br><br>"; 
                $name06 = $id.'_'.$request->file('iap_d06')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d06')->move(public_path().'/images/', $name06);

                // ************* Actualizamos registro **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                 
                            'IAP_D06'       => $name06,                  
                            'PER06_ID'      => $request->per06_id,
                            'NUM06_ID'      => $request->num06_id,                
                            'IAP_EDO06'     => $request->iap_edo06,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                            ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro sin foto **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                 
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                 
                            'PER06_ID'      => $request->per06_id,
                            'NUM06_ID'      => $request->num06_id,                
                            'IAP_EDO06'     => $request->iap_edo06,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                             ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }    
        }
        return redirect()->route('verAsyc');
    }    

    /****************** Editar inf. de asistencia social y contable **********/
    public function actionEditarAsyc7($id){
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


        $regper       = regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();
        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')
                        ->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->orderBy('PERIODO_ID','asc')
                        ->get();        
       $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();                                
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->orderBy('IAP_DESC','asc')
                        ->get();

        $regasyc = regAsistContableModel::select('IAP_FOLIO','IAP_ID','PERIODO_ID',
            'IAP_D01','PER01_ID','NUM01_ID','IAP_EDO01','IAP_D02','PER02_ID','NUM02_ID','IAP_EDO02',
            'IAP_D03','PER03_ID','NUM03_ID','IAP_EDO03','IAP_D04','PER04_ID','NUM04_ID','IAP_EDO04',
            'IAP_D05','PER05_ID','NUM05_ID','IAP_EDO05','IAP_D06','PER06_ID','NUM06_ID','IAP_EDO06',
            'IAP_D07','PER07_ID','NUM07_ID','IAP_EDO07','IAP_D08','PER08_ID','NUM08_ID','IAP_EDO08',
            'IAP_D09','PER09_ID','NUM09_ID','IAP_EDO09','IAP_D10','PER10_ID','NUM10_ID','IAP_EDO10',
            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                    ->where('IAP_FOLIO', $id)
                    ->first();
        if($regasyc->count() <= 0){
            toastr()->error('No existe registro de información de asistencia social y contable.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaAsyc');
        }
        return view('sicinar.asyc.editarAsyc7',compact('nombre','usuario','estructura','id_estructura','regiap','regasyc','regper','regnumeros', 'regperiodos','regperiodicidad'));
    }

    public function actionActualizarAsyc7(asycRequest $request, $id){
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
        $xfuncion_id  =      9003;
        $xtrx_id      =       161;    //Actualizar aportacion monetaria        

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
        $regasyc = regAsistContableModel::where('IAP_FOLIO',$id);
        if($regasyc->count() <= 0)
            toastr()->error('No existe llave de datos de asistencia social y contable.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            $name07 =null;
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d01 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('iap_d07')){
                echo "Escribió en el campo de texto 7: " .'-'. $request->iap_d07 .'-'. "<br><br>"; 
                $name07 = $id.'_'.$request->file('iap_d07')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d07')->move(public_path().'/images/', $name07);

                // ************* Actualizamos registro **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                 
                            'IAP_D07'       => $name07,                  
                            'PER07_ID'      => $request->per07_id,
                            'NUM07_ID'      => $request->num07_id,                
                            'IAP_EDO07'     => $request->iap_edo07,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                            ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro sin foto **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                 
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                 
                            'PER07_ID'      => $request->per07_id,
                            'NUM07_ID'      => $request->num07_id,                
                            'IAP_EDO07'     => $request->iap_edo07,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                             ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }    
        }
        return redirect()->route('verAsyc');
    }    

    /****************** Editar inf. de asistencia social y contable **********/
    public function actionEditarAsyc8($id){
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


        $regper       = regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();
        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')
                        ->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->orderBy('PERIODO_ID','asc')
                        ->get();        
       $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')
                        ->orderBy('PER_ID','asc')
                        ->get();                                
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->orderBy('IAP_DESC','asc')
                        ->get();

        $regasyc = regAsistContableModel::select('IAP_FOLIO','IAP_ID','PERIODO_ID',
            'IAP_D01','PER01_ID','NUM01_ID','IAP_EDO01','IAP_D02','PER02_ID','NUM02_ID','IAP_EDO02',
            'IAP_D03','PER03_ID','NUM03_ID','IAP_EDO03','IAP_D04','PER04_ID','NUM04_ID','IAP_EDO04',
            'IAP_D05','PER05_ID','NUM05_ID','IAP_EDO05','IAP_D06','PER06_ID','NUM06_ID','IAP_EDO06',
            'IAP_D07','PER07_ID','NUM07_ID','IAP_EDO07','IAP_D08','PER08_ID','NUM08_ID','IAP_EDO08',
            'IAP_D09','PER09_ID','NUM09_ID','IAP_EDO09','IAP_D10','PER10_ID','NUM10_ID','IAP_EDO10',
            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                    ->where('IAP_FOLIO', $id)
                    ->first();
        if($regasyc->count() <= 0){
            toastr()->error('No existe registro de información de asistencia social y contable.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaAsyc');
        }
        return view('sicinar.asyc.editarAsyc8',compact('nombre','usuario','estructura','id_estructura','regiap','regasyc','regper','regnumeros', 'regperiodos','regperiodicidad'));
    }

    public function actionActualizarAsyc8(asycRequest $request, $id){
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
        $xfuncion_id  =      9003;
        $xtrx_id      =       161;    //Actualizar aportacion monetaria        

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
        $regasyc = regAsistContableModel::where('IAP_FOLIO',$id);
        if($regasyc->count() <= 0)
            toastr()->error('No existe llave de datos de asistencia social y contable.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            $name08 =null;
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d01 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('iap_d08')){
                echo "Escribió en el campo de texto 8: " .'-'. $request->iap_d08 .'-'. "<br><br>"; 
                $name08 = $id.'_'.$request->file('iap_d08')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d08')->move(public_path().'/images/', $name08);

                // ************* Actualizamos registro **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                 
                            'IAP_D08'       => $name08,                  
                            'PER08_ID'      => $request->per08_id,
                            'NUM08_ID'      => $request->num08_id,                
                            'IAP_EDO08'     => $request->iap_edo08,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                            ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro sin foto **********************************
                $regasyc = regAsistContableModel::where('IAP_FOLIO',$id)        
                            ->update([                 
                            'IAP_ID'        => $request->iap_id,
                            'PERIODO_ID'    => $request->periodo_id,                                 
                            'PER08_ID'      => $request->per08_id,
                            'NUM08_ID'      => $request->num08_id,                
                            'IAP_EDO08'     => $request->iap_edo08,
                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                             ]);
                toastr()->success('Datos de asistencia social y contable actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }    
        }
        return redirect()->route('verAsyc');
    }    

    //***** Borrar registro completo ***********************
    public function actionBorrarAsyc($id){
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
        $xfuncion_id  =      9003;
        $xtrx_id      =       162;     // Cancelación transaccion de asistencia social y contable

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

        /************ Elimina transacción de asistencia social y contable ***************/
        $regasyc = regAsistContableModel::where('IAP_FOLIO',$id);
        if($regasyc->count() <= 0)
            toastr()->error('No existe reg. de trx de asistencia social y contable.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regasyc->delete();
            toastr()->success('Reg. de trx de inf. de asistencia social y contable ha sido eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************* Termina de eliminar  la IAP **********************************/
        
        return redirect()->route('verAsyc');
    }    

}