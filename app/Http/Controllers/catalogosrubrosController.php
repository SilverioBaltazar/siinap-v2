<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\catalogosrubrosRequest;
use App\regRubroModel;
use App\regBitacoraModel;
// Exportar a excel 
use App\Exports\ExcelExportCatRubros;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;

class catalogosrubrosController extends Controller
{
    public function actionNuevoRubro(){
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

        $regrubro = regRubroModel::select('RUBRO_ID','RUBRO_DESC', 'RUBRO_STATUS','RUBRO_FECREG')
            ->orderBy('RUBRO_ID','asc')->get();
        //dd($unidades);
        return view('sicinar.catalogos.nuevoRubro',compact('regrubro','nombre','usuario','estructura','id_estructura'));
    }

    public function actionAltaNuevoRubro(Request $request){
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
        //$plan = progtrabModel::select('STATUS_1')
        //    ->where('N_PERIODO',date('Y'))
        //    ->where('ESTRUCGOB_ID','like',$request->estructura.'%')
        //    ->where('CVE_DEPENDENCIA','like',$request->unidad.'%')
        //    ->get();
        //if($plan->count() > 0){
        //    toastr()->error('El Plan de Trabajo para esta Unidad Administrativa ya ha sido creado.','Plan de Trabajo Duplicado!',['positionClass' => 'toast-bottom-right']);
        //    return back();
        //}
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

        /************ ALTA DE RUBRO ************************************/        
        $rubro_id = regRubroModel::max('RUBRO_ID');
        $rubro_id = $rubro_id+1;
        $nuevorubro = new regRubroModel();
        $nuevorubro->RUBRO_ID   = $rubro_id;
        $nuevorubro->RUBRO_DESC = $request->rubro_desc;
        $nuevorubro->save();

        if($nuevorubro->save() == true){
            toastr()->success('El rubro social ha sido dado de alta correctamente.','Rubro social dado de alta!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoProceso');
            //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','estructura','id_estructura','rango','preguntas','apartados'));
        }else{
            toastr()->error('Error inesperado al dar de alta el rubro social. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //return back();
            //return redirect()->route('nuevoProceso');
        }

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         7;
        $xfuncion_id  =      7009;
        $xtrx_id      =       133;

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $rubro_id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $rubro_id;       // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $xip;            // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //****************************************
            $reg = regBitacoraModel::select('NO_VECES')->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $rubro_id]) 
            ->first();
            if($reg->count() <= 0) $xno_veces = 1;
            else {
                $xno_veces = $reg->NO_VECES; 
                $xno_veces = ($xno_veces+1); 
            }
            //****************************************                    
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $rubro_id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $xip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/ 

        return redirect()->route('verRubro');
    }

    
    public function actionVerRubro(){
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

        $regrubro = regRubroModel::select('RUBRO_ID','RUBRO_DESC', 'RUBRO_STATUS','RUBRO_FECREG')
            ->orderBy('RUBRO_ID','ASC')
            ->paginate(15);
        if($regrubro->count() <= 0){
            toastr()->error('No existen registros de Rubros sociales dados de alta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevoRubro');
        }
        return view('sicinar.catalogos.verRubro',compact('nombre','usuario','estructura','id_estructura','regrubro'));

    }

    public function actionEditarRubro($id){
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

        $regrubro = regRubroModel::select('RUBRO_ID','RUBRO_DESC','RUBRO_STATUS','RUBRO_FECREG')
            ->where('RUBRO_ID',$id)
            ->orderBy('RUBRO_ID','ASC')
            ->first();
        if($regrubro->count() <= 0){
            toastr()->error('No existe registros de rubros sociales.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevoProceso');
        }
        return view('sicinar.catalogos.editarRubro',compact('nombre','usuario','estructura','id_estructura','regrubro'));

    }

    public function actionActualizarRubro(catalogosrubrosRequest $request, $id){
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

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         7;
        $xfuncion_id  =      7009;
        $xtrx_id      =       134;

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
            $nuevoregBitacora->IP         = $xip;            // IP
            $nuevoregBitacora->LOGIN      = $nombre;        // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //****************************************
            $reg = regBitacoraModel::select('NO_VECES')->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id]) 
            ->first();
            if($reg->count() <= 0) $xno_veces = 1;
            else {
                $xno_veces = $reg->NO_VECES; 
                $xno_veces = ($xno_veces+1); 
            }
            //****************************************                    
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $xip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/         

        // **************** actualizar ******************************
        $regrubro = regRubroModel::where('RUBRO_ID',$id);
        if($regrubro->count() <= 0)
            toastr()->error('No existe rubro social.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regrubro = regRubroModel::where('RUBRO_ID',$id)        
            ->update([
                'RUBRO_DESC' => strtoupper($request->rubro_desc),
                'RUBRO_STATUS' => $request->rubro_status
            ]);
            toastr()->success('Rubros social actualizado correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verRubro');
        //return view('sicinar.catalogos.verProceso',compact('nombre','usuario','estructura','id_estructura','regproceso'));

    }

public function actionBorrarRubro($id){
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
        $xproceso_id  =         7;
        $xfuncion_id  =      7009;
        $xtrx_id      =       135;

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
            $nuevoregBitacora->IP         = $xip;            // IP
            $nuevoregBitacora->LOGIN      = $nombre;        // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //****************************************
            $reg = regBitacoraModel::select('NO_VECES')->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id]) 
            ->first();
            if($reg->count() <= 0) $xno_veces = 1;
            else {
                $xno_veces = $reg->NO_VECES; 
                $xno_veces = ($xno_veces+1); 
            }
            //****************************************                    
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $xip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/          

        $regrubro = regRubroModel::select('RUBRO_ID','RUBRO_DESC', 'RUBRO_STATUS','RUBRO_FECREG')->where('RUBRO_ID',$id);
        //                     ->find('RUBRO_ID',$id);
        if($regrubro->count() <= 0)
            toastr()->error('No existe rubro social.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regrubro->delete();
            toastr()->success('Rubro social ha sido eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verRubro');
    }    

    // exportar a formato catalogo de rubros sociales a formato excel
    public function exportCatRubrosExcel(){
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
        $xproceso_id  =         7;
        $xfuncion_id  =      7009;
        $xtrx_id      =       136;            // Exportar a formato Excel
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
            $nuevoregBitacora->IP         = $xip;            // IP
            $nuevoregBitacora->LOGIN      = $nombre;        // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //****************************************
            $reg = regBitacoraModel::select('NO_VECES')->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id]) 
            ->first();
            if($reg->count() <= 0) $xno_veces = 1;
            else {
                $xno_veces = $reg->NO_VECES; 
                $xno_veces = ($xno_veces+1); 
            }
            //****************************************                    
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $xip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/  

        return Excel::download(new ExcelExportCatRubros, 'Cat_Rubros_Sociales_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato catalogo de rubros sociales a formato PDF
    public function exportCatRubrosPdf(){
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

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         7;
        $xfuncion_id  =      7009;
        $xtrx_id      =       137;       //Exportar a formato PDF
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
            $nuevoregBitacora->IP         = $xip;            // IP
            $nuevoregBitacora->LOGIN      = $nombre;        // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //****************************************
            $reg = regBitacoraModel::select('NO_VECES')->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id]) 
            ->first();
            if($reg->count() <= 0) $xno_veces = 1;
            else {
                $xno_veces = $reg->NO_VECES; 
                $xno_veces = ($xno_veces+1); 
            }
            //****************************************                    
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $xip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/          

        $regrubro = regRubroModel::select('RUBRO_ID','RUBRO_DESC','RUBRO_STATUS','RUBRO_FECREG')
            ->orderBy('RUBRO_ID','ASC')->get();
        if($regrubro->count() <= 0){
            toastr()->error('No existen registros en el catalogo de rubros sociales.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verRubro');
        }
        $pdf = PDF::loadView('sicinar.pdf.catrubrosPDF', compact('nombre','usuario','estructura','id_estructura','regrubro'));
        $pdf->setPaper('A4', 'landscape');        
        return $pdf->stream('CatalogoDeRubrosSociales');
    }

}
