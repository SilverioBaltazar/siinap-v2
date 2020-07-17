<?php
//**************************************************************/
//* File:       rJuridicosController.php
//* Proyecto:   Sistema SIINAP.V2 JAPEM
//¨Función:     Clases para el modulo de asistencia social y Contable
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: diciembre 2019
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\iapsjuridicoRequest;
use App\regIapModel;
use App\regBitacoraModel;
use App\regPfiscalesModel;
use App\regIapJuridicoModel;
use App\regPerModel;
use App\regNumerosModel;
use App\regFormatosModel;

// Exportar a excel 
use Maatwebsite\Excel\Facades\Excel;

// Exportar a pdf
use PDF;
//use Options;

class rJuridicosController extends Controller
{

    //******** Mostrar requisitos juridicos *****//
    public function actionVerIapj(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regperiodicidad= regPerModel::select('PER_ID','PER_DESC')->orderBy('PER_ID','asc')
                        ->get();                          
        $regnumeros   = regNumerosModel::select('NUM_ID','NUM_DESC')->get();
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->get();        
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->get();
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){
            $regjuridico  = regIapJuridicoModel::select('IAP_ID','PERIODO_ID', 
                            'DOC_ID12','FORMATO_ID12','IAP_D12','PER_ID12','NUM_ID12','IAP_EDO12',
                            'DOC_ID13','FORMATO_ID13','IAP_D13','PER_ID13','NUM_ID13','IAP_EDO13',
                            'DOC_ID14','FORMATO_ID14','IAP_D14','PER_ID14','NUM_ID14','IAP_EDO14',
                            'DOC_ID15','FORMATO_ID15','IAP_D15','PER_ID15','NUM_ID15','IAP_EDO15',
                            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                            ->orderBy('IAP_ID','ASC')
                            ->orderBy('PERIODO_ID','ASC')
                            ->paginate(30);
        }else{
            $regjuridico  = regIapJuridicoModel::select('IAP_ID','PERIODO_ID',
                            'DOC_ID12','FORMATO_ID12','IAP_D12','PER_ID12','NUM_ID12','IAP_EDO12',
                            'DOC_ID13','FORMATO_ID13','IAP_D13','PER_ID13','NUM_ID13','IAP_EDO13',
                            'DOC_ID14','FORMATO_ID14','IAP_D14','PER_ID14','NUM_ID14','IAP_EDO14',
                            'DOC_ID15','FORMATO_ID15','IAP_D15','PER_ID15','NUM_ID15','IAP_EDO15',
                            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                            ->where('IAP_ID',$arbol_id)
                            ->paginate(30);            
        }
        if($regjuridico->count() <= 0){
            toastr()->error('No existen datos juridicos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevajuridica');
        }
        return view('sicinar.requisitos.verIapJuridico',compact('nombre','usuario','regiap','regperiodicidad','regnumeros', 'regperiodos','regjuridico','regformatos'));
    }


    public function actionNuevaIapj(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();   
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();
        if(session()->get('rango') !== '0'){
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','asc')
                        ->get();
        }else{
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->where('IAP_ID',$arbol_id)
                        ->get();            
        }
        $regjuridico  = regIapJuridicoModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID12','FORMATO_ID12','IAP_D12','PER_ID12','NUM_ID12','IAP_EDO12',
                        'DOC_ID13','FORMATO_ID13','IAP_D13','PER_ID13','NUM_ID13','IAP_EDO13',
                        'DOC_ID14','FORMATO_ID14','IAP_D14','PER_ID14','NUM_ID14','IAP_EDO14',
                        'DOC_ID15','FORMATO_ID15','IAP_D15','PER_ID15','NUM_ID15','IAP_EDO15',                        
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('IAP_ID','ASC')
                        ->get();        
        //dd($unidades);
        return view('sicinar.requisitos.nuevaIapj',compact('regper','regnumeros','regiap','regperiodos','regperiodicidad','nombre','usuario','regjuridico','regformatos'));
    }

    public function actionAltaNuevaIapj(Request $request){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

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

        // *************** Validar triada ***********************************/
        /************************** Registro *****************************/ 
        $regjuridico  = regIapJuridicoModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID12','FORMATO_ID12','IAP_D12','PER_ID12','NUM_ID12','IAP_EDO12',
                        'DOC_ID13','FORMATO_ID13','IAP_D13','PER_ID13','NUM_ID13','IAP_EDO13',
                        'DOC_ID14','FORMATO_ID14','IAP_D14','PER_ID14','NUM_ID14','IAP_EDO14',
                        'DOC_ID15','FORMATO_ID15','IAP_D15','PER_ID15','NUM_ID15','IAP_EDO15',                        
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $request->periodo_id,'IAP_ID' => $request->iap_id])
                        ->get();
        if($regjuridico->count() <= 0 && !empty($request->iap_id)){
            //********** Registrar la alta *****************************/
            //$iap_folio = regIapJuridicoModel::max('IAP_FOLIO');
            //$iap_folio = $iap_folio+1;
            $nuevajuridica = new regIapJuridicoModel();
 
            $file12 =null;
            if(isset($request->iap_d12)){
                if(!empty($request->iap_d12)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d12')){
                        $file12=$request->iap_id.'_'.$request->file('iap_d12')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d12')->move(public_path().'/images/', $file12);
                    }
                }
            }
            $file13 =null;
            if(isset($request->iap_d13)){
                if(!empty($request->iap_d13)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d13')){
                        $file13=$request->iap_id.'_'.$request->file('iap_d13')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d13')->move(public_path().'/images/', $file13);
                    }
                }
            }
            $file14 =null;
            if(isset($request->iap_d14)){
                if(!empty($request->iap_d14)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d14')){
                        $file14=$request->iap_id.'_'.$request->file('iap_d14')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d14')->move(public_path().'/images/', $file14);
                    }
                }
            }
            $file15 =null;
            if(isset($request->iap_d15)){
                if(!empty($request->iap_d15)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d15')){
                        $file15=$request->iap_id.'_'.$request->file('iap_d15')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d15')->move(public_path().'/images/', $file15);
                    }
                }
            }            

            $nuevajuridica->PERIODO_ID   = $request->periodo_id;
            $nuevajuridica->IAP_ID       = $request->iap_id;        

            $nuevajuridica->DOC_ID12     = $request->doc_id12;
            $nuevajuridica->FORMATO_ID12 = $request->formato_id12;
            $nuevajuridica->IAP_D12      = $file12;        
            $nuevajuridica->NUM_ID12     = $request->num_id12;
            $nuevajuridica->PER_ID12     = $request->per_id12;        
            $nuevajuridica->IAP_EDO12    = $request->iap_edo12;        

            $nuevajuridica->DOC_ID13     = $request->doc_id13;
            $nuevajuridica->FORMATO_ID13 = $request->formato_id13;
            $nuevajuridica->IAP_D13      = $file13;        
            $nuevajuridica->NUM_ID13     = $request->num_id13;
            $nuevajuridica->PER_ID13     = $request->per_id13;        
            $nuevajuridica->IAP_EDO13    = $request->iap_edo13; 

            $nuevajuridica->DOC_ID14     = $request->doc_id14;
            $nuevajuridica->FORMATO_ID14 = $request->formato_id14;
            $nuevajuridica->IAP_D14      = $file14;        
            $nuevajuridica->NUM_ID14     = $request->num_id14;
            $nuevajuridica->PER_ID14     = $request->per_id14;        
            $nuevajuridica->IAP_EDO14    = $request->iap_edo14;

            $nuevajuridica->DOC_ID15     = $request->doc_id15;
            $nuevajuridica->FORMATO_ID15 = $request->formato_id15;
            $nuevajuridica->IAP_D15      = $file15;        
            $nuevajuridica->NUM_ID15     = $request->num_id15;
            $nuevajuridica->PER_ID15     = $request->per_id15;        
            $nuevajuridica->IAP_EDO15    = $request->iap_edo15;                     

            $nuevajuridica->IP           = $ip;
            $nuevajuridica->LOGIN        = $nombre;         // Usuario ;
            $nuevajuridica->save();

            if($nuevajuridica->save() == true){
                toastr()->success('Requisitos jurídicos registrados.','ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3002;
                $xtrx_id      =       150;    //alta
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                                           'FUNCION_ID','TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN',
                                           'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $request->iap_id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $request->iap_id;      // Folio    
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
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                          'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                          'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                                          'FOLIO' => $request->iap_id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************               
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                            'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,
                                            'FOLIO' => $request->iap_id])
                                   ->update([
                                             'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'     => $regbitacora->IP       = $ip,
                                             'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 
                
                //return redirect()->route('nuevajuridica');
                //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','rango','preguntas','apartados'));
            }else{
                toastr()->error('Error inesperado al registrar requisitos jurídicos. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                //return back();
                //return redirect()->route('nuevajuridica');
            }   //******************** Termina la alta ************************/ 

        }else{
            toastr()->error('Ya existe IAP con requisitos jurídicos.','Por favor editar, lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevajuridica');
        }   // Termian If de busqueda ****************

        return redirect()->route('verIapj');
    }


    /****************** Editar registro  **********/
    public function actionEditarIapj($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();                                
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')
                        ->get();
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','asc')
                        ->get();
        $regjuridico  = regIapJuridicoModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID12','FORMATO_ID12','IAP_D12','PER_ID12','NUM_ID12','IAP_EDO12',
                        'DOC_ID13','FORMATO_ID13','IAP_D13','PER_ID13','NUM_ID13','IAP_EDO13',
                        'DOC_ID14','FORMATO_ID14','IAP_D14','PER_ID14','NUM_ID14','IAP_EDO14',
                        'DOC_ID15','FORMATO_ID15','IAP_D15','PER_ID15','NUM_ID15','IAP_EDO15',                        
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID', $id)
                        ->first();
        if($regjuridico->count() <= 0){
            toastr()->error('No existe requisito jurídico.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevajuridica');
        }
        return view('sicinar.requisitos.editarIapj',compact('nombre','usuario','regiap','regjuridico','regnumeros', 'regperiodos','regperiodicidad','regformatos'));

    }
    
    public function actionActualizarIapj(iapsjuridicoRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');        

        // **************** actualizar ******************************
        $regjuridico = regIapJuridicoModel::where('IAP_ID',$id);
        if($regjuridico->count() <= 0)
            toastr()->error('No existe requisitos jurídico.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //****************** Actualizar **************************/
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d12 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d12 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name12 =null;        
            if($request->hasFile('iap_d12')){
                echo "Escribió en el campo de texto 12: " .'-'. $request->iap_d12 .'-'. "<br><br>"; 
                $name12 = $id.'_'.$request->file('iap_d12')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d12')->move(public_path().'/images/', $name12);
            }
            $name13 =null;        
            if($request->hasFile('iap_d13')){
                echo "Escribió en el campo de texto 13: " .'-'. $request->iap_d13 .'-'. "<br><br>"; 
                $name13 = $id.'_'.$request->file('iap_d13')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d13')->move(public_path().'/images/', $name13);
            }            
            $name14 =null;        
            if($request->hasFile('iap_d14')){
                echo "Escribió en el campo de texto 14: " .'-'. $request->iap_d14 .'-'. "<br><br>"; 
                $name14 = $id.'_'.$request->file('iap_d14')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d14')->move(public_path().'/images/', $name14);
            }  
            $name15 =null;        
            if($request->hasFile('iap_d15')){
                echo "Escribió en el campo de texto 15: " .'-'. $request->iap_d15 .'-'. "<br><br>"; 
                $name15 = $id.'_'.$request->file('iap_d15')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d15')->move(public_path().'/images/', $name15);
            }                        

            // ************* Actualizamos registro **********************************
            $regjuridico = regIapJuridicoModel::where('IAP_ID',$id)        
                           ->update([                
                                     'PERIODO_ID'   => $request->periodo_id,
                                    
                                     'DOC_ID12'     => $request->doc_id12,
                                     'FORMATO_ID12' => $request->formato_id12,                            
                                     //'IAP_D12'      => $name12,                                                       
                                     'PER_ID12'     => $request->per_id12,
                                     'NUM_ID12'     => $request->num_id12,                
                                     'IAP_EDO12'    => $request->iap_edo12,
                                    
                                     'DOC_ID13'     => $request->doc_id13,
                                     'FORMATO_ID13' => $request->formato_id13,                                          
                                     //'IAP_D13'      => $name13,              
                                     'PER_ID13'     => $request->per_id13,
                                     'NUM_ID13'     => $request->num_id13,                
                                     'IAP_EDO13'    => $request->iap_edo13,
                                     
                                     'DOC_ID14'     => $request->doc_id14,
                                     'FORMATO_ID14' => $request->formato_id14, 
                                     //'IAP_D14'      => $name14,        
                                     'PER_ID14'     => $request->per_id14,
                                     'NUM_ID14'     => $request->num_id14,                
                                     'IAP_EDO14'    => $request->iap_edo14,

                                     'DOC_ID15'     => $request->doc_id15,
                                     'FORMATO_ID15' => $request->formato_id15, 
                                     //'IAP_D15'      => $name15,        
                                     'PER_ID15'     => $request->per_id15,
                                     'NUM_ID15'     => $request->num_id15,                
                                     'IAP_EDO15'    => $request->iap_edo15,                                     

                                     'IAP_STATUS'   => $request->iap_status,
                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/
                                    ]);
            toastr()->success('Requisitos jurídicos actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       151;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                                    'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M',
                                                    'IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
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
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->update([
                                          'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                          'IP_M'    => $regbitacora->IP       = $ip,
                                          'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                          'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina actualizar ***********************************/
        return redirect()->route('verIapj');
        
    }


    /****************** Editar inf. juridica **********/
    public function actionEditarIapj12($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get(); 
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                               
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','asc')
                        ->get();
        $regjuridico  = regIapJuridicoModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID12','FORMATO_ID12','IAP_D12','PER_ID12','NUM_ID12','IAP_EDO12',
                        'DOC_ID13','FORMATO_ID13','IAP_D13','PER_ID13','NUM_ID13','IAP_EDO13',
                        'DOC_ID14','FORMATO_ID14','IAP_D14','PER_ID14','NUM_ID14','IAP_EDO14',
                        'DOC_ID15','FORMATO_ID15','IAP_D15','PER_ID15','NUM_ID15','IAP_EDO15',
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID', $id)
                        ->first();
        if($regjuridico->count() <= 0){
            toastr()->error('No existe información jurídica.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevajuridica');
        }
        return view('sicinar.requisitos.editarIapj12',compact('nombre','usuario','regiap','regjuridico','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarIapj12(iapsjuridicoRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');        

        // **************** actualizar ******************************
        $regjuridico = regIapJuridicoModel::where('IAP_ID',$id);
        if($regjuridico->count() <= 0)
            toastr()->error('No existe archivo PDF12.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d12 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d12 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name12 =null;
            if($request->hasFile('iap_d12')){
                echo "Escribió en el campo de texto 12: " .'-'. $request->iap_d12 .'-'. "<br><br>"; 
                $name12 = $id.'_'.$request->file('iap_d12')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d12')->move(public_path().'/images/', $name12);

                // ************* Actualizamos registro **********************************
                $regjuridico = regIapJuridicoModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID12'    => $request->doc_id12,
                                     'FORMATO_ID12'=> $request->formato_id12,             
                                     'IAP_D12'     => $name12,                  
                                     'PER_ID12'    => $request->per_id12,
                                     'NUM_ID12'    => $request->num_id12,                
                                     'IAP_EDO12'   => $request->iap_edo12,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('archivo jurídico 12 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regjuridico = regIapJuridicoModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID12'    => $request->doc_id12,
                                     'FORMATO_ID12'=> $request->formato_id12,             
                                     //'IAP_D12'    => $name12,                  
                                     'PER_ID12'    => $request->per_id12,
                                     'NUM_ID12'    => $request->num_id12,                
                                     'IAP_EDO12'   => $request->iap_edo12,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo jurídico 12 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       151;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
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
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************                    
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id,
                                           'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 
                                           'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M' => $regbitacora->IP           = $ip,
                                            'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                            'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
            
        }       /************ Termina de actualizar ********************************/

        return redirect()->route('verIapj');
        
    }    

    /****************** Editar inf. juridica **********/
    public function actionEditarIapj13($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                           
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','asc')
                        ->get();
        $regjuridico = regIapJuridicoModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID12','FORMATO_ID12','IAP_D12','PER_ID12','NUM_ID12','IAP_EDO12',
                        'DOC_ID13','FORMATO_ID13','IAP_D13','PER_ID13','NUM_ID13','IAP_EDO13',
                        'DOC_ID14','FORMATO_ID14','IAP_D14','PER_ID14','NUM_ID14','IAP_EDO14',
                        'DOC_ID15','FORMATO_ID15','IAP_D15','PER_ID15','NUM_ID15','IAP_EDO15',
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('IAP_ID', $id)
                       ->first();
        if($regjuridico->count() <= 0){
            toastr()->error('No existe requisito jurídico.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevajuridica');
        }
        return view('sicinar.requisitos.editarIapj13',compact('nombre','usuario','regiap','regjuridico','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarIapj13(iapsjuridicoRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');        

        // **************** actualizar ******************************
        $regjuridico = regIapJuridicoModel::where('IAP_ID',$id);
        if($regjuridico->count() <= 0)
            toastr()->error('No existen requisitos jurídicos.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d12 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d12 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name13 =null;
            if($request->hasFile('iap_d13')){
                echo "Escribió en el campo de texto 13: " .'-'. $request->iap_d13 .'-'. "<br><br>"; 
                $name13 = $id.'_'.$request->file('iap_d13')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d13')->move(public_path().'/images/', $name13);

                // ************* Actualizamos registro **********************************
                $regjuridico = regIapJuridicoModel::where('IAP_ID',$id)        
                               ->update([  
                                      'DOC_ID13'    => $request->doc_id13,
                                      'FORMATO_ID13'=> $request->formato_id13,             
                                      'IAP_D13'     => $name13,           
                                      'PER_ID13'    => $request->per_id13,
                                      'NUM_ID13'    => $request->num_id13,                
                                      'IAP_EDO13'   => $request->iap_edo13,
                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Requisitos juridicos 13 actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regjuridico = regIapJuridicoModel::where('IAP_ID',$id)        
                               ->update([  
                                      'DOC_ID13'    => $request->doc_id13,
                                      'FORMATO_ID13'=> $request->formato_id13,             
                                      //'IAP_D13'   => $name13,           
                                      'PER_ID13'    => $request->per_id13,
                                      'NUM_ID13'    => $request->num_id13,                
                                      'IAP_EDO13'   => $request->iap_edo13,
                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Requisitos juridicos 13 actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       151;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
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
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************                    
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id,
                                           'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 
                                           'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M' => $regbitacora->IP           = $ip,
                                            'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                            'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         

        }       /************ Termina de actualizar ********************************/

        return redirect()->route('verIapj');    
    }    

    /****************** Editar inf. jurídica *********************************/
    public function actionEditarIapj14($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get(); 
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                               
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','asc')
                        ->get();
        $regjuridico  = regIapJuridicoModel::select('IAP_FOLIO','IAP_ID','PERIODO_ID',
                        'DOC_ID12','FORMATO_ID12','IAP_D12','PER_ID12','NUM_ID12','IAP_EDO12',
                        'DOC_ID13','FORMATO_ID13','IAP_D13','PER_ID13','NUM_ID13','IAP_EDO13',
                        'DOC_ID14','FORMATO_ID14','IAP_D14','PER_ID14','NUM_ID14','IAP_EDO14',
                        'DOC_ID15','FORMATO_ID15','IAP_D15','PER_ID15','NUM_ID15','IAP_EDO15',
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID',$id)
                        ->first();
        if($regjuridico->count() <= 0){
            toastr()->error('No existe requisito jurídico.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevajuridica');
        }   /********************* Termina de actualizar ***********************/
        return view('sicinar.requisitos.editarIapj14',compact('nombre','usuario','regiap','regjuridico','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarIapj14(iapsjuridicoRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');        

        // **************** actualizar ******************************
        $regjuridico = regIapJuridicoModel::where('IAP_ID',$id);
        if($regjuridico->count() <= 0)
            toastr()->error('No existe requisito jurídico.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d12 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d12 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name14 =null;
            if($request->hasFile('iap_d14')){
                echo "Escribió en el campo de texto 14: " .'-'. $request->iap_d14 .'-'. "<br><br>"; 
                $name14 = $id.'_'.$request->file('iap_d14')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d14')->move(public_path().'/images/', $name14);

                // ************* Actualizamos registro **********************************
                $regjuridico = regIapJuridicoModel::where('IAP_ID',$id)        
                               ->update([  
                                      'DOC_ID14'    => $request->doc_id14,
                                      'FORMATO_ID14'=> $request->formato_id14,             
                                      'IAP_D14'     => $name14,           
                                      'PER_ID14'    => $request->per_id14,
                                      'NUM_ID14'    => $request->num_id14,                
                                      'IAP_EDO14'   => $request->iap_edo14,
                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Datos juridicos 14 actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regjuridico = regIapJuridicoModel::where('IAP_ID',$id)        
                               ->update([  
                                      'DOC_ID14'    => $request->doc_id14,
                                      'FORMATO_ID14'=> $request->formato_id14,             
                                      //'IAP_D14'   => $name14,           
                                      'PER_ID14'    => $request->per_id14,
                                      'NUM_ID14'    => $request->num_id14,                
                                      'IAP_EDO14'   => $request->iap_edo14,
                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->error('No se actualizo archivo PDF14.','¡Upsss!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       151;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
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
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************                    
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id,
                                           'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 
                                           'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M' => $regbitacora->IP           = $ip,
                                            'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                            'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         

        }       /************ Termina de actualizar ********************************/

        return redirect()->route('verIapj');    
    }    

    /****************** Editar inf. juridica **********/
    public function actionEditarIapj15($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get(); 
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                               
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','asc')
                        ->get();
        $regjuridico  = regIapJuridicoModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID12','FORMATO_ID12','IAP_D12','PER_ID12','NUM_ID12','IAP_EDO12',
                        'DOC_ID13','FORMATO_ID13','IAP_D13','PER_ID13','NUM_ID13','IAP_EDO13',
                        'DOC_ID14','FORMATO_ID14','IAP_D14','PER_ID14','NUM_ID14','IAP_EDO14',
                        'DOC_ID15','FORMATO_ID15','IAP_D15','PER_ID15','NUM_ID15','IAP_EDO15',
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID', $id)
                        ->first();
        if($regjuridico->count() <= 0){
            toastr()->error('No existe información jurídica.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevajuridica');
        }
        return view('sicinar.requisitos.editarIapj15',compact('nombre','usuario','regiap','regjuridico','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarIapj15(iapsjuridicoRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');        

        // **************** actualizar ******************************
        $regjuridico = regIapJuridicoModel::where('IAP_ID',$id);
        if($regjuridico->count() <= 0)
            toastr()->error('No existe archivo PDF15.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d12 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d12 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name15 =null;
            if($request->hasFile('iap_d15')){

                $name15 = $id.'_'.$request->file('iap_d15')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d15')->move(public_path().'/images/', $name15);

                // ************* Actualizamos registro **********************************
                $regjuridico = regIapJuridicoModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID15'    => $request->doc_id15,
                                     'FORMATO_ID15'=> $request->formato_id15,
                                     'IAP_D15'     => $name15,                  
                                     'PER_ID15'    => $request->per_id15,
                                     'NUM_ID15'    => $request->num_id15,                
                                     'IAP_EDO15'   => $request->iap_edo15,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                echo "Escribió en el campo de texto 15-1: " .'-'. $name15 .'-'. "<br><br>";
                toastr()->success('archivo jurídico 15 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                echo "Escribió en el campo de texto 15-2: " .'-'. $request->iap_d15 .'-'. "<br><br>"; 
                $regjuridico = regIapJuridicoModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID15'    => $request->doc_id15,
                                     'FORMATO_ID15'=> $request->formato_id15,             
                                     //'IAP_D15'   => $name15,       
                                     'PER_ID15'    => $request->per_id15,
                                     'NUM_ID15'    => $request->num_id15,                
                                     'IAP_EDO15'   => $request->iap_edo15,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo jurídico 15 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       151;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
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
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************                    
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,
                                           'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                           'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M'    => $regbitacora->IP       = $ip,
                                            'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                            'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/

        return redirect()->route('verIapj');
        
    }    

    //***** Borrar registro completo ***********************
    public function actionBorrarIapj($id){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        /************ Elimina transacción de asistencia social y contable ***************/
        $regjuridico = regIapJuridicoModel::where('IAP_ID',$id);
        if($regjuridico->count() <= 0)
            toastr()->error('No existe requisito jurídico.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regjuridico->delete();
            toastr()->success('Requisito jurídico eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre aboorar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       152;     // Cancelación transaccion 
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                           'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
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
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                             'MES_ID'  => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M' => $regbitacora->IP           = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/    

        }   /************* Termina de eliminar  la IAP **********************************/
        
        return redirect()->route('verIapj');
    }    

}