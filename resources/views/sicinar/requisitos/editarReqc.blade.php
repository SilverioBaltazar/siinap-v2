@extends('sicinar.principal')

@section('title','Editar requisitos Contables')

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
            <h1>
                Menú
                <small> Requisitos contables - Otros requisitos - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarReqc',$regcontable->iap_id], 'method' => 'PUT', 'id' => 'actualizarReqc', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6 offset-md-6">
                                    <label>IAP : {{$regcontable->iap_id.' '}}</label>
                                    @foreach($regiap as $iap)
                                        @if($iap->iap_id == $regcontable->iap_id)
                                            <label style="color:green;">{{$iap->iap_desc}}</label>
                                            @break
                                        @endif
                                    @endforeach
                                </div>                                     
                                <div class="col-xs-4 form-group">
                                    <label >Periodo fiscal de los requisitos a cumplir </label>
                                    <select class="form-control m-bot15" name="periodo_id" id="periodo_id" required>
                                    <option selected="true" disabled="disabled">Seleccionar Periodo fiscal </option>
                                    @foreach($regperiodos as $periodo)
                                        @if($periodo->periodo_id == $regcontable->periodo_id)
                                            <option value="{{$periodo->periodo_id}}" selected>{{$periodo->periodo_desc}}</option>
                                        @else 
                                            <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}}</option>
                                        @endif                                    
                                    @endforeach
                                    </select>
                                </div>     
                            </div>
           
                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    @if(!empty($regcontable->iap_d7)||(!is_null($regcontable->iap_d7)))
                                        <label >Archivo de presupuesto anual en formato excel </label><br>
                                        <a href="/images/{{$regcontable->iap_d7}}" class="btn btn-danger" title="documento de presupuesto anual"><i class="fa fa-file-excel-o"></i><small>Excel</small>
                                        </a>{{$regcontable->iap_d7}}
                                        <input type="hidden" name="doc_id7" id="doc_id7" value="16">
                                    @else
                                        <label >Archivo de presupuesto anual en formato excel </label><br>
                                        <b style="color:darkred;">** Pendiente **</b>
                                        <input type="hidden" name="doc_id7" id="doc_id7" value="16">
                                    @endif
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Formato del archivo que se subio </label>
                                    <select class="form-control m-bot15" name="formato_id7" id="formato_id7" required>
                                        <option selected="true" disabled="disabled">Seleccionar formato de archivo </option>
                                        @foreach($regformatos as $formato)
                                            @if($formato->formato_id == $regcontable->formato_id7)
                                                <option value="{{$formato->formato_id}}" selected>{{$formato->formato_desc}}</option>
                                            @else 
                                               <option value="{{$formato->formato_id}}">{{$formato->formato_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                                    
                                </div>                                    
                            </div>
                            <div class="row">        
                                <div class="col-xs-4 form-group">
                                    <label >$ Ingreso estimado  </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="preg_003" id="preg_003" placeholder="$ Ingreso estimado" value="{{$regcontable->preg_003}}" required>
                                </div>                                                                        
                                <div class="col-xs-4 form-group">
                                    <label >$ Egreso estimado </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="preg_004" id="preg_004" placeholder="$ Egreso estimado" value="{{$regcontable->preg_004}}" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >$ Inversiones proyectadas </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="preg_005" id="preg_005" placeholder="$ Inversiones proyectadas" value="{{$regcontable->preg_005}}" required>
                                </div>                                  
                            </div>                                           
                            <div class="row">                                                                 
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de cumplimiento del requisito </label>
                                    <select class="form-control m-bot15" name="per_id7" id="per_id7" required>
                                        <option selected="true" disabled="disabled">Seleccionar Periodicidad de cumplimiento </option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            @if($periodicidad->per_id == $regcontable->per_id7)
                                                <option value="{{$periodicidad->per_id}}" selected>{{$periodicidad->per_desc}}</option>
                                            @else 
                                               <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num_id7" id="num_id7" required>
                                        <option selected="true" disabled="disabled">Seleccionar Periodo de entrega</option>
                                        @foreach($regnumeros as $numeros)
                                            @if($numeros->num_id == $regcontable->num_id7)
                                                <option value="{{$numeros->num_id}}" selected>{{$numeros->num_desc}}</option>
                                            @else 
                                               <option value="{{$numeros->num_id}}">{{$numeros->num_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>  
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Requisito cumplido y actualizado? </label>
                                    <select class="form-control m-bot15" name="iap_edo7" id="iap_edo7" required>
                                        @if($regcontable->iap_edo7 == 'S')
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
                                <div class="col-xs-4 form-group">
                                    @if(!empty($regcontable->iap_d8)||(!is_null($regcontable->iap_d8)))
                                        <label >Archivo de constancia de autorización para recibir donativos en formato PDF </label><br>
                                        <a href="/images/{{$regcontable->iap_d8}}" class="btn btn-danger" title="constancia de autorización para recibir donativos"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                        </a>{{$regcontable->iap_d8}}
                                        <input type="hidden" name="doc_id8" id="doc_id8" value="14">
                                    @else
                                        <label >Archivo de constancia de autorización para recibir donativos en formato PDF </label><br>
                                        <b style="color:darkred;">** Pendiente **</b>
                                        <input type="hidden" name="doc_id8" id="doc_id8" value="14">
                                    @endif
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Formato del archivo que se subio </label>
                                    <select class="form-control m-bot15" name="formato_id8" id="formato_id8" required>
                                        <option selected="true" disabled="disabled">Seleccionar formato del archivo </option>
                                        @foreach($regformatos as $formato)
                                            @if($formato->formato_id == $regcontable->formato_id8)
                                                <option value="{{$formato->formato_id}}" selected>{{$formato->formato_desc}}</option>
                                            @else 
                                               <option value="{{$formato->formato_id}}">{{$formato->formato_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                                    
                                </div>                                    
                            </div>
                            <div class="row">                                                                 
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de cumplimiento del requisito </label>
                                    <select class="form-control m-bot15" name="per_id8" id="per_id8" required>
                                        <option selected="true" disabled="disabled">Seleccionar Periodicidad de cumplimiento </option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            @if($periodicidad->per_id == $regcontable->per_id8)
                                                <option value="{{$periodicidad->per_id}}" selected>{{$periodicidad->per_desc}}</option>
                                            @else 
                                               <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num_id8" id="num_id8" required>
                                        <option selected="true" disabled="disabled">Seleccionar Periodo de entrega</option>
                                        @foreach($regnumeros as $numeros)
                                            @if($numeros->num_id == $regcontable->num_id8)
                                                <option value="{{$numeros->num_id}}" selected>{{$numeros->num_desc}}</option>
                                            @else 
                                               <option value="{{$numeros->num_id}}">{{$numeros->num_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>  
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Requisito cumplido y actualizado? </label>
                                    <select class="form-control m-bot15" name="iap_edo8" id="iap_edo8" required>
                                        @if($regcontable->iap_edo8 == 'S')
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
                                <div class="col-xs-4 form-group">
                                    @if(!empty($regcontable->iap_d9)||(!is_null($regcontable->iap_d9)))
                                        <label >Archivo de declaración anual en formato PDF </label><br>
                                        <a href="/images/{{$regcontable->iap_d9}}" class="btn btn-danger" title="declaración anual"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                        </a>{{$regcontable->iap_d9}}
                                        <input type="hidden" name="doc_id9" id="doc_id9" value="13">
                                    @else
                                        <label >Archivo de declaración anual en formato PDF </label><br>
                                        <b style="color:darkred;">** Pendiente **</b>
                                        <input type="hidden" name="doc_id9" id="doc_id9" value="13">
                                    @endif
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Formato del archivo que se subio </label>
                                    <select class="form-control m-bot15" name="formato_id9" id="formato_id9" required>
                                        <option selected="true" disabled="disabled">Seleccionar formato del archivo </option>
                                        @foreach($regformatos as $formato)
                                            @if($formato->formato_id == $regcontable->formato_id9)
                                                <option value="{{$formato->formato_id}}" selected>{{$formato->formato_desc}}</option>
                                            @else 
                                               <option value="{{$formato->formato_id}}">{{$formato->formato_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                                    
                                </div>                                    
                            </div>
                            <div class="row">                                                                 
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de cumplimiento del requisito </label>
                                    <select class="form-control m-bot15" name="per_id9" id="per_id9" required>
                                        <option selected="true" disabled="disabled">Seleccionar Periodicidad de cumplimiento </option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            @if($periodicidad->per_id == $regcontable->per_id9)
                                                <option value="{{$periodicidad->per_id}}" selected>{{$periodicidad->per_desc}}</option>
                                            @else 
                                               <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num_id9" id="num_id9" required>
                                        <option selected="true" disabled="disabled">Seleccionar Periodo de entrega</option>
                                        @foreach($regnumeros as $numeros)
                                            @if($numeros->num_id == $regcontable->num_id9)
                                                <option value="{{$numeros->num_id}}" selected>{{$numeros->num_desc}}</option>
                                            @else 
                                               <option value="{{$numeros->num_id}}">{{$numeros->num_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>  
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Requisito cumplido y actualizado? </label>
                                    <select class="form-control m-bot15" name="iap_edo9" id="iap_edo9" required>
                                        @if($regcontable->iap_edo9 == 'S')
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
                                <div class="col-xs-4 form-group">
                                    @if(!empty($regcontable->iap_d10) || !is_null($regcontable->iap_d10))
                                        <label >Archivo de cuotas 5 al millar en formato PDF </label><br>
                                        <a href="/images/{{$regcontable->iap_d10}}" class="btn btn-danger" title="cuotas 5 al millar"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                        </a>{{$regcontable->iap_d10}}
                                        <input type="hidden" name="doc_id10" id="doc_id10" value="15">
                                    @else
                                        <label >Archivo de cuotas 5 al millar en formato PDF </label><br>
                                        <b style="color:darkred;">** Pendiente **</b>
                                        <input type="hidden" name="doc_id10" id="doc_id10" value="15">
                                    @endif
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Formato del archivo que se subio </label>
                                    <select class="form-control m-bot15" name="formato_id10" id="formato_id10" required>
                                        <option selected="true" disabled="disabled">Seleccionar formato del archivo </option>
                                        @foreach($regformatos as $formato)
                                            @if($formato->formato_id == $regcontable->formato_id10)
                                                <option value="{{$formato->formato_id}}" selected>{{$formato->formato_desc}}</option>
                                            @else 
                                               <option value="{{$formato->formato_id}}">{{$formato->formato_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                                    
                                </div>                                    
                            </div>
                            <div class="row">        
                                <div class="col-xs-4 form-group">
                                    <label >Monto en $ pesos mexicanos de la aportación monetaria </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="preg_006" id="preg_006" placeholder="Monto en pesos $ mexicanos de la aportación monetaria" value="{{$regcontable->preg_006}}" required>
                                </div>                                                                        
                            </div>                                                                       
                            <div class="row">                                                                 
                                <div class="col-xs-4 form-group">
                                    <label >Periodicidad de cumplimiento del requisito </label>
                                    <select class="form-control m-bot15" name="per_id10" id="per_id10" required>
                                        <option selected="true" disabled="disabled">Seleccionar Periodicidad de cumplimiento </option>
                                        @foreach($regperiodicidad as $periodicidad)
                                            @if($periodicidad->per_id == $regcontable->per_id10)
                                                <option value="{{$periodicidad->per_id}}" selected>{{$periodicidad->per_desc}}</option>
                                            @else 
                                               <option value="{{$periodicidad->per_id}}">{{$periodicidad->per_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Periodo de entrega  </label>
                                    <select class="form-control m-bot15" name="num_id10" id="num_id10" required>
                                        <option selected="true" disabled="disabled">Seleccionar Periodo de entrega</option>
                                        @foreach($regnumeros as $numeros)
                                            @if($numeros->num_id == $regcontable->num_id10)
                                                <option value="{{$numeros->num_id}}" selected>{{$numeros->num_desc}}</option>
                                            @else 
                                               <option value="{{$numeros->num_id}}">{{$numeros->num_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>  
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Requisito cumplido y actualizado? </label>
                                    <select class="form-control m-bot15" name="iap_edo10" id="iap_edo10" required>
                                        @if($regcontable->iap_edo10 == 'S')
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
                                    <a href="{{route('verReqc')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar
                                    </a>
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
    {!! JsValidator::formRequest('App\Http\Requests\reqcontablesRequest','#actualizarReqc') !!}
@endsection

@section('javascrpt')
@endsection