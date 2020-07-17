@extends('sicinar.principal')

@section('title','Registro de requisitos contables')

@section('links')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('nombre')
    {{$nombre}}
@endsection

@section('usuario')
    {{$usuario}}
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Menú
                <small>Requisitos contables - Otros requisitos - Nuevo </small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12"> 
                    <div class="box box-success">

                        {!! Form::open(['route' => 'AltaNuevoReqc', 'method' => 'POST','id' => 'nuevoReqc', 'enctype' => 'multipart/form-data']) !!}

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
                                    <label >Periodo fiscal de los requisitos a cumplir</label>
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
                                    <label >Archivo de Presupuesto anual en formato Excel </label>
                                    <input type="hidden" name="doc_id7" id="doc_id7" value="16">
                                    <input type="file" class="text-md-center" style="color:red" name="iap_d7" id="iap_d7" placeholder="Subir archivo de Presupuesto anual en formato Excel">
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Formato del archivo a subir </label>
                                    <select class="form-control m-bot15" name="formato_id7" id="formato_id7" required>
                                        <option selected="true" disabled="disabled">Seleccionar formato del archivo a subir</option>
                                        @foreach($regformatos as $formato)
                                            <option value="{{$formato->formato_id}}">{{$formato->formato_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                         
                            </div>    
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >$ Ingreso estimado  </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="preg_003" id="preg_003" placeholder="$ Ingreso estimado " required>
                                </div>                                                          
                                <div class="col-xs-4 form-group">
                                    <label >$ Egreso estimado </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="preg_004" id="preg_004" placeholder="$ Egreso estimado" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >$ Inversiones proyectadas </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="preg_005" id="preg_005" placeholder="$ Inversiones proyectadas" required>
                                </div>                                   
                            </div>                                         
                            <div class="row">                                                                       
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de cumplimiento del requisito </label>
                                    <select class="form-control m-bot15" name="per_id7" id="per_id7" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodicidad de entrega</option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num_id7" id="num_id7" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo de entrega</option>
                                        @foreach($regnumeros as $numero)
                                            <option value="{{$numero->num_id}}">{{$numero->num_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Requisito cumplido y actualizado? </label>
                                    <select class="form-control m-bot15" name="iap_edo7" id="iap_edo7" required>
                                        <option value="S">         Si</option>
                                        <option value="N" selected>No</option>
                                    </select>
                                </div>                                                                  
                            </div>                            

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de Constancia de cumplimiento para recibir donativos en formato PDF </label>
                                    <input type="hidden" name="doc_id8" id="doc_id8" value="14">
                                    <input type="file" class="text-md-center" style="color:red" name="iap_d8" id="iap_d8" placeholder="Subir archivo de Constancia de cumplimiento para recibir donativos en formato PDF">
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Formato del archivo a subir </label>
                                    <select class="form-control m-bot15" name="formato_id8" id="formato_id8" required>
                                        <option selected="true" disabled="disabled">Seleccionar formato del archivo a subir</option>
                                        @foreach($regformatos as $formato)
                                            <option value="{{$formato->formato_id}}">{{$formato->formato_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>         
                            </div> 
                            <div class="row">                                                                       
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de cumplimiento del requisito </label>
                                    <select class="form-control m-bot15" name="per_id8" id="per_id8" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodicidad de entrega</option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num_id8" id="num_id8" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo de entrega</option>
                                        @foreach($regnumeros as $numero)
                                            <option value="{{$numero->num_id}}">{{$numero->num_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Requisito cumplido y actualizado? </label>
                                    <select class="form-control m-bot15" name="iap_edo8" id="iap_edo8" required>
                                        <option value="S">         Si</option>
                                        <option value="N" selected>No</option>
                                    </select>
                                </div>                                                                  
                            </div>                         

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de Declaración anual en formato PDF </label>
                                    <input type="hidden" name="doc_id9" id="doc_id9" value="13">
                                    <input type="file" class="text-md-center" style="color:red" name="iap_d9" id="iap_d9" placeholder="Subir archivo de Declaración anual en formato PDF">
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Formato del archivo a subir </label>
                                    <select class="form-control m-bot15" name="formato_id9" id="formato_id9" required>
                                        <option selected="true" disabled="disabled">Seleccionar formato del archivo a subir</option>
                                        @foreach($regformatos as $formato)
                                            <option value="{{$formato->formato_id}}">{{$formato->formato_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>         
                            </div> 
                            <div class="row">                                                                       
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de cumplimiento del requisito </label>
                                    <select class="form-control m-bot15" name="per_id9" id="per_id9" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodicidad de entrega</option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num_id9" id="num_id9" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo de entrega</option>
                                        @foreach($regnumeros as $numero)
                                            <option value="{{$numero->num_id}}">{{$numero->num_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Requisito cumplido y actualizado? </label>
                                    <select class="form-control m-bot15" name="iap_edo9" id="iap_edo9" required>
                                        <option value="S">         Si</option>
                                        <option value="N" selected>No</option>
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de quotas de 5 al millar en formato PDF </label>
                                    <input type="hidden" name="doc_id10" id="doc_id10" value="15">
                                    <input type="file" class="text-md-center" style="color:red" name="iap_d10" id="iap_d10" placeholder="Subir archivo de quotas de 5 al millar en formato PDF">
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Formato del archivo a subir </label>
                                    <select class="form-control m-bot15" name="formato_id10" id="formato_id10" required>
                                        <option selected="true" disabled="disabled">Seleccionar formato del archivo a subir</option>
                                        @foreach($regformatos as $formato)
                                            <option value="{{$formato->formato_id}}">{{$formato->formato_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>         
                            </div> 
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Monto en pesos mexicanos de la aportación monetaria </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="preg_006" id="preg_006" placeholder="Monto en pesos mexicanos de la aportación monetaria" required>
                                </div>                                                          
                            </div>                                                                     
                            <div class="row">                                                                       
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de cumplimiento del requisito </label>
                                    <select class="form-control m-bot15" name="per_id10" id="per_id10" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodicidad de entrega</option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num_id10" id="num_id10" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo de entrega</option>
                                        @foreach($regnumeros as $numero)
                                            <option value="{{$numero->num_id}}">{{$numero->num_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Requisito cumplido y actualizado? </label>
                                    <select class="form-control m-bot15" name="iap_edo10" id="iap_edo10" required>
                                        <option value="S">         Si</option>
                                        <option value="N" selected>No</option>
                                    </select>
                                </div>                                                                  
                            </div>                         

                            <div class="row">
                                <div class="col-md-6 offset-md-5">
                                    {!! Form::submit('Registrar requisitos contables ',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verReqc')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\reqcontablesRequest','#nuevoReqc') !!}
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
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ634567890,.;:-_<>!%()=?¡¿/*+";
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