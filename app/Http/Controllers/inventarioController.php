<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\inventarioRequest;

use App\regInventarioModel;
use App\regIapModel;
use App\regBitacoraModel;
use App\regPfiscalesModel;
use App\regPeriodosaniosModel;
use App\regMesesModel;
use App\regDiasModel;
use App\regCondicionModel;
// Exportar a excel 
//use App\Exports\ExcelExportCatIAPS;
//use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class inventarioController extends Controller
{

    public function actionVerInventarios(){
        $nombre       = session()->get('userlog'); 
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');

        $regcondicion = regCondicionModel::select('CONDICION_ID','CONDICION_DESC')->get();
        $regmeses     = regMesesModel::select('MES_ID', 'MES_DESC')->get();
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();                           
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get(); 
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->where('PERIODO_ID','>',1979)
                        ->where('PERIODO_ID','<',2021)
                        ->get(); 
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->get();
        if(session()->get('rango') !== '0'){                           
            $reginventario=regInventarioModel::select('ID','PERIODO_ID','IAP_ID','ACTIVO_ID','ACTIVO_DESC',
                        'INVENTARIO_FECADQ','INVENTARIO_FECADQ2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'INVENTARIO_DOC','ACTIVO_VALOR','CONDICION_ID','INVENTARIO_OBS','INVENTARIO_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('IAP_ID'   ,'ASC')
                        ->orderBy('ACTIVO_ID','ASC')
                        ->paginate(30);               
        }else{
            $reginventario=regInventarioModel::select('ID','PERIODO_ID','IAP_ID','ACTIVO_ID','ACTIVO_DESC',
                        'INVENTARIO_FECADQ','INVENTARIO_FECADQ2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'INVENTARIO_DOC','ACTIVO_VALOR','CONDICION_ID','INVENTARIO_OBS','INVENTARIO_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID',$arbol_id)            
                        ->orderBy('IAP_ID'   ,'ASC')
                        ->orderBy('ACTIVO_ID','ASC')
                        ->paginate(30);   
        }                                                

        if($reginventario->count() <= 0){
            toastr()->error('No existen registros de inventario de activo fijo.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoCurso');
        }
        return view('sicinar.inventarios.verInventarios',compact('nombre','usuario','regdias','regmeses','regperiodos','reganios','regiap','regcondicion','reginventario'));
    }

    public function actionNuevoInventario(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regcondicion = regCondicionModel::select('CONDICION_ID','CONDICION_DESC')->get();
        $regmeses     = regMesesModel::select('MES_ID', 'MES_DESC')->get();
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();                                   
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get(); 
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->where('PERIODO_ID','>',1979)
                        ->where('PERIODO_ID','<',2021)
                        ->get();         
        if(session()->get('rango') !== '0'){                           
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','ASC')
                        ->get();                                                        
        }else{
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','ASC')
                        ->where('IAP_ID',$arbol_id)
                        ->get();            
        }                                                
        $reginventario= regInventarioModel::select('ID','PERIODO_ID','IAP_ID','ACTIVO_ID','ACTIVO_DESC',
                        'INVENTARIO_FECADQ','INVENTARIO_FECADQ2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'INVENTARIO_DOC','ACTIVO_VALOR','CONDICION_ID','INVENTARIO_OBS','INVENTARIO_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('IAP_ID'   ,'ASC')
                        ->orderBy('ACTIVO_ID','ASC')
                        ->get();
        //dd($unidades);
        return view('sicinar.inventarios.nuevoInventario',compact('nombre','usuario','regmeses','regperiodos','regdias','reganios','regcondicion','regiap','reginventario'));
    }

    public function actionAltaNuevoInventario(Request $request){
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
        $duplicado = regInventarioModel::where(['IAP_ID' => $request->iap_id, 'ACTIVO_ID' => $request->iap_id])
                     ->get();
        if($duplicado->count() >= 1)
            return back()->withInput()->withErrors(['ACTIVO_DESC' => 'Activo fijo '.$request->activo_desc.' Ya existe. Por favor verificar.']);
        else{    

            /************ ALTA *****************************/ 
            setlocale(LC_TIME, "spanish");   
            header("Content-Type: text/html;charset=utf-8");
            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);                

            $id = regInventarioModel::max('ID');
            $id = $id+1;

            $nuevoinventario = new regInventarioModel();
            $nuevoinventario->ID                = $id;
            $nuevoinventario->PERIODO_ID        = $request->periodo_id;
            $nuevoinventario->IAP_ID            = $request->iap_id;
            //$nuevoinventario->ACTIVO_ID         = substr(trim(strtoupper($request->activo_id)),0,19);
            $nuevoinventario->ACTIVO_ID         = substr(trim(strtoupper($request->activo_id))  ,0, 24);
            //$nuevoinventario->ACTIVO_ID         = $request->activo_id;
            $nuevoinventario->ACTIVO_DESC       = substr(trim(strtoupper($request->activo_desc)),0,499);        
            $nuevoinventario->INVENTARIO_FECADQ = date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) ));
            $nuevoinventario->INVENTARIO_FECADQ2= trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);
            $nuevoinventario->PERIODO_ID1       = $request->periodo_id1;
            $nuevoinventario->MES_ID1           = $request->mes_id1;
            $nuevoinventario->DIA_ID1           = $request->dia_id1;

            $nuevoinventario->INVENTARIO_DOC    = substr(trim(strtoupper($request->inventario_doc)),0,199);
            $nuevoinventario->ACTIVO_VALOR      = $request->activo_valor;
            $nuevoinventario->INVENTARIO_OBS    = substr(trim(strtoupper($request->inventario_obs)),0,3999);
            $nuevoinventario->CONDICION_ID      = $request->condicion_id;

            $nuevoinventario->IP                = $ip;
            $nuevoinventario->LOGIN             = $nombre;         // Usuario ;
            $nuevoinventario->save();
            if($nuevoinventario->save() == true){
                toastr()->success('Activo fijo dado de alta.','ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3009;
                $xtrx_id      =        17;    //Alta 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                               'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                               'FECHA_M', 'IP_M', 'LOGIN_M')
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
                    $nuevoregBitacora->FOLIO      = $id;       // Folio    
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
                                 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,
                                        'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                                   ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/             
                //return redirect()->route('nuevaIap');
                //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','rango','preguntas','apartados'));
            }else{
                toastr()->error('Error en alta del activo fijo. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                //return back();
                //return redirect()->route('nuevoProceso');
            }   // ************************* Termiande la alta ************************//
        }   // ************************* Termina de validad duplicado *************//

        return redirect()->route('verInventarios');
    }


    public function actionEditarInventario($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regcondicion = regCondicionModel::select('CONDICION_ID','CONDICION_DESC')->get();
        $regmeses     = regMesesModel::select('MES_ID', 'MES_DESC')->get();
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();                           
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get(); 
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->where('PERIODO_ID','>',1979)
                        ->where('PERIODO_ID','<',2021)
                        ->get(); 
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->get();                                                        
        }else{
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->where('IAP_ID',$arbol_id)
                        ->get();            
        }        
        $reginventario= regInventarioModel::select('ID','PERIODO_ID','IAP_ID','ACTIVO_ID','ACTIVO_DESC',
                        'INVENTARIO_FECADQ','INVENTARIO_FECADQ2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'INVENTARIO_DOC','ACTIVO_VALOR','CONDICION_ID','INVENTARIO_OBS','INVENTARIO_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('ID', $id)
                        ->first();
        if($reginventario->count() <= 0){
            toastr()->error('No existe registro de activo fijo.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoCurso');
        }
        return view('sicinar.inventarios.editarInventario',compact('nombre','usuario','regcondicion','regdias','regmeses','regperiodos','reganios','regiap','reginventario'));

    }

    public function actionActualizarInventario(inventarioRequest $request, $id){
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
        $reginventario = regInventarioModel::where('ID', $id);
        if($reginventario->count() <= 0)
            toastr()->error('No existe activo fijo.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            // ************ Se actulizan datos del curso *************
            setlocale(LC_TIME, "spanish");   
            //header("Content-Type: text/html;charset=utf-8");

            //$curso_desc = substr(Trim(strtoupper($request->curso_desc)),0,499);
            //$curso_desc = iconv("UTF-8", "ISO-8859-1", $curso_desc);

            //echo "Fecha de inicio : " .'-'. $request->curso_finicio .'-'. "<br><br>"; 
            //echo "Fecha de inicio : " .'-'. date('Y/m/d', strtotime($request->curso_finicio)) .'-'. "<br><br>"; 
            //echo "Fecha de termino 2: " .'-'. $request->curso_ffin .'-'. "<br><br>"; 

            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);                
            $reginventario = regInventarioModel::where('ID', $id)        
            ->update([                
                //'IAP_ID'          => $request->iap_id,                
                //'PERIODO_ID'      => $request->periodo_id,
                'ACTIVO_DESC'       => substr(Trim(strtoupper($request->activo_desc)) ,0,499),                
                'INVENTARIO_FECADQ' =>  date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )), 
                'INVENTARIO_FECADQ2'=>  trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1),
                'PERIODO_ID1'       => $request->periodo_id1,
                'MES_ID1'           => $request->mes_id1,                
                'DIA_ID1'           => $request->dia_id1,

                'INVENTARIO_DOC'    => substr(Trim(strtoupper($request->inventario_doc)) ,0,99),
                'ACTIVO_VALOR'      => $request->activo_valor,
         
                'INVENTARIO_OBS'    => substr(Trim(strtoupper($request->inventario_obs)),0,3999),
                'CONDICION_ID'      => $request->condicion_id,
                'INVENTARIO_STATUS' => $request->inventario_status,                

                'IP_M'              => $ip,
                'LOGIN_M'           => $nombre,
                'FECHA_M'           => date('Y/m/d')    //date('d/m/Y')                                
            ]);
            toastr()->success('Activo fijo actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3009;
            $xtrx_id      =        18;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                           'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 
                           'IP_M', 'LOGIN_M')
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
                             'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                            ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->update([
                                        'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                        'IP_M'    => $regbitacora->IP       = $ip,
                                        'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                        'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         

        }

        return redirect()->route('verInventarios');
        //return view('sicinar.catalogos.verProceso',compact('nombre','usuario','regproceso'));
    }


    public function actionBorrarInventario($id){
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

        /************ Elimina curso **************************************/
        $reginventario = regInventarioModel::where('ID', $id);        
        //                    ->find('RUBRO_ID',$id);
        if($reginventario->count() <= 0)
            toastr()->error('No existe activo fijo.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $reginventario->delete();
            toastr()->success('activo fijo eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            //echo 'Ya entre aboorar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3009;
            $xtrx_id      =        19;     // Baja 
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
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
                             'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                              ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                       'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                       'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                              ->update([
                                        'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                        'IP_M' => $regbitacora->IP           = $ip,
                                        'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                        'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }  /************ Bitacora termina *************************************/                 
        }      /************* Termina de eliminar *********************************/

        return redirect()->route('verInventarios');
    }    

}