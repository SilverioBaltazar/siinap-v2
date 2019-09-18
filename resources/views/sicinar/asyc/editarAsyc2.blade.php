@extends('sicinar.principal')

@section('title','Editar Datos de asistencia social y contables')

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
            <h1>
                Menú
                <small> Instituciones Privadas (IAPS) - Datos de asistencia social y contables - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Editar Datos de asistencia social y contables</h3>
                        </div>
                        {!! Form::open(['route' => ['actualizarAsyc2',$regasyc->iap_folio], 'method' => 'PUT', 'id' => 'actualizarAsyc2', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>Id.: {{$regasyc->iap_folio}}</label>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >IAP </label>
                                    <select class="form-control m-bot15" name="iap_id" id="iap_id" required>
                                    <option selected="true" disabled="disabled">Seleccionar IAP </option>
                                    @foreach($regiap as $iap)
                                        @if($iap->iap_id == $regasyc->iap_id)
                                            <option value="{{$iap->iap_id}}" selected>{{$iap->iap_desc}}</option>
                                        @else 
                                            <option value="{{$iap->iap_id}}">{{$iap->iap_desc}}</option>
                                        @endif                                    
                                    @endforeach
                                    </select>                           
                                </div> 
                                <div class="col-xs-4 form-group">
                                    <label >Periodo fiscal </label>
                                    <select class="form-control m-bot15" name="periodo_id" id="periodo_id" required>
                                    <option selected="true" disabled="disabled">Seleccionar Periodo fiscal </option>
                                    @foreach($regperiodos as $periodo)
                                        @if($periodo->periodo_id == $regasyc->periodo_id)
                                            <option value="{{$periodo->periodo_id}}" selected>{{$periodo->periodo_desc}}</option>
                                        @else 
                                            <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}}</option>
                                        @endif                                    
                                    @endforeach
                                    </select>
                                </div>     
                            </div>

                            <div class="row">    
                                @if (!empty($regasyc->iap_d02)||!is_null($regasyc->iap_d02))  
                                    <div class="col-xs-4 form-group">
                                        <label >Lista de personal en formato excel</label>
                                        <label ><a href="/images/{{$regasyc->iap_d02}}" class="btn btn-danger" title="Lista de personal en formato excel"><i class="fa fa-file-excel-o"></i>Excel</a>
                                        </label>
                                    </div>   
                                    <div class="col-xs-4 form-group">
                                        <label >Actualizar archivo de Lista de personal en formato excel</label>
                                        <input type="file" class="text-md-center" style="color:red" name="iap_d02" id="iap_d02" placeholder="Subir archivo de Lista de personal en formato excel" >
                                    </div>      
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de Lista de personalen formato excel</label>
                                        <input type="file" class="text-md-center" style="color:red" name="iap_d02" id="iap_d02" placeholder="Subir archivo de Lista de personal formato excel" >
                                    </div>                                                
                                @endif       
                            </div>

                            <div class="row">                                                                 
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de entrega de información </label>
                                    <select class="form-control m-bot15" name="per02_id" id="per02_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar Periodicidad de entrega de información </option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            @if($periodicidad->per_id == $regasyc->per02_id)
                                                <option value="{{$periodicidad->per_id}}" selected>{{$periodicidad->per_desc}}</option>
                                            @else 
                                               <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega de información </label>
                                    <select class="form-control m-bot15" name="num02_id" id="num02_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar Periodo de entrega de información </option>
                                        @foreach($regnumeros as $numeros)
                                            @if($numeros->num_id == $regasyc->num02_id)
                                                <option value="{{$numeros->num_id}}" selected>{{$numeros->num_desc}}</option>
                                            @else 
                                               <option value="{{$numeros->num_id}}">{{$numeros->num_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>  
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Está actualizada la información? </label>
                                    <select class="form-control m-bot15" name="iap_edo02" id="iap_edo02" required>
                                        @if($regasyc->iap_edo02 == 'S')
                                            <option value="S" selected>Si</option>
                                            <option value="N">         No</option>
                                        @else
                                            <option value="S">         Si</option>
                                            <option value="N" selected>No</option>
                                        @endif
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">
                                @if(count($errors) > 0)
                                    <div class="alert alert-danger" role="alert">
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Guardar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verAsyc')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\asycRequest','#actualizarAsyc2') !!}
@endsection

@section('javascrpt')
@endsection