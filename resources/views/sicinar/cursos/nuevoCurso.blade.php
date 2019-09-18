@extends('sicinar.principal')

@section('title','Registro de cursos')

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
                <small>IAPS - Cursos</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header"><h3 class="box-title">Registrar curso</h3></div> 
                        {!! Form::open(['route' => 'AltaNuevoCurso', 'method' => 'POST','id' => 'nuevoCurso', 'enctype' => 'multipart/form-data']) !!}
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
                                    <label >Periodo fiscal </label>
                                    <select class="form-control m-bot15" name="periodo_id" id="periodo_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo fiscal</option>
                                        @foreach($regperiodos as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id" id="mes_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes a impartir el curso</option>
                                        @foreach($regmeses as $mes)
                                            <option value="{{$mes->mes_id}}">{{$mes->mes_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                                 
                            </div>                            

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Nombre del curso  </label>
                                    <input type="text" class="form-control" name="curso_desc" id="curso_desc" placeholder="Digitar nombre del curso" onkeypress="return soloAlfa(event)" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Objetivo del curso  </label>
                                    <input type="text" class="form-control" name="curso_obj" id="curso_obj" placeholder="Digitar el objetivo del curso" onkeypress="return soloAlfa(event)" required>
                                </div>                                
                            </div>                            

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >Costo ($) </label>
                                    <input type="text" class="form-control" name="curso_costo" id="curso_costo" placeholder="Costo del curso" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Número de horas </label>
                                    <input type="text" class="form-control" name="curso_thoras" id="curso_thoras" placeholder="Número de horas" required>
                                </div>           
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>Fecha de inicio (dd/mm/aaaa)</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" class="form-control pull-right" id="datepicker1"  name="curso_finicio" placeholder="Fecha de inicio (dd/mm/aaaa)" required>
                                    </div>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label>Fecha de término (dd/mm/aaaa)</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" class="form-control pull-right" id="datepicker1"  name="curso_ffin" placeholder="Fecha de termino (dd/mm/aaaa)" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">                               
                                <div class="col-md-12 offset-md-5">
                                    <label >Observaciones </label>
                                    <input type="text" class="form-control" name="curso_obs" id="curso_obs" placeholder="Observaciones relevantes del curso" required>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar el curso ',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verCursos')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar curso</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\cursosRequest','#nuevoCurso') !!}
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