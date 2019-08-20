<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\catalogosRequest;
use App\Http\Requests\catalogosfuncionRequest;
use App\regProcesoModel;
use App\regFuncionModel;
// Exportar a excel 
use App\Exports\ExcelExportCatFunciones;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;

class catalogosfuncionesController extends Controller
{
    public function actionNuevaFuncion(){
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
        
        $regproceso = regProcesoModel::select('PROCESO_ID','PROCESO_DESC','PROCESO_STATUS','PROCESO_FECREG')
            ->orderBy('PROCESO_ID','asc')->get();
        if($regproceso->count() <= 0){
            toastr()->error('No existen registros en el catalogo de procesos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('verProceso');
        }        

        $regfuncion = regFuncionModel::select('PROCESO_ID','FUNCION_ID','FUNCION_DESC', 'FUNCION_STATUS','FUNCION_FECREG')
            ->orderBy('PROCESO_ID','FUNCION_ID','asc')->get();
        //dd($unidades);
        return view('sicinar.catalogos.nuevaFuncion',compact('regfuncion','regproceso','nombre','usuario','estructura','id_estructura'));
    }

    public function actionAltaNuevaFuncion(Request $request){
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
        //$ip           = session()->get('ip');
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
        //$plan = progtrabModel::select('STATUS_1')
        //    ->where('N_PERIODO',date('Y'))
        //    ->where('ESTRUCGOB_ID','like',$request->estructura.'%')
        //    ->where('CVE_DEPENDENCIA','like',$request->unidad.'%')
        //    ->get();
        //if($plan->count() > 0){
        //    toastr()->error('El Plan de Trabajo para esta Unidad Administrativa ya ha sido creado.','Plan de Trabajo Duplicado!',['positionClass' => 'toast-bottom-right']);
        //    return back();
        //}
        //$proceso_id = regProcesoModel::max('PROCESO_ID');
        //$proceso_id = $proceso_id+1;
        /* ALTA DEl proceso ****************************/
        $nuevaFuncion = new regFuncionModel();
        $nuevaFuncion->PROCESO_ID    = $request->proceso_id;
        $nuevaFuncion->FUNCION_ID    = $request->funcion_id;
        $nuevaFuncion->FUNCION_DESC  = strtoupper($request->funcion_desc);
        //$nuevaFuncion->FUNCION_STATUS= $request->funcion_status;        
        $nuevaFuncion->save();

        if($nuevaFuncion->save() == true){
            toastr()->success('La función del Proceso ha sido dada de alta correctamente.','Función dada de alta!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoProceso');
            //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','estructura','id_estructura','rango','preguntas','apartados'));
        }else{
            toastr()->error('Error inesperado al dar de alta la función del Proceso. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //return back();
            //return redirect()->route('nuevoProceso');
        }
        return redirect()->route('verFuncion');
    }

    
    public function actionVerFuncion(){
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
        $regfuncion = regFuncionModel::join('JP_CAT_PROCESOS','JP_CAT_PROCESOS.PROCESO_ID','=','JP_CAT_FUNCIONES.PROCESO_ID')
                                   ->select('JP_CAT_FUNCIONES.PROCESO_ID','JP_CAT_PROCESOS.PROCESO_DESC','JP_CAT_FUNCIONES.FUNCION_ID','JP_CAT_FUNCIONES.FUNCION_DESC','JP_CAT_FUNCIONES.FUNCION_STATUS','JP_CAT_FUNCIONES.FUNCION_FECREG')
                                   ->orderBy('JP_CAT_FUNCIONES.PROCESO_ID','DESC')
                                   ->paginate(15);
        if($regfuncion->count() <= 0){
            toastr()->error('No existen registros de funciones de Procesos dados de alta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaFuncion');
        }
        return view('sicinar.catalogos.verFuncion',compact('nombre','usuario','estructura','id_estructura','regfuncion'));

    }

    public function actionEditarFuncion($id){
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

        //echo 'Ya entre a editar funcion..........';
        //$regproceso = regProcesoModel::select('PROCESO_ID','PROCESO_DESC','PROCESO_STATUS','PROCESO_FECREG')
        //    ->where('PROCESO_ID',$proceso_id)->get();
        $regproceso = regProcesoModel::select('PROCESO_ID','PROCESO_DESC')->get();        
        //if($regproceso->count() <= 0){
        //    toastr()->error('No existen registros en el catalogo de procesos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            ////return redirect()->route('verFuncion');
        //}        

        //$regfuncion = regFuncionModel::select('PROCESO_ID','FUNCION_ID','FUNCION_DESC','FUNCION_STATUS','FUNCION_FECREG')->where('FUNCION_ID',$funcion_id)->first();
        $regfuncion = regFuncionModel::select('PROCESO_ID','FUNCION_ID','FUNCION_DESC','FUNCION_STATUS','FUNCION_FECREG')
                                     ->where('FUNCION_ID',$id)->first();

        //$regfuncion = regFuncionModel::join('JP_CAT_PROCESOS','JP_CAT_PROCESOS.PROCESO_ID','=','JP_CAT_FUNCIONES.PROCESO_ID')
        //                    ->select('JP_CAT_FUNCIONES.PROCESO_ID','JP_CAT_PROCESOS.PROCESO_DESC','JP_CAT_FUNCIONES.FUNCION_ID','JP_CAT_FUNCIONES.FUNCION_DESC','JP_CAT_FUNCIONES.FUNCION_STATUS','JP_CAT_FUNCIONES.FUNCION_FECREG')
        //                    ->where('FUNCION_ID',$id)
        //                    ->orderBy('JP_CAT_FUNCIONES.PROCESO_ID','JP_CAT_FUNCIONES.FUNCION_ID','DESC')
        //                    ->get();

        if($regfuncion->count() <= 0){
            toastr()->error('No existe registros de funciones de procesos dados de alta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verFuncion');
        }

        return view('sicinar.catalogos.editarFuncion',compact('nombre','usuario','estructura','id_estructura','regproceso','regfuncion'));
        //return view('sicinar.catalogos.editarFuncion',compact('nombre','usuario','estructura','id_estructura','regfuncion'));

    }

    public function actionActualizarFuncion(catalogosfuncionRequest $request, $id){
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

        $regfuncion = regFuncionModel::where('FUNCION_ID',$id);
        if($regfuncion->count() <= 0)
            toastr()->error('No existe funcion del modelado de proceso.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regfuncion = regFuncionModel::where('FUNCION_ID',$id)        
                ->update([
                'FUNCION_DESC' => strtoupper($request->funcion_desc),
                'FUNCION_STATUS' => $request->funcion_status
                ]);
            toastr()->success('Funcion del proceso ha sido actualizado correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verFuncion');
        //return view('sicinar.catalogos.verFuncion',compact('nombre','usuario','estructura','id_estructura','regfuncion'));

    }

public function actionBorrarFuncion($id){
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

        $regfuncion = regFuncionModel::select('PROCESO_ID','FUNCION_ID','FUNCION_DESC','FUNCION_STATUS','FUNCION_FECREG')
                                     ->where('FUNCION_ID',$id);
        if($regfuncion->count() <= 0)
            toastr()->error('No existe funcion del proceso.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regfuncion->delete();
            toastr()->success('La funcion del proceso ha sido eliminada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verFuncion');
    }    

    // exportar a formato catalogo de funciones de procesos a formato excel
    public function exportCatFuncionesExcel(){
        return Excel::download(new ExcelExportCatFunciones, 'Cat_Funciones_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato catalogo de funciones de procesos a formato PDF
    public function exportCatFuncionesPdf(){
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

        $regproceso = regProcesoModel::select('PROCESO_ID','PROCESO_DESC','PROCESO_STATUS','PROCESO_FECREG')
            ->orderBy('PROCESO_ID','DESC')->get();
        if($regproceso->count() <= 0){
            toastr()->error('No existen registros en el catalogo de procesos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('verProceso');
        }

        $regfuncion = regFuncionModel::join('JP_CAT_PROCESOS','JP_CAT_PROCESOS.PROCESO_ID','=','JP_CAT_FUNCIONES.PROCESO_ID')
                                   ->select('JP_CAT_FUNCIONES.PROCESO_ID','JP_CAT_PROCESOS.PROCESO_DESC','JP_CAT_FUNCIONES.FUNCION_ID','JP_CAT_FUNCIONES.FUNCION_DESC','JP_CAT_FUNCIONES.FUNCION_STATUS','JP_CAT_FUNCIONES.FUNCION_FECREG')
                                   ->orderBy('JP_CAT_FUNCIONES.PROCESO_ID','DESC')
                                   ->orderBy('JP_CAT_FUNCIONES.FUNCION_ID','DESC')
                                   ->get();
        if($regfuncion->count() <= 0){
            toastr()->error('No existen registros en el catalogo de funciones de procesos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verFuncion');
        }
        $pdf = PDF::loadView('sicinar.pdf.catfuncionesPDF', compact('nombre','usuario','estructura','id_estructura','regfuncion','regproceso'));
        $pdf->setPaper('A4', 'landscape');        
        return $pdf->stream('CatalogoDeFuncionesDeProcesos');
    }

}
