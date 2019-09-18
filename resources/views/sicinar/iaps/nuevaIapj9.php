@extends('sicinar.principal')

@section('title','Registro de Información de datos juridicos')

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
                <small>IAPS - Información de datos jurídicos </small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header"><h3 class="box-title">Registrar información de datos jurídicos </h3></div> 
                        {!! Form::open(['route' => 'AltaNuevaIapj', 'method' => 'POST','id' => 'nuevaIapj', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >IAP </label>
                                    <select class="form-control m-bot15" name="iap_id" id="iap_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar IAP</option>
                                        @foreach($regiap as $iap)
                                            <option value="{{$iap->iap_id}}">{{$iap->iap_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                 
                            </div>     

                            <div class="row">   
                                <div class="col-xs-4 form-group">  
                                    <label>¿Está registrado en el registro público de la propiedad? </label>
                                    <select class="form-control m-bot15" name="iap_rpp" id="iap_rpp" required>
                                        <option value="S">         Si</option>
                                        <option value="N" selected>No</option>
                                    </select>                                                            
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label>Fecha de vencimiento del Patronato (dd/mm/aaaa) </label>
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" class="form-control pull-right" id="datepicker1"  name="iap_fvp" placeholder="Fecha de vencimiento del Patronato (dd/mm/aaaa)" value="{!! date('d/m/Y',strtotime($regiapjuridico->iap_fvp)) !!}" required>
                                    </div>
                                </div>                                                            
                            </div>

                            <div class="row">                                                                    
                                <div class="col-xs-4 form-group">
                                    <label >Vigencia del patronato en años </label>
                                    <select class="form-control m-bot15" name="anio_id" id="anio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar vigencia del patronato</option>
                                        @foreach($regvigencia as $vigencia)
                                            <option value="{{$vigencia->anio_id}}">{{$vigencia->anio_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Situación del inmueble </label>
                                    <select class="form-control m-bot15" name="inm_id" id="inm_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar Situación del inmueble</option>
                                        @foreach($reginmuebles as $inmueble)
                                            <option value="{{$inmueble->inm_id}}">{{$inmueble->inm_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                
                            </div>     

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de acta constitutiva en formato PDF </label>
                                    <input type="file" class="text-md-center" style="color:red" name="iap_act_const" id="iap_act_const" placeholder="Subir archivo de acta constitutiva en formato PDF">
                                </div>   
                            </div>

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de RFC de SAT en formato PDF </label>
                                    <input type="file" class="text-md-center" style="color:red" name="iap_rfc" id="iap_rfc" placeholder="Subir archivo de RFC de SAT en formato PDF">
                                </div>   
                            </div>                            

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar la información de datos juridicos de la IAP',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verIapj')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\iapsjuridicoRequest','#nuevaIapj') !!}
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