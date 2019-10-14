<?php

namespace App\Http\Controllers;

use App\Http\Requests\usuarioRequest;
use App\Http\Requests\altaUsuarioRequest;
use Illuminate\Http\Request;
use App\usuarioModel;
use App\estructurasModel;
use App\dependenciasModel;
use App\regIapModel;
use App\regBitacoraModel;

class usuariosController extends Controller
{

    public function actionLogin(usuarioRequest $request){
    	//dd($request->all());
        $existe = usuarioModel::select('LOGIN','PASSWORD','TIPO_USUARIO','ESTRUCGOB_ID','CVE_DEPENDENCIA','CVE_ARBOL','STATUS_1')
            ->where('LOGIN','like','%'.$request->usuario.'%')
            ->where('PASSWORD','like','%'.$request->password.'%')
            ->where('STATUS_2',1)
            ->get();
    	//dd($existe);
        if($existe->count()>=1){
            //dd('Entra if.');
    	    if(strcmp($existe[0]->login,$request->usuario) == 0){
    	        if(strcmp($existe[0]->password,$request->password) == 0){
                    //dd('Entro.');
                }else{
                    return back()->withInput()->withErrors(['PASSWORD' => 'Contraseña incorrecta.']);
                }
            }else{
                return back()->withInput()->withErrors(['LOGIN' => 'Usuario -'.$request->usuario.'- incorrecto.']);
            }
        }

        $estructura        ="";
        $dependencia       ="";
        $nombre_dependencia="";
        if($existe->count()>=1){
            //$estruc = estructurasModel::ObtEstruc($existe[0]->estrucgob_id);            
            //******** Obtener la dependencia ***********
            $dep = dependenciasModel::select('DEPEN_DESC')
                    ->where('DEPEN_ID','like',$existe[0]->cve_dependencia.'%')->get();
            if($dep->count()<1){
                //$dependencia = $existe[0]->cve_dependencia;
                $nombre_dependencia = "Particular";
            }else{
                $nombre_dependencia = $dep[0]->depen_desc;
            }

            //****************** Obtener la IAP ****************
            $estruc = regIapModel::ObtIap($existe[0]->cve_arbol);
            if($estruc->count()<1){
                //$estructura = $existe[0]->cve_arbol;
                $estructura = 0;
            }else{
                $estructura = $estruc[0]->iap_id;
            }
            //***************************************************

    		if($existe[0]->status_1 == '4'){  //Super administrador
    			$usuario           = "SuperAdministrador";
    			//$estructura        = $estruc[0]->iap_id;
                $id_estructura     = $existe[0]->estrucgob_id;
                $dependencia       = $existe[0]->cve_dependencia;
                //$nombre_dependencia= "Particular";
    		}else{
                if($existe[0]->status_1 == '3'){ //Administrador
                    $usuario           = "Administrador";
                    //$estructura        = $estruc[0]->iap_id;
                    $id_estructura     = $existe[0]->estrucgob_id;
                    $dependencia       = $existe[0]->cve_dependencia;
                    //$nombre_dependencia= "Particular";
                }else{
                    if($existe[0]->status_1 == '2'){ //Particular
                        $usuario            = "Particular";
                        //$estructura         = $estruc[0]->iap_id;
                        $id_estructura      = $existe[0]->estrucgob_id;
                        $dependencia        = $existe[0]->cve_dependencia;
                        //$nombre_dependencia = "Particular";
                    }else{
                        if($existe[0]->status_1 == '1'){ //operativo UNIDADES ADMINISTRATIVAS
                            $usuario       = "Operativo";
                            //$estructura    = $estruc[0]->iap_id;
                            $id_estructura = $existe[0]->estrucgob_id;
                            $dependencia   = $existe[0]->cve_dependencia;
                        }else{
                            return back()->withInput()->withErrors(['LOGIN' => 'Usuario o password incorrecto.']);
                        }
                    }
                }
    		}
    		$nombre = $request->usuario;
            $rango = $existe[0]->status_1;
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
    		session(['userlog' => $request->usuario,'passlog' => $request->password,'usuario' => $usuario,'estructura' => $estructura, 'ip' => $ip, 'rango' => $rango, 'id_estructura' => $id_estructura, 'dependencia' => $dependencia,'nombre_dependencia'=>$nombre_dependencia]);
            //dd('Usuario: '.$usuario.' - Rango: '.$rango.' - Estructura: '.$estructura.'- Dependencia: '.$dependencia.' - Nombre dependencia: '.$nombre_dependencia);
    		toastr()->info($nombre,'Bienvenido ');
    		return view('sicinar.menu.menuInicio',compact('usuario','nombre','estructura','rango'));
    	}else{
    		return back()->withInput()->withErrors(['LOGIN' => 'El usuario no esta dado de alta.']);
    	}
    }

    public function actionCerrarSesion(){
        session()->forget('userlog');
        session()->forget('passlog');
        session()->forget('usuario','estructura','ip','rango','id_estructura','plan_id');
        //session()->forget('userlog','passlog','usuario','estructura','ip','rango','id_estructura','plan_id');
        //REGRESA AL LOGIN PRINCIPAL
        //return view('sicinar.login.terminada');
        return view('sicinar.login.loginInicio');
    }

    public function actionExpirada(){
    	return view('sicinar.login.expirada');
    }

    public function actionNuevoUsuario(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $estructura   = session()->get('estructura');
        //$id_estruc  = session()->get('id_estructura');
        //$id_estructura = rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        //$ip = session()->get('ip');
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','DESC')
                        ->get();
        $dependencias = dependenciasModel::select('DEPEN_ID','DEPEN_DESC')->orderBy('DEPEN_ID','DESC')
                        ->get();
        return view('sicinar.BackOffice.nuevoUsuario',compact('nombre','usuario','estructura','rango','dependencias','regiap'));
    }

    public function actionAltaUsuario(altaUsuarioRequest $request){
        //dd($request->all());
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario    = session()->get('usuario');
        $estructura = session()->get('estructura');
        //$id_estruc= session()->get('id_estructura');
        //$id_estructura = rtrim($id_estruc," ");
        $rango      = session()->get('rango');
        $ip         = session()->get('ip');

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

        if($request->perfil == '4')
            $tp = 'SA';
        else
            if($request->perfil == '3')
                $tp = 'AD';
            else
                if($request->perfil == '2')
                    $tp = 'EJ';
                else
                    if($request->perfil == '1')
                        $tp = 'CA';
                    else
                        $tp = 'PG';

        //if($request->perfil == '1' AND $request->unidad == '0'){
        //    return back()->withErrors(['unidad' => 'No puedes elegir la Unidad Administrativa: ADMINISTRADOR si tiene rol OPERATIVO.']);
        //}
        //dd($request->all());
        $folio        = usuarioModel::max('FOLIO');
        $folio        = $folio+1;
        $nuevoUsuario = new usuarioModel();
        $nuevoUsuario->N_PERIODO = date('Y');
        $nuevoUsuario->FOLIO     = $folio;
        //$nuevoUsuario->ESTRUCGOB_ID    = '21500';
        $nuevoUsuario->CVE_DEPENDENCIA = $request->unidad;
        $nuevoUsuario->CVE_ARBOL  = $request->iap_id;
        $nuevoUsuario->LOGIN      = strtolower($request->usuario);
        $nuevoUsuario->PASSWORD   = $request->password;
        $nuevoUsuario->AP_PATERNO = strtoupper($request->paterno);
        $nuevoUsuario->AP_MATERNO = strtoupper($request->materno);
        $nuevoUsuario->NOMBRES    = strtoupper($request->nombre);
        $nuevoUsuario->NOMBRE_COMPLETO = strtoupper($request->nombre.' '.$request->paterno.' '.$request->materno);
        $nuevoUsuario->TIPO_USUARIO = $tp;
        $nuevoUsuario->STATUS_1 = $request->perfil;
        $nuevoUsuario->STATUS_2 = 1;
        $nuevoUsuario->EMAIL    = strtolower($request->usuario);
        $nuevoUsuario->IP       = $ip;
        $nuevoUsuario->FECHA_REGISTRO = date('Y/m/d');
        if($nuevoUsuario->save() == true){
            toastr()->success('El Usuario ha sido creado correctamente.','Ok!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('verUsuarios');
        }else{
            toastr()->error('El Usuario no ha sido creado.','Ha ocurrido algo inesperado!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('altaUsuario');
        }

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         7;
        $xfuncion_id  =      7004;
        $xtrx_id      =        99;    //Alta

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $folio])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $folio;          // Folio    
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
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $folio])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         

            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $folio])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/ 
        return redirect()->route('verUsuarios');
    }

    public function actionVerUsuario(){
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario    = session()->get('usuario');
        $estructura = session()->get('estructura');
        //$id_estruc = session()->get('id_estructura');
        //$id_estructura = rtrim($id_estruc," ");
        $rango      = session()->get('rango');
        //$ip = session()->get('ip');

        $regiap       = regIapModel::select('IAP_ID','IAP_DESC','IAP_STATUS')->get();            
        $dependencias = dependenciasModel::select('DEPEN_ID','DEPEN_DESC')->get();
        $usuarios   = usuarioModel::select('FOLIO','NOMBRE_COMPLETO','EMAIL','CVE_DEPENDENCIA','CVE_ARBOL','LOGIN','PASSWORD','STATUS_1','STATUS_2')
            ->orderBy('STATUS_1','DESC')
            ->orderBy('FOLIO','ASC')
            ->paginate(15);        
        //dd($usuarios->all());
        return view('sicinar.BackOffice.verUsuarios',compact('nombre','usuario','estructura','rango','usuarios','dependencias','regiap'));
        //dd($usuarios->all());
    }

    public function actionEditarUsuario($id){
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario    = session()->get('usuario');
        $estructura = session()->get('estructura');
        //$id_estruc = session()->get('id_estructura');
        //$id_estructura = rtrim($id_estruc," ");
        $rango      = session()->get('rango');
            //            ->where('ESTRUCGOB_ID','like','%21500%')
            //->where('CLASIFICGOB_ID','=',1)
        $dependencias= dependenciasModel::select('DEPEN_ID','DEPEN_DESC')
                       ->orderBy('DEPEN_ID','ASC')
                       ->get();
        $regiap      = regIapModel::select('IAP_ID','IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','ASC')->get();            
        $user        = usuarioModel::select('FOLIO','NOMBRES','AP_PATERNO','AP_MATERNO','LOGIN','PASSWORD','STATUS_1','STATUS_2','EMAIL','CVE_DEPENDENCIA','CVE_ARBOL')
            ->where('FOLIO',$id)
            ->first();        
        return view('sicinar.BackOffice.editarUsuario',compact('nombre','usuario','estructura','rango','user','dependencias','regiap'));
    }

    public function actionActualizarUsuario(altaUsuarioRequest $request, $id){
        //dd($request->all());
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
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
        $xfuncion_id  =      7004;
        $xtrx_id      =       100;    //Actualizar 

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

        if($request->perfil == '4')
            $tp = 'SA';
        else
            if($request->perfil == '3')
                $tp = 'AD';
            else
                if($request->perfil == '2')
                    $tp = 'EJ';
                else
                    if($request->perfil == '1')
                        $tp = 'CA';
                    else
                        $tp = 'PG';
        $actUser = usuarioModel::where('FOLIO',$id)
            ->update([
                'CVE_DEPENDENCIA' => $request->unidad,
                'CVE_ARBOL'       => $request->iap_id,
                'LOGIN'           => strtolower($request->usuario),
                'PASSWORD'        => $request->password,
                'AP_PATERNO'      => strtoupper($request->paterno),
                'AP_MATERNO'      => strtoupper($request->materno),
                'NOMBRES'         => strtoupper($request->nombre),
                'NOMBRE_COMPLETO' => strtoupper($request->nombre.' '.$request->paterno.' '.$request->materno),
                'EMAIL'           => strtolower($request->usuario),
                'TIPO_USUARIO'    => $tp,
                'STATUS_1'        => $request->perfil,
                'STATUS_2'        => $request->status_2
            ]);
        toastr()->success('El Usuario ha sido actualizado correctamente.','Ok!',['positionClass' => 'toast-bottom-right']);
        return redirect()->route('verUsuarios');

    }

    public function actionBorrarUsuario($id){
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
        /************ Elimina el usuario **************************************/
        $usuarios=usuarioModel::select('FOLIO','NOMBRE_COMPLETO','EMAIL','CVE_DEPENDENCIA','LOGIN','PASSWORD','STATUS_1','STATUS_2')->where('FOLIO',$id);
        if($usuarios->count() <= 0)
            toastr()->error('No existe el usuario.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $usuarios->delete();
            toastr()->success('Usuario ha sido eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************* Termina de eliminar  el usuario **********************************/
        return redirect()->route('verUsuarios');
    }    


    public function actionActivarUsuario($id){
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $activar = usuarioModel::where('FOLIO',$id)
            ->update([
                'STATUS_2' => '1'
            ]);
        return redirect()->route('verUsuarios');
    }

    public function actionDesactivarUsuario($id){
        $nombre = session()->get('userlog');
        $pass   = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $activar = usuarioModel::where('FOLIO',$id)
            ->update([
                'STATUS_2' => '0'
            ]);
        return redirect()->route('verUsuarios');
    }
}
