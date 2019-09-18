@extends('sicinar.principal')

@section('title','Nueva IAP')

@section('links')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('nombre')
    {{$nombre}}
@endsection

@section('usuario')
    {{$usuario}}
@endsection

@section('estructura')
    {{$estructura}}
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Menú
                <small> IAPS - Nueva</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header"><h3 class="box-title">Registrar nueva IAP</h3></div>
                        {!! Form::open(['route' => 'AltaNuevaIap', 'method' => 'POST','id' => 'nuevaIap', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Nombre de la IAP </label>
                                    <input type="text" class="form-control" name="iap_desc" id="iap_desc" placeholder="Digitar nombre de la IAP" onkeypress="return soloAlfa(event)" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >RFC </label>
                                    <input type="text" class="form-control" name="iap_rfc" id="iap_rfc" placeholder="* RFC de la IAP" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Rubro asistencial </label>
                                    <select class="form-control m-bot15" name="rubro_id" id="rubro_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar rubro asistencial</option>
                                        @foreach($regrubro as $rubro)
                                            <option value="{{$rubro->rubro_id}}">{{$rubro->rubro_id}} - {{$rubro->rubro_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                               
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Teléfono </label>
                                    <input type="text" class="form-control" name="iap_telefono" id="iap_telefono" placeholder="* Teléfono" required>
                                </div>                                
                                <div class="col-xs-4 form-group">
                                    <label >Registro de constitución </label>
                                    <input type="text" class="form-control" name="iap_regcons" id="iap_regcons" placeholder="* Registro de constitución de la IAP" required>
                                </div>

                                <div class="col-xs-4 form-group">
                                    <label>Fecha de constitución:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" class="form-control pull-right" id="datepicker1"  name="iap_feccons" placeholder="Fecha de constitución (dd/mm/aaaa)" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Calle </label>
                                    <input type="text" class="form-control" name="iap_calle" id="iap_calle" placeholder="* Calle de la IAP" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Número ext./int.</label>
                                    <input type="text" class="form-control" name="iap_num" id="iap_num" placeholder="* Número exterior y/o interior" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Colonia </label>
                                    <input type="text" class="form-control" name="iap_colonia" id="iap_colonia" placeholder="* Calle de la IAP" required>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Código postal </label>
                                    <input type="text" class="form-control" name="iap_cp" id="iap_cp" placeholder="* Código postal" required>
                                </div>                                                                
                                <div class="col-xs-4 form-group">
                                    <label >Entidad federativa</label>
                                    <select class="form-control m-bot15" name="entidadfederativa_id" id="entidadfederativa_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar entidad federativa</option>
                                        @foreach($regentidades as $estado)
                                            <option value="{{$estado->entidadfederativa_id}}">{{$estado->entidadfederativa_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Municipio</label>
                                    <select class="form-control m-bot15" name="municipio_id" id="municipio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar municipio</option>
                                        @foreach($regmunicipio as $municipio)
                                            <option value="{{$municipio->municipioid}}">{{$municipio->entidadfederativa_desc.'-'.$municipio->municipionombre}}
                                            </option>
                                        @endforeach
                                    </select>                                  
                                </div>                                                                    
                            </div>

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >Correo electrónico </label>
                                    <input type="text" class="form-control" name="iap_email" id="iap_email" placeholder="* Código postal" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Sitio web </label>
                                    <input type="text" class="form-control" name="iap_sweb" id="iap_sweb" placeholder="* Sitio web o pagina electrónica" required>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Presidente </label>
                                    <input type="text" class="form-control" name="iap_pres" id="iap_pres" placeholder="* Presidente" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Representante legal </label>
                                    <input type="text" class="form-control" name="iap_replegal" id="iap_replegal" placeholder="* Representante legal" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Secretario </label>
                                    <input type="text" class="form-control" name="iap_srio" id="iap_srio" placeholder="* Secretario" required>
                                </div>                                                          
                                <div class="col-xs-4 form-group">
                                    <label >Tesorero </label>
                                    <input type="text" class="form-control" name="iap_tesorero" id="iap_tesorero" placeholder="* Tesorero" required>
                                </div>   
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Objeto social </label>
                                    <textarea class="form-control" name="iap_objsoc" id="iap_objsoc" rows="6" cols="120" placeholder="* Objeto social de la IAP" required>
                                    </textarea>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de foto 1 </label>
                                    <input type="file" class="text-md-center" style="color:red" name="iap_foto1" id="iap_foto1" placeholder="Subir archivo de fotografía 1" >
                                </div>                                                          
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de foto 2 </label>
                                    <input type="file" class="text-md-center" style="color:red" name="iap_foto2" id="iap_foto2" placeholder="Subir archivo de fotografía 2" >
                                </div>   
                            </div>

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Dar de alta',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verIap')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
                                </div>                                
                            </div>                            

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\iapsRequest','#nuevaIap') !!}
@endsection

@section('javascrpt')
<script>
  function soloAlfa(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ.";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }

    function general(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890,.;:-_<>!%()=?¡¿/*+";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }
</script>
@endsection