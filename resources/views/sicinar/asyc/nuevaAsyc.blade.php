@extends('sicinar.principal')

@section('title','Registro de Información de asistencia social y contable')

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
                <small>IAPS - Información de Asistencia social y contable </small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header"><h3 class="box-title">Registrar información de asistencia social y contable</h3></div> 
                        {!! Form::open(['route' => 'AltaNuevaAsyc', 'method' => 'POST','id' => 'nuevaAsyc', 'enctype' => 'multipart/form-data']) !!}
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
                                <div class="col-xs-4 form-group">
                                    <label >Periodo fiscal </label>
                                    <select class="form-control m-bot15" name="periodo_id" id="periodo_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo fiscal</option>
                                        @foreach($regperiodos as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                            </div>     

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de padrón de beneficiarios (formato excel) </label>
                                    <input type="file" class="text-md-center" style="color:red" name="iap_d01" id="iap_d01" placeholder="Subir archivo de padrón de beneficiarios en formato excel">
                                </div>   
                            </div>
                            <div class="row">                                                                                  
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de entrega de información </label>
                                    <select class="form-control m-bot15" name="per01_id" id="per01_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodicidad de entrega</option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num01_id" id="num01_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo de entrega</option>
                                        @foreach($regnumeros as $numero)
                                            <option value="{{$numero->num_id}}">{{$numero->num_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Está actualizada la información? </label>
                                    <select class="form-control m-bot15" name="iap_edo01" id="iap_edo01" required>
                                        <option value="S">         Si</option>
                                        <option value="N" selected>No</option>
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de Listado de Personal (formato excel) </label>
                                    <input type="file" class="text-md-center" style="color:red" name="iap_d02" id="iap_d02" placeholder="Subir archivo de Listado de personal en formato excel">
                                </div>
                            </div>
                            <div class="row">                                                                
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de entrega de información </label>
                                    <select class="form-control m-bot15" name="per02_id" id="per02_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodicidad de entrega</option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num02_id" id="num02_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo de entrega</option>
                                        @foreach($regnumeros as $numero)
                                            <option value="{{$numero->num_id}}">{{$numero->num_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Está actualizada la información? </label>
                                    <select class="form-control m-bot15" name="iap_edo02" id="iap_edo02" required>
                                        <option value="S">         Si</option>
                                        <option value="N" selected>No</option>
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de Detección de necesidades (formato excel) </label>
                                    <input type="file" class="text-md-center" style="color:red" name="iap_d03" id="iap_d03" placeholder="Subir archivo de detección de necesidades en formato excel">
                                </div>   
                            </div>
                            <div class="row">                                                                                  
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de entrega de información </label>
                                    <select class="form-control m-bot15" name="per03_id" id="per03_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodicidad de entrega</option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num03_id" id="num03_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo de entrega</option>
                                        @foreach($regnumeros as $numero)
                                            <option value="{{$numero->num_id}}">{{$numero->num_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Está actualizada la información? </label>
                                    <select class="form-control m-bot15" name="iap_edo03" id="iap_edo03" required>
                                        <option value="S">         Si</option>
                                        <option value="N" selected>No</option>
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de inventario de activo fijo (formato excel) </label>
                                    <input type="file" class="text-md-center" style="color:red" name="iap_d04" id="iap_d04" placeholder="Subir archivo de inventario de activo fijo en formato excel">
                                </div>   
                            </div>
                            <div class="row">                                                                                  
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de entrega de información </label>
                                    <select class="form-control m-bot15" name="per04_id" id="per04_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodicidad de entrega</option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num04_id" id="num04_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo de entrega</option>
                                        @foreach($regnumeros as $numero)
                                            <option value="{{$numero->num_id}}">{{$numero->num_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Está actualizada la información? </label>
                                    <select class="form-control m-bot15" name="iap_edo04" id="iap_edo04" required>
                                        <option value="S">         Si</option>
                                        <option value="N" selected>No</option>
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de presupuesto anual (formato excel) </label>
                                    <input type="file" class="text-md-center" style="color:red" name="iap_d05" id="iap_d05" placeholder="Subir archivo de presupuesto anual en formato excel">
                                </div>   
                            </div>
                            <div class="row">                                                                                  
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de entrega de información </label>
                                    <select class="form-control m-bot15" name="per05_id" id="per05_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodicidad de entrega</option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num05_id" id="num05_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo de entrega</option>
                                        @foreach($regnumeros as $numero)
                                            <option value="{{$numero->num_id}}">{{$numero->num_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Está actualizada la información? </label>
                                    <select class="form-control m-bot15" name="iap_edo05" id="iap_edo05" required>
                                        <option value="S">         Si</option>
                                        <option value="N" selected>No</option>
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de programa de trabajo (formato excel) </label>
                                    <input type="file" class="text-md-center" style="color:red" name="iap_d06" id="iap_d06" placeholder="Subir archivo de programa de trabajo en formato excel">
                                </div>   
                            </div>
                            <div class="row">                                                                                  
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de entrega de información </label>
                                    <select class="form-control m-bot15" name="per06_id" id="per06_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodicidad de entrega</option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num06_id" id="num06_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo de entrega</option>
                                        @foreach($regnumeros as $numero)
                                            <option value="{{$numero->num_id}}">{{$numero->num_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Está actualizada la información? </label>
                                    <select class="form-control m-bot15" name="iap_edo06" id="iap_edo06" required>
                                        <option value="S">         Si</option>
                                        <option value="N" selected>No</option>
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de Constancia de autorización para recibir donativos (formato PDF)</label>
                                    <input type="file" class="text-md-center" style="color:red" name="iap_d07" id="iap_d07" placeholder="Subir archivo de Constancia de autorización para recibir donativos en formato PDF">
                                </div>   
                            </div>
                            <div class="row">                                                                                  
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de entrega de información </label>
                                    <select class="form-control m-bot15" name="per07_id" id="per07_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodicidad de entrega</option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num07_id" id="num07_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo de entrega</option>
                                        @foreach($regnumeros as $numero)
                                            <option value="{{$numero->num_id}}">{{$numero->num_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Está actualizada la información? </label>
                                    <select class="form-control m-bot15" name="iap_edo07" id="iap_edo07" required>
                                        <option value="S">         Si</option>
                                        <option value="N" selected>No</option>
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de Constancia de cumplimiento de declaración ante el SAT (formato PDF) </label>
                                    <input type="file" class="text-md-center" style="color:red" name="iap_d08" id="iap_d08" placeholder="Subir archivo de Constancia de cumplimiento de declaración ante el SAT (formato PDF)">
                                </div>   
                            </div>
                            <div class="row">                                                                                  
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de entrega de información </label>
                                    <select class="form-control m-bot15" name="per08_id" id="per08_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodicidad de entrega</option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num08_id" id="num08_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo de entrega</option>
                                        @foreach($regnumeros as $numero)
                                            <option value="{{$numero->num_id}}">{{$numero->num_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Está actualizada la información? </label>
                                    <select class="form-control m-bot15" name="iap_edo08" id="iap_edo08" required>
                                        <option value="S">         Si</option>
                                        <option value="N" selected>No</option>
                                    </select>
                                </div>                                                                  
                            </div>


                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar la información de asistencia social y contable ',['class' => 'btn btn-success btn-flat pull-right']) !!}
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
    {!! JsValidator::formRequest('App\Http\Requests\asycRequest','#nuevaAsyc') !!}
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