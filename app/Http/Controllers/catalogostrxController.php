<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\catalogostrxRequest;
use App\regTrxModel;
// Exportar a excel 
use App\Exports\ExcelExportCatTrx;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;

class catalogostrxController extends Controller
{
    public function actionNuevaTrx(){
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

        $regtrx = regTrxModel::select('TRX_ID','TRX_DESC', 'TRX_STATUS','TRX_FECREG')
            ->orderBy('TRX_ID','asc')->get();
        //dd($unidades);
        return view('sicinar.catalogos.nuevaTrx',compact('regtrx','nombre','usuario','estructura','id_estructura'));
    }

    public function actionAltaNuevaTrx(Request $request){
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
        $trx_id = regTrxModel::max('TRX_ID');
        $trx_id = $trx_id+1;
        /* ALTA DEl proceso ****************************/
        $nuevaActividad = new regTrxModel();
        $nuevaActividad->TRX_ID   = $trx_id;
        $nuevaActividad->TRX_DESC = $request->trx_desc;
        $nuevaActividad->save();

        if($nuevaActividad->save() == true){
            toastr()->success('La actividad ha sido dado de alta correctamente.','Actividad dada de alta!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoProceso');
            //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','estructura','id_estructura','rango','preguntas','apartados'));
        }else{
            toastr()->error('Error inesperado al dar de alta la actividad. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //return back();
            //return redirect()->route('nuevoProceso');
        }
        return redirect()->route('verTrx');
    }

    
    public function actionVerTrx(){
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

        $regtrx = regTrxModel::select('TRX_ID','TRX_DESC', 'TRX_STATUS','TRX_FECREG')
            ->orderBy('TRX_ID','ASC')
            ->paginate(15);
        if($regtrx->count() <= 0){
            toastr()->error('No existen registro de Actividades dadas de alta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaTrx');
        }
        return view('sicinar.catalogos.verTrx',compact('nombre','usuario','estructura','id_estructura','regtrx'));

    }

    public function actionEditarTrx($id){
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

        $regtrx = regTrxModel::select('TRX_ID','TRX_DESC','TRX_STATUS','TRX_FECREG')
            ->where('TRX_ID',$id)
            ->orderBy('TRX_ID','ASC')
            ->first();
        if($regtrx->count() <= 0){
            toastr()->error('No existe registros de actividades dadas de alta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevoProceso');
        }
        return view('sicinar.catalogos.editarTrx',compact('nombre','usuario','estructura','id_estructura','regtrx'));

    }

    public function actionActualizarTrx(catalogostrxRequest $request, $id){
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

        $regtrx = regTrxModel::where('TRX_ID',$id);
        if($regtrx->count() <= 0)
            toastr()->error('No existe actividad.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regtrx = regTrxModel::where('TRX_ID',$id)        
            ->update([
                'TRX_DESC' => strtoupper($request->trx_desc),
                'TRX_STATUS' => $request->trx_status
            ]);
            toastr()->success('Actividad actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verTrx');
        //return view('sicinar.catalogos.verProceso',compact('nombre','usuario','estructura','id_estructura','regproceso'));

    }

public function actionBorrarTrx($id){
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

        $regtrx = regTrxModel::select('TRX_ID','TRX_DESC', 'TRX_STATUS','TRX_FECREG')->where('TRX_ID',$id);
        //                     ->find('TRX_ID',$id);
        if($regtrx->count() <= 0)
            toastr()->error('No existe actividad.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regtrx->delete();
            toastr()->success('Actividad ha sido eliminada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verTrx');
    }    

    // exportar a formato catalogo de actividades a formato excel
    public function exportCatTrxExcel(){
        return Excel::download(new ExcelExportCatTrx, 'Cat_Actividades_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato catalogo de actividades a formato PDF
    public function exportCatTrxPdf(){
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

        $regtrx = regTrxModel::select('TRX_ID','TRX_DESC','TRX_STATUS','TRX_FECREG')
            ->orderBy('TRX_ID','ASC')->get();
        if($regtrx->count() <= 0){
            toastr()->error('No existen registros en el catalogo de actividades.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verTrx');
        }
        $pdf = PDF::loadView('sicinar.pdf.cattrxPDF', compact('nombre','usuario','estructura','id_estructura','regtrx'));
        $pdf->setPaper('A4', 'landscape');        
        return $pdf->stream('CatalogoDeActividades');
    }

}
