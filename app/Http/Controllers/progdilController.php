<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\progdilRequest;

use App\regBitacoraModel;
use App\regIapModel;
use App\regPerModel;
use App\regMesesModel;
use App\regDiasModel;
use App\regHorasModel;
use App\regPfiscalesModel;
use App\regMunicipioModel;
use App\regEntidadesModel;

//use App\regRubroModel;
//use App\regFormatosModel;
use App\regDoctosModel;

use App\regAgendaModel;
// Exportar a excel 
//use App\Exports\ExcelExportCatIAPS;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class progdilController extends Controller
{

    public function actionBuscarProgdil(Request $request)
    {
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
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();   
        $reghoras     = regHorasModel::select('HORA_ID','HORA_DESC')->get();   
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();   
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();        
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->get();       
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//
        //**************************************************************//
        $fper  = $request->get('fper');   
        $fmes  = $request->get('fmes');  
        $fdia  = $request->get('fdia');    
        $fiap  = $request->get('fiap');            
        $regprogdil = regAgendaModel::orderBy('VISITA_FOLIO', 'ASC')
                      ->fper($fper)    //Metodos personalizados es equivalente a ->where('IAP_DESC', 'LIKE', "%$name%");
                      ->fmes($fmes)    //Metodos personalizados
                      //->fdia($fdia)    //Metodos personalizados
                      ->fiap($fiap)    //Metodos personalizados
                      ->paginate(50);
        if($regprogdil->count() <= 0){
            toastr()->error('No existen registros programados en la agenda.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }            
        return view('sicinar.agenda.verProgdil', compact('nombre','usuario','estructura','id_estructura','regiap','regperiodos', 'regdias', 'reghoras','regmeses','regprogdil','regentidades','regmunicipio'));
    }


    public function actionVerProgdil(){
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
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();   
        $reghoras     = regHorasModel::select('HORA_ID','HORA_DESC')->get();   
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();   
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();        
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->get();       
        $regprogdil   = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','IAP_ID',
                                             'MUNICIPIO_ID',
                                             'VISITA_CONTACTO','VISITA_TEL','VISITA_EMAIL','VISITA_DOM','VISITA_OBJ',
                                             'VISITA_SPUB','VISITA_SPUB2','VISITA_FECREGP','VISITA_EDO',
                                             'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                    ->orderBy('VISITA_FOLIO','ASC')                    
                    ->paginate(30);
        if($regprogdil->count() <= 0){
            toastr()->error('No existen registros de programación de visistas de diligencias.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.agenda.verprogdil',compact('nombre','usuario','estructura','id_estructura','regmeses', 'reghoras','regdias','regperiodos','regiap','regprogdil','regentidades','regmunicipio'));

    }

public function actionNuevoProgdil(){
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
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();   
        $reghoras     = regHorasModel::select('HORA_ID','HORA_DESC')->get();   
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();   
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();        
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->get();       
        $regprogdil   = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','IAP_ID', 
                                             'MUNICIPIO_ID',
                                             'VISITA_CONTACTO','VISITA_TEL','VISITA_EMAIL','VISITA_DOM','VISITA_OBJ',
                                             'VISITA_SPUB','VISITA_SPUB2','VISITA_FECREGP','VISITA_EDO',
                                             'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')->orderBy('VISITA_FOLIO','asc')
                     ->get();
        //dd($unidades);
        return view('sicinar.agenda.nuevoProgdil',compact('regmeses','reghoras','regdias','regperiodos','regiap','regprogdil','regmunicipio','regEntidadesModel','nombre','usuario','estructura','id_estructura'));
    }

    public function actionAltaNuevoProgdil(Request $request){
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

        /************ Alta *****************************/ 
        $mes = regMesesModel::ObtMes($request->mes_id);
        $dia = regDiasModel::ObtDia($request->dia_id);

        $visita_folio = regAgendaModel::max('VISITA_FOLIO');
        $visita_folio = $visita_folio+1;

        $nuevoprogdil = new regAgendaModel();

        $nuevoprogdil->VISITA_FOLIO   = $visita_folio;
        $nuevoprogdil->VISITA_OBJ     = strtoupper($request->visita_obj);
        $nuevoprogdil->VISITA_CONTACTO= strtoupper($request->visita_contacto);
        $nuevoprogdil->VISITA_TEL     = strtoupper($request->visita_tel);
        $nuevoprogdil->VISITA_DOM     = strtoupper($request->visita_dom);
        $nuevoprogdil->VISITA_EMAIL   = strtolower($request->visita_email);
        $nuevoprogdil->VISITA_SPUB    = strtoupper($request->visita_spub);
        $nuevoprogdil->VISITA_SPUB2   = strtoupper($request->visita_spub2);

        $nuevoprogdil->IAP_ID         = $request->iap_id;
        $nuevoprogdil->PERIODO_ID     = $request->periodo_id;
        $nuevoprogdil->MES_ID         = $request->mes_id;
        $nuevoprogdil->DIA_ID         = $request->dia_id;
        $nuevoprogdil->HORA_ID        = $request->hora_id;
        $nuevoprogdil->MUNICIPIO_ID   = $request->municipio_id;

        $nuevoprogdil->VISITA_FECREGP = trim($dia[0]->dia_desc.'/'.$mes[0]->mes_mes.'/'.$request->periodo_id);

        $nuevoprogdil->IP          = $ip;
        $nuevoprogdil->LOGIN       = $nombre;         // Usuario ;
        $nuevoprogdil->save();

        if($nuevoprogdil->save() == true){
            toastr()->success('Diligencia ha sido programada en la agenda correctamente.','Diligencia dada de alta!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoDocto');
            //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','estructura','id_estructura','rango','preguntas','apartados'));
        }else{
            toastr()->error('Error inesperado al dar de alta en la agenda la diligencia. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //return back();
            //return redirect()->route('nuevoDocto');
        }

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =        10;
        $xfuncion_id  =     10001;
        $xtrx_id      =       180;    //Alta 

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                         'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $visita_folio])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $visita_folio;         // Folio    
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
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $visita_folio])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         

            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $visita_folio])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M'     => $regbitacora->IP       = $ip,
                'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/ 
        return redirect()->route('verProgdil');
    }

    public function actionEditarProgdil($id){
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
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();   
        $reghoras     = regHorasModel::select('HORA_ID','HORA_DESC')->get();   
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();   
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();        
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->get();       
        $regprogdil   = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','IAP_ID',
                                             'MUNICIPIO_ID',
                                             'VISITA_CONTACTO','VISITA_TEL','VISITA_EMAIL','VISITA_DOM','VISITA_OBJ',
                                             'VISITA_SPUB','VISITA_SPUB2','VISITA_FECREGP','VISITA_EDO',
                                             'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('VISITA_FOLIO',$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('MES_ID','ASC')
                        ->orderBy('DIA_ID','ASC')
                        ->orderBY('HORA_ID','ASC')
                        ->first();
        if($regprogdil->count() <= 0){
            toastr()->error('No existen registros en la agenda.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoProgdil');
        }
        return view('sicinar.agenda.editarProgdil',compact('nombre','usuario','estructura','id_estructura','regmeses','reghoras','regdias','regiap','regperiodos','regprogdil','regentidades','regmunicipio'));

    }

    public function actionActualizarProgdil(progdilRequest $request, $id){
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
        $xproceso_id  =        10;
        $xfuncion_id  =     10001;
        $xtrx_id      =       181;    //Actualizar         

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
        $regprogdil   = regAgendaModel::where('VISITA_FOLIO',$id);
        if($regprogdil->count() <= 0)
            toastr()->error('No existe diligencia programada en la agenda.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //                'IAP_GEOREF_LATITUD' => $request->iap_georef_latitud, 
            $mes = regMesesModel::ObtMes($request->mes_id);
            $dia = regDiasModel::ObtDia($request->dia_id);
            $regprogdil   = regAgendaModel::where('VISITA_FOLIO',$id)        
            ->update([                
                'VISITA_OBJ'     => strtoupper($request->visita_obj),
                'VISITA_DOM'     => strtoupper($request->visita_dom),
                'VISITA_CONTACTO'=> strtoupper($request->visita_contacto),
                'VISITA_EMAIL'   => strtolower($request->visita_email),
                'VISITA_TEL'     => strtoupper($request->visita_tel),
                'VISITA_SPUB'    => strtoupper($request->visita_spub),
                'VISITA_SPUB2'   => strtoupper($request->visita_spub2),
                'IAP_ID'         => $request->iap_id,                
                'PERIODO_ID'     => $request->periodo_id,
                'MES_ID'         => $request->mes_id,
                'DIA_ID'         => $request->dia_id,
                'HORA_ID'        => $request->hora_id,
                'MUNICIPIO_ID'   => $request->municipio_id,
                'VISITA_FECREGP' => trim($dia[0]->dia_desc.'/'.$mes[0]->mes_mes.'/'.$request->periodo_id),
                
                'VISITA_EDO'     => $request->visita_edo,                
                'IP_M'           => $ip,
                'LOGIN_M'        => $nombre,
                'FECHA_M'        => date('Y/m/d')    //date('d/m/Y')                                
            ]);
            toastr()->success('Diligencia actualizada en la agenda correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verProgdil');
    }

    public function actionBorrarProgdil($id){
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
        $xproceso_id  =        10;
        $xfuncion_id  =     10001;
        $xtrx_id      =       182;     // Baja 

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
        $regprogdil = regAgendaModel::select('VISITA_FOLIO')
                      ->where('VISITA_FOLIO',$id);
        if($regprogdil->count() <= 0)
            toastr()->error('No existe diligencia en la agenda.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regprogdil->delete();
            toastr()->success('Diligencia programada en la agenda ha sido eliminada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************* Termina de eliminar  la IAP **********************************/
        return redirect()->route('verProgdil');
    }    

    // exportar a formato PDF
    public function actionMandamientoPDF($id){
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
        $xproceso_id  =        10;
        $xfuncion_id  =     10001;
        $xtrx_id      =       184;     // pdf
        $id           =       $id;

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
                           ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                           ->get();
        $regmunicipio = regMunicipioModel::join('CAT_ENTIDADES_FEDERATIVAS','CAT_ENTIDADES_FEDERATIVAS.ENTIDADFEDERATIVA_ID', '=', 'CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','CAT_ENTIDADES_FEDERATIVAS.ENTIDADFEDERATIVA_DESC','CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9,15,22])
                        ->orderBy('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();   
        $reghoras     = regHorasModel::select('HORA_ID','HORA_DESC')->get();   
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();   
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();        
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->get();       
        $regprogdil   = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','IAP_ID',
                                             'MUNICIPIO_ID',
                                             'VISITA_CONTACTO','VISITA_TEL','VISITA_EMAIL','VISITA_DOM','VISITA_OBJ',
                                             'VISITA_SPUB','VISITA_SPUB2','VISITA_FECREGP','VISITA_EDO')
                        ->where('VISITA_FOLIO',$id)
                        ->get();
        if($regprogdil->count() <= 0){
            toastr()->error('No existen diligencia programada en la agenda.','Uppss!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('verIap');
        }
        $pdf = PDF::loadView('sicinar.pdf.mandamientoPDF', compact('nombre','usuario','regmeses','reghoras','regdias','regperiodos','regiap','regprogdil','regentidades','regmunicipio'));
        //$options = new Options();
        //$options->set('defaultFont', 'Courier');
        //$pdf->set_option('defaultFont', 'Courier');
        //******** Horizontal ***************
        //$pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');
        //$pdf->set_options('isPhpEnabled', true);
        //$pdf->setOptions(['isPhpEnabled' => true]);
        //******** vertical ***************          
        $pdf->setPaper('A4','portrait');

        // Output the generated PDF to Browser
        return $pdf->stream('DocumentoDeMandamientoEscrito');
    }


}
