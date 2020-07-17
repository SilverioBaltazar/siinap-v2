<?php
//**************************************************************/
//* File:       rContablesController.php
//* Proyecto:   Sistema SIINAP.V2 JAPEM
//¨Función:     Clases para el modulo de requisitos Contables
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: diciembre 2019
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\reqcontablesRequest;
use App\regIapModel;
use App\regBitacoraModel;
use App\regPfiscalesModel;
use App\regReqContablesModel;
use App\regPerModel;
use App\regNumerosModel;
use App\regFormatosModel;

// Exportar a excel 
use Maatwebsite\Excel\Facades\Excel;

// Exportar a pdf
use PDF;
//use Options;

class rContablesController extends Controller
{

    //******** Mostrar requisitos contables *****//
    public function actionVerReqc(){
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
            $regcontable  = regReqContablesModel::select('IAP_ID','PERIODO_ID',
                            'DOC_ID6' ,'FORMATO_ID6' ,'IAP_D6' ,'PER_ID6' ,'NUM_ID6' ,'IAP_EDO6',
                            'DOC_ID7' ,'FORMATO_ID7' ,'IAP_D7' ,'PER_ID7' ,'NUM_ID7' ,'IAP_EDO7',
                            'DOC_ID8' ,'FORMATO_ID8' ,'IAP_D8' ,'PER_ID8' ,'NUM_ID8' ,'IAP_EDO8',
                            'DOC_ID9' ,'FORMATO_ID9' ,'IAP_D9' ,'PER_ID9' ,'NUM_ID9' ,'IAP_EDO9',                        
                            'DOC_ID10','FORMATO_ID10','IAP_D10','PER_ID10','NUM_ID10','IAP_EDO10',                        
                            'DOC_ID11','FORMATO_ID11','IAP_D11','PER_ID11','NUM_ID11','IAP_EDO11',
                            'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                            ->orderBy('IAP_ID','ASC')
                            ->paginate(30);
        }else{  
            $regcontable  = regReqContablesModel::select('IAP_ID','PERIODO_ID',
                            'DOC_ID6' ,'FORMATO_ID6' ,'IAP_D6' ,'PER_ID6' ,'NUM_ID6' ,'IAP_EDO6',
                            'DOC_ID7' ,'FORMATO_ID7' ,'IAP_D7' ,'PER_ID7' ,'NUM_ID7' ,'IAP_EDO7',
                            'DOC_ID8' ,'FORMATO_ID8' ,'IAP_D8' ,'PER_ID8' ,'NUM_ID8' ,'IAP_EDO8',
                            'DOC_ID9' ,'FORMATO_ID9' ,'IAP_D9' ,'PER_ID9' ,'NUM_ID9' ,'IAP_EDO9',                        
                            'DOC_ID10','FORMATO_ID10','IAP_D10','PER_ID10','NUM_ID10','IAP_EDO10',                        
                            'DOC_ID11','FORMATO_ID11','IAP_D11','PER_ID11','NUM_ID11','IAP_EDO11',
                            'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                            ->where('IAP_ID',$arbol_id)
                            ->paginate(30);            
        }
        if($regcontable->count() <= 0){
            toastr()->error('No existen requisitos contables.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.verReqc',compact('nombre','usuario','regiap','regperiodicidad','regnumeros', 'regperiodos','regcontable','regformatos'));
    }


    public function actionNuevoReqc(){
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
        $regcontable  = regReqContablesModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'IAP_D6' ,'PER_ID6' ,'NUM_ID6' ,'IAP_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'IAP_D7' ,'PER_ID7' ,'NUM_ID7' ,'IAP_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'IAP_D8' ,'PER_ID8' ,'NUM_ID8' ,'IAP_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'IAP_D9' ,'PER_ID9' ,'NUM_ID9' ,'IAP_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','IAP_D10','PER_ID10','NUM_ID10','IAP_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','IAP_D11','PER_ID11','NUM_ID11','IAP_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('IAP_ID','ASC')
                        ->get();        
        //dd($unidades);
        return view('sicinar.requisitos.nuevoReqc',compact('regper','regnumeros','regiap','regperiodos','regperiodicidad','nombre','usuario','regcontable','regformatos'));
    }

    public function actionAltaNuevoReqc(Request $request){
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

        /************ Registro *****************************/ 
        $regcontable  = regReqContablesModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'IAP_D6' ,'PER_ID6' ,'NUM_ID6' ,'IAP_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'IAP_D7' ,'PER_ID7' ,'NUM_ID7' ,'IAP_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'IAP_D8' ,'PER_ID8' ,'NUM_ID8' ,'IAP_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'IAP_D9' ,'PER_ID9' ,'NUM_ID9' ,'IAP_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','IAP_D10','PER_ID10','NUM_ID10','IAP_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','IAP_D11','PER_ID11','NUM_ID11','IAP_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $request->periodo_id,'IAP_ID' => $request->iap_id])
                        ->get();
        if($regcontable->count() <= 0 && !empty($request->iap_id)){
            //********** Registrar la alta *****************************/
            //$iap_folio = regReqContablesModel::max('IAP_FOLIO');
            //$iap_folio = $iap_folio+1;
            $nuevocontable = new regReqContablesModel();

            $file6 =null;
            if(isset($request->iap_d6)){
                if(!empty($request->iap_d6)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d6')){
                        $file6=$request->iap_id.'_'.$request->file('iap_d6')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d6')->move(public_path().'/images/', $file6);
                    }
                }
            }            
            $file7 =null;
            if(isset($request->iap_d7)){
                if(!empty($request->iap_d7)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d7')){
                        $file7=$request->iap_id.'_'.$request->file('iap_d7')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d7')->move(public_path().'/images/', $file7);
                    }
                }
            }
            $file8 =null;
            if(isset($request->iap_d8)){
                if(!empty($request->iap_d8)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d8')){
                        $file8=$request->iap_id.'_'.$request->file('iap_d8')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d8')->move(public_path().'/images/', $file8);
                    }
                }
            }
            $file9 =null;
            if(isset($request->iap_d9)){
                if(!empty($request->iap_d9)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d9')){
                        $file9=$request->iap_id.'_'.$request->file('iap_d9')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d9')->move(public_path().'/images/', $file9);
                    }
                }
            }            
            $file10 =null;
            if(isset($request->iap_d10)){
                if(!empty($request->iap_d10)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d10')){
                        $file10=$request->iap_id.'_'.$request->file('iap_d10')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d10')->move(public_path().'/images/', $file10);
                    }
                }
            }
            $file11 =null;
            if(isset($request->iap_d11)){
                if(!empty($request->iap_d11)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d11')){
                        $file11=$request->iap_id.'_'.$request->file('iap_d11')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d11')->move(public_path().'/images/', $file11);
                    }
                }
            }

            $nuevocontable->PERIODO_ID   = $request->periodo_id;
            $nuevocontable->IAP_ID       = $request->iap_id;        

            $nuevocontable->DOC_ID6     = $request->doc_id6;
            $nuevocontable->FORMATO_ID6 = $request->formato_id6;
            $nuevocontable->IAP_D6      = $file6;        
            $nuevocontable->NUM_ID6     = $request->num_id6;
            $nuevocontable->PER_ID6     = $request->per_id6;        
            $nuevocontable->IAP_EDO6    = $request->iap_edo6;

            $nuevocontable->DOC_ID7     = $request->doc_id7;
            $nuevocontable->FORMATO_ID7 = $request->formato_id7;
            $nuevocontable->IAP_D7      = $file7;        
            $nuevocontable->NUM_ID7     = $request->num_id7;
            $nuevocontable->PER_ID7     = $request->per_id7;        
            $nuevocontable->IAP_EDO7    = $request->iap_edo7;

            $nuevocontable->DOC_ID8     = $request->doc_id8;
            $nuevocontable->FORMATO_ID8 = $request->formato_id8;
            $nuevocontable->IAP_D8      = $file8;        
            $nuevocontable->NUM_ID8     = $request->num_id8;
            $nuevocontable->PER_ID8     = $request->per_id8;        
            $nuevocontable->IAP_EDO8    = $request->iap_edo8;

            $nuevocontable->DOC_ID9     = $request->doc_id9;
            $nuevocontable->FORMATO_ID9 = $request->formato_id9;
            $nuevocontable->IAP_D9      = $file9;        
            $nuevocontable->NUM_ID9     = $request->num_id9;
            $nuevocontable->PER_ID9     = $request->per_id9;        
            $nuevocontable->IAP_EDO9    = $request->iap_edo9;        

            $nuevocontable->DOC_ID10     = $request->doc_id10;
            $nuevocontable->FORMATO_ID10 = $request->formato_id10;
            $nuevocontable->IAP_D10      = $file10;        
            $nuevocontable->NUM_ID10     = $request->num_id10;
            $nuevocontable->PER_ID10     = $request->per_id10;        
            $nuevocontable->IAP_EDO10    = $request->iap_edo10; 

            $nuevocontable->DOC_ID11     = $request->doc_id11;
            $nuevocontable->FORMATO_ID11 = $request->formato_id11;
            $nuevocontable->IAP_D11      = $file11;        
            $nuevocontable->NUM_ID11     = $request->num_id11;
            $nuevocontable->PER_ID11     = $request->per_id11;        
            $nuevocontable->IAP_EDO11    = $request->iap_edo11;         

            $nuevocontable->PREG_001     = $request->preg_001; 
            $nuevocontable->PREG_002     = $request->preg_002;
            $nuevocontable->PREG_003     = $request->preg_003; 
            $nuevocontable->PREG_004     = $request->preg_004;
            $nuevocontable->PREG_005     = $request->preg_005; 
            $nuevocontable->PREG_006     = $request->preg_006;                        

            $nuevocontable->IP           = $ip;
            $nuevocontable->LOGIN        = $nombre;         // Usuario ;
            $nuevocontable->save();

            if($nuevocontable->save() == true){
                toastr()->success('Información jurdica registrada.','ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3004;
                $xtrx_id      =        94;    //alta
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
                
                //return redirect()->route('nuevocontable');
                //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','rango','preguntas','apartados'));
            }else{
                toastr()->error('Error inesperado al registrar requisitos contables. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                //return back();
                //return redirect()->route('nuevocontable');
            }   //******************** Termina la alta ************************/ 

        }else{
            toastr()->error('Ya existen requisitos contables.','Por favor editar, lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }   // Termian If de busqueda ****************

        return redirect()->route('verReqc');
    }


    /****************** Editar registro  **********/
    public function actionEditarReqc($id){
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
        $regcontable  = regReqContablesModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'IAP_D6' ,'PER_ID6' ,'NUM_ID6' ,'IAP_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'IAP_D7' ,'PER_ID7' ,'NUM_ID7' ,'IAP_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'IAP_D8' ,'PER_ID8' ,'NUM_ID8' ,'IAP_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'IAP_D9' ,'PER_ID9' ,'NUM_ID9' ,'IAP_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','IAP_D10','PER_ID10','NUM_ID10','IAP_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','IAP_D11','PER_ID11','NUM_ID11','IAP_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID', $id)
                        ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe requisitos contables.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc',compact('nombre','usuario','regiap','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));

    }
    
    public function actionActualizarReqc(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('IAP_ID',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existen requisitos contables.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //****************** Actualizar **************************/
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name6 =null;        
            if($request->hasFile('iap_d6')){
                $name6 = $id.'_'.$request->file('iap_d6')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d6')->move(public_path().'/images/', $name6);
            }            
            $name7 =null;        
            if($request->hasFile('iap_d7')){
                $name7 = $id.'_'.$request->file('iap_d7')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d7')->move(public_path().'/images/', $name7);
            }            
            $name8 =null;        
            if($request->hasFile('iap_d8')){
                $name8 = $id.'_'.$request->file('iap_d8')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d8')->move(public_path().'/images/', $name8);
            }            
            $name9 =null;        
            if($request->hasFile('iap_d9')){
                echo "Escribió en el campo de texto 9: " .'-'. $request->iap_d9 .'-'. "<br><br>"; 
                $name9 = $id.'_'.$request->file('iap_d9')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d9')->move(public_path().'/images/', $name9);
            }
            $name10 =null;        
            if($request->hasFile('iap_d10')){
                echo "Escribió en el campo de texto 10: " .'-'. $request->iap_d10 .'-'. "<br><br>"; 
                $name10 = $id.'_'.$request->file('iap_d10')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d10')->move(public_path().'/images/', $name10);
            }            
            $name11 =null;        
            if($request->hasFile('iap_d11')){
                echo "Escribió en el campo de texto 11: " .'-'. $request->iap_d11 .'-'. "<br><br>"; 
                $name11 = $id.'_'.$request->file('iap_d11')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d11')->move(public_path().'/images/', $name11);
            }            

            // ************* Actualizamos registro **********************************
            $regcontable = regReqContablesModel::where('IAP_ID',$id)        
                           ->update([                
                                     //'PERIODO_ID'   => $request->periodo_id,

                                     'DOC_ID6'     => $request->doc_id6,
                                     'FORMATO_ID6' => $request->formato_id6,                            
                                     //'IAP_D6'      => $name6,                                                       
                                     'PER_ID6'     => $request->per_id6,
                                     'NUM_ID6'     => $request->num_id6,                
                                     'IAP_EDO6'    => $request->iap_edo6,

                                     'DOC_ID7'     => $request->doc_id7,
                                     'FORMATO_ID7' => $request->formato_id7,                            
                                     //'IAP_D7'      => $name7,                                                       
                                     'PER_ID7'     => $request->per_id7,
                                     'NUM_ID7'     => $request->num_id7,                
                                     'IAP_EDO7'    => $request->iap_edo7,

                                     'DOC_ID8'     => $request->doc_id8,
                                     'FORMATO_ID8' => $request->formato_id8,                            
                                     //'IAP_D8'      => $name8,                                                       
                                     'PER_ID8'     => $request->per_id8,
                                     'NUM_ID8'     => $request->num_id8,                
                                     'IAP_EDO8'    => $request->iap_edo8,

                                     'DOC_ID9'     => $request->doc_id9,
                                     'FORMATO_ID9' => $request->formato_id9,                            
                                     //'IAP_D9'      => $name9,                                                       
                                     'PER_ID9'     => $request->per_id9,
                                     'NUM_ID9'     => $request->num_id9,                
                                     'IAP_EDO9'    => $request->iap_edo9,
                                    
                                     'DOC_ID10'     => $request->doc_id10,
                                     'FORMATO_ID10' => $request->formato_id10,                                          
                                     //'IAP_D10'      => $name10,              
                                     'PER_ID10'     => $request->per_id10,
                                     'NUM_ID10'     => $request->num_id10,                
                                     'IAP_EDO10'    => $request->iap_edo10,
                                     
                                     'DOC_ID11'     => $request->doc_id11,
                                     'FORMATO_ID11' => $request->formato_id11, 
                                     //'IAP_D11'      => $name11,        
                                     'PER_ID11'     => $request->per_id11,
                                     'NUM_ID11'     => $request->num_id11,                
                                     'IAP_EDO11'    => $request->iap_edo11,

                                     'PREG_001'    => $request->preg_001,
                                     'PREG_002'    => $request->preg_002,
                                     'PREG_003'    => $request->preg_003,
                                     'PREG_004'    => $request->preg_004,
                                     'PREG_005'    => $request->preg_005,
                                     'PREG_006'    => $request->preg_006,                                     

                                     //'IAP_STATUS'   => $request->iap_status,
                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/
                                    ]);
            toastr()->success('Requisitos contables actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
        return redirect()->route('verReqc');
        
    }

    /****************** Editar requisitos contables **********/
    public function actionEditarReqc6($id){
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
        $regcontable  = regReqContablesModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'IAP_D6' ,'PER_ID6' ,'NUM_ID6' ,'IAP_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'IAP_D7' ,'PER_ID7' ,'NUM_ID7' ,'IAP_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'IAP_D8' ,'PER_ID8' ,'NUM_ID8' ,'IAP_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'IAP_D9' ,'PER_ID9' ,'NUM_ID9' ,'IAP_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','IAP_D10','PER_ID10','NUM_ID10','IAP_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','IAP_D11','PER_ID11','NUM_ID11','IAP_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID', $id)
                        ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe requisitos contables.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc6',compact('nombre','usuario','regiap','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqc6(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('IAP_ID',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe archivo PDF6.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name6 =null;
            if($request->hasFile('iap_d6')){
                echo "Escribió en el campo de texto 6: " .'-'. $request->iap_d6 .'-'. "<br><br>"; 
                $name6 = $id.'_'.$request->file('iap_d6')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d6')->move(public_path().'/images/', $name6);

                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID6'    => $request->doc_id6,
                                     'FORMATO_ID6'=> $request->formato_id6,             
                                     'IAP_D6'     => $name6,                  
                                     'PER_ID6'    => $request->per_id6,
                                     'NUM_ID6'    => $request->num_id6,                
                                     'IAP_EDO6'   => $request->iap_edo6,

                                     'PREG_001'   => $request->preg_001,
                                     'PREG_002'   => $request->preg_002,                     

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('archivo contable 6 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID6'    => $request->doc_id6,
                                     'FORMATO_ID6'=> $request->formato_id6,             
                                     //'IAP_D6'   => $name6,                  
                                     'PER_ID6'    => $request->per_id6,
                                     'NUM_ID6'    => $request->num_id6,                
                                     'IAP_EDO6'   => $request->iap_edo6,

                                     'PREG_001'   => $request->preg_001,
                                     'PREG_002'   => $request->preg_002,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo contable 6 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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

        return redirect()->route('verReqc');
        
    }    

        /****************** Editar requisitos contables **********/
    public function actionEditarReqc7($id){
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
        $regcontable  = regReqContablesModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'IAP_D6' ,'PER_ID6' ,'NUM_ID6' ,'IAP_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'IAP_D7' ,'PER_ID7' ,'NUM_ID7' ,'IAP_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'IAP_D8' ,'PER_ID8' ,'NUM_ID8' ,'IAP_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'IAP_D9' ,'PER_ID9' ,'NUM_ID9' ,'IAP_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','IAP_D10','PER_ID10','NUM_ID10','IAP_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','IAP_D11','PER_ID11','NUM_ID11','IAP_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID', $id)
                        ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe requisitos contables.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc7',compact('nombre','usuario','regiap','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqc7(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('IAP_ID',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe archivo PDF7.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name7 =null;
            if($request->hasFile('iap_d7')){
                echo "Escribió en el campo de texto 7: " .'-'. $request->iap_d7 .'-'. "<br><br>"; 
                $name7 = $id.'_'.$request->file('iap_d7')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d7')->move(public_path().'/images/', $name7);

                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID7'    => $request->doc_id7,
                                     'FORMATO_ID7'=> $request->formato_id7,             
                                     'IAP_D7'     => $name7,                  
                                     'PER_ID7'    => $request->per_id7,
                                     'NUM_ID7'    => $request->num_id7,                
                                     'IAP_EDO7'   => $request->iap_edo7,

                                     'PREG_003'   => $request->preg_003,
                                     'PREG_004'   => $request->preg_004,                                     
                                     'PREG_005'   => $request->preg_005,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('archivo contable 7 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID7'    => $request->doc_id7,
                                     'FORMATO_ID7'=> $request->formato_id7,             
                                     //'IAP_D7'   => $name7,                  
                                     'PER_ID7'    => $request->per_id7,
                                     'NUM_ID7'    => $request->num_id7,                
                                     'IAP_EDO7'   => $request->iap_edo7,

                                     'PREG_003'   => $request->preg_003,
                                     'PREG_004'   => $request->preg_004, 
                                     'PREG_005'   => $request->preg_005,                                

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo contable 7 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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

        return redirect()->route('verReqc');
        
    }    

    /****************** Editar requisitos contables **********/
    public function actionEditarReqc8($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get(); 
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                               
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'IAP_D6' ,'PER_ID6' ,'NUM_ID6' ,'IAP_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'IAP_D7' ,'PER_ID7' ,'NUM_ID7' ,'IAP_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'IAP_D8' ,'PER_ID8' ,'NUM_ID8' ,'IAP_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'IAP_D9' ,'PER_ID9' ,'NUM_ID9' ,'IAP_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','IAP_D10','PER_ID10','NUM_ID10','IAP_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','IAP_D11','PER_ID11','NUM_ID11','IAP_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID', $id)
                        ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe requisito contable.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc8',compact('nombre','usuario','regiap','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqc8(reqcontablesRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        // **************** actualizar ******************************
        $regcontable = regReqContablesModel::where('IAP_ID',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe archivo PDF8.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name8 =null;
            if($request->hasFile('iap_d8')){
                echo "Escribió en el campo de texto 8: " .'-'. $request->iap_d8 .'-'. "<br><br>"; 
                $name8 = $id.'_'.$request->file('iap_d8')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d8')->move(public_path().'/images/', $name8);

                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID8'    => $request->doc_id8,
                                     'FORMATO_ID8'=> $request->formato_id8,             
                                     'IAP_D8'     => $name8,                  
                                     'PER_ID8'    => $request->per_id8,
                                     'NUM_ID8'    => $request->num_id8,                
                                     'IAP_EDO8'   => $request->iap_edo8,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('archivo contable 8 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID8'    => $request->doc_id8,
                                     'FORMATO_ID8'=> $request->formato_id8,             
                                     //'IAP_D8'   => $name8,                  
                                     'PER_ID8'    => $request->per_id8,
                                     'NUM_ID8'    => $request->num_id8,                
                                     'IAP_EDO8'   => $request->iap_edo8,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo contable 8 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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

        return redirect()->route('verReqc');
        
    }    

    /****************** Editar requisitos contables **********/
    public function actionEditarReqc9($id){
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
        $regcontable  = regReqContablesModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'IAP_D6' ,'PER_ID6' ,'NUM_ID6' ,'IAP_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'IAP_D7' ,'PER_ID7' ,'NUM_ID7' ,'IAP_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'IAP_D8' ,'PER_ID8' ,'NUM_ID8' ,'IAP_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'IAP_D9' ,'PER_ID9' ,'NUM_ID9' ,'IAP_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','IAP_D10','PER_ID10','NUM_ID10','IAP_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','IAP_D11','PER_ID11','NUM_ID11','IAP_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID', $id)
                        ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe requisitos contables.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc9',compact('nombre','usuario','regiap','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqc9(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('IAP_ID',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe archivo PDF9.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name9 =null;
            if($request->hasFile('iap_d9')){
                echo "Escribió en el campo de texto 9: " .'-'. $request->iap_d9 .'-'. "<br><br>"; 
                $name9 = $id.'_'.$request->file('iap_d9')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d9')->move(public_path().'/images/', $name9);

                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID9'    => $request->doc_id9,
                                     'FORMATO_ID9'=> $request->formato_id9,             
                                     'IAP_D9'     => $name9,                  
                                     'PER_ID9'    => $request->per_id9,
                                     'NUM_ID9'    => $request->num_id9,                
                                     'IAP_EDO9'   => $request->iap_edo9,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('archivo contable 9 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID9'    => $request->doc_id9,
                                     'FORMATO_ID9'=> $request->formato_id9,             
                                     //'IAP_D9'   => $name9,                  
                                     'PER_ID9'    => $request->per_id9,
                                     'NUM_ID9'    => $request->num_id9,                
                                     'IAP_EDO9'   => $request->iap_edo9,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo contable 9 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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

        return redirect()->route('verReqc');
        
    }    
    /****************** Editar requisitos contables **********/
    public function actionEditarReqc10($id){
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
        $regcontable = regReqContablesModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'IAP_D6' ,'PER_ID6' ,'NUM_ID6' ,'IAP_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'IAP_D7' ,'PER_ID7' ,'NUM_ID7' ,'IAP_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'IAP_D8' ,'PER_ID8' ,'NUM_ID8' ,'IAP_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'IAP_D9' ,'PER_ID9' ,'NUM_ID9' ,'IAP_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','IAP_D10','PER_ID10','NUM_ID10','IAP_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','IAP_D11','PER_ID11','NUM_ID11','IAP_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('IAP_ID', $id)
                       ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe requisitos contables.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc10',compact('nombre','usuario','regiap','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqc10(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('IAP_ID',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existen requisitos contables.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name10 =null;
            if($request->hasFile('iap_d10')){
                echo "Escribió en el campo de texto 10: " .'-'. $request->iap_d10 .'-'. "<br><br>"; 
                $name10 = $id.'_'.$request->file('iap_d10')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d10')->move(public_path().'/images/', $name10);

                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('IAP_ID',$id)        
                               ->update([  
                                      'DOC_ID10'    => $request->doc_id10,
                                      'FORMATO_ID10'=> $request->formato_id10,             
                                      'IAP_D10'     => $name10,           
                                      'PER_ID10'    => $request->per_id10,
                                      'NUM_ID10'    => $request->num_id10,                
                                      'IAP_EDO10'   => $request->iap_edo10,

                                      'PREG_006'    => $request->preg_006,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('requisitos contables 10 actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('IAP_ID',$id)        
                               ->update([  
                                      'DOC_ID10'    => $request->doc_id10,
                                      'FORMATO_ID10'=> $request->formato_id10,             
                                      //'IAP_D10'   => $name10,           
                                      'PER_ID10'    => $request->per_id10,
                                      'NUM_ID10'    => $request->num_id10,                
                                      'IAP_EDO10'   => $request->iap_edo10,

                                      'PREG_006'    => $request->preg_006,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('requisito contable 10 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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

        return redirect()->route('verReqc');    
    }    

    /****************** Editar  *********************************/
    public function actionEditarReqc11($id){
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
        $regcontable  = regReqContablesModel::select('IAP_FOLIO','IAP_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'IAP_D6' ,'PER_ID6' ,'NUM_ID6' ,'IAP_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'IAP_D7' ,'PER_ID7' ,'NUM_ID7' ,'IAP_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'IAP_D8' ,'PER_ID8' ,'NUM_ID8' ,'IAP_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'IAP_D9' ,'PER_ID9' ,'NUM_ID9' ,'IAP_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','IAP_D10','PER_ID10','NUM_ID10','IAP_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','IAP_D11','PER_ID11','NUM_ID11','IAP_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID',$id)
                        ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe registro de información de asistencia social y contable.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }   /********************* Termina de actualizar ***********************/
        return view('sicinar.requisitos.editarReqc11',compact('nombre','usuario','regiap','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqc11(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('IAP_ID',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existen requisitos contables.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name11 =null;
            if($request->hasFile('iap_d11')){
                echo "Escribió en el campo de texto 11: " .'-'. $request->iap_d11 .'-'. "<br><br>"; 
                $name11 = $id.'_'.$request->file('iap_d11')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d11')->move(public_path().'/images/', $name11);

                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('IAP_ID',$id)        
                               ->update([  
                                      'DOC_ID11'    => $request->doc_id11,
                                      'FORMATO_ID11'=> $request->formato_id11,             
                                      'IAP_D11'     => $name11,           
                                      'PER_ID11'    => $request->per_id11,
                                      'NUM_ID11'    => $request->num_id11,                
                                      'IAP_EDO11'   => $request->iap_edo11,
                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('requisitos contables 11 actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('IAP_ID',$id)        
                               ->update([  
                                      'DOC_ID11'    => $request->doc_id11,
                                      'FORMATO_ID11'=> $request->formato_id11,             
                                      //'IAP_D11'   => $name11,           
                                      'PER_ID11'    => $request->per_id11,
                                      'NUM_ID11'    => $request->num_id11,                
                                      'IAP_EDO11'   => $request->iap_edo11,
                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('requisito contable 11 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                                           'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                           'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M' => $regbitacora->IP           = $ip,
                                            'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                            'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         

        }       /************ Termina de actualizar ********************************/

        return redirect()->route('verReqc');    
    }    

    //***** Borrar registro completo ***********************
    public function actionBorrarReqc($id){
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
        $regcontable = regReqContablesModel::where('IAP_ID',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe requisito contable.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regcontable->delete();
            toastr()->success('Requisito contable eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre aboorar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        96;     // borrar 
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

        }   /************* Termina de eliminar  ************************************/
        
        return redirect()->route('verReqc');
    }    

}