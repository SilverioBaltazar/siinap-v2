@extends('sicinar.principal')

@section('title','Editar requisitos contables')

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
                <small> Instituciones Privadas (IAPS) - requisitos contables - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Editar requisitos contables</h3>
                        </div>
                        {!! Form::open(['route' => ['actualizarReqc7',$regcontable->iap_id], 'method' => 'PUT', 'id' => 'actualizarReqc7', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>IAP &nbsp;&nbsp;&nbsp; </label><br>
                                    @foreach($regiap as $iap)
                                        @if($iap->iap_id == $regcontable->iap_id)
                                            <label style="color:green;">{{$regcontable->iap_id.' '.$iap->iap_desc}}</label>
                                            @break
                                        @endif
                                    @endforeach
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label>Periodo fiscal de los requisitos a cumplir</label>
                                    @foreach($regperiodos as $periodo)
                                        @if($periodo->periodo_id == $regcontable->periodo_id)
                                            <label style="color:green;">{{$periodo->periodo_desc}}</label>
                                            @break
                                        @endif
                                    @endforeach
                                </div>                                                                     
                            </div>

                            <div class="row">
                                @if (!empty($regcontable->iap_d7)||!is_null($regcontable->iap_d7))   
                                    <div class="col-xs-4 form-group">
                                        <label >Presupuesto anual en formato excel </label><br>
                                        <label ><a href="/images/{{$regcontable->iap_d7}}" class="btn btn-danger" title="presupuesto anual en formato excel "><i class="fa fa-file-excel-o"></i>Excel
                                        </a>&nbsp;&nbsp;&nbsp;{{$regcontable->iap_d7}}
                                        </label>
                                        <input type="hidden" name="doc_id7" id="doc_id7" value="16">
                                    </div>   
                                    <div class="col-xs-4 form-group">
                                        <label >Actualizar archivo de presupuesto anual en formato excel </label>
                                        <input type="file" class="text-md-center" style="color:red" name="iap_d7" id="iap_d7" placeholder="Subir archivo de presupuesto anual en formato excel " >
                                    </div>      
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de inventario general en formato Excel</label>
                                        <input type="file" class="text-md-center" style="color:red" name="iap_d7" id="iap_d7" placeholder="Subir archivo de presupuesto anual en formato excel" >
                                        <input type="hidden" name="doc_id7" id="doc_id7" value="16">
                                    </div>                                                
                                @endif 
                                <div class="col-xs-4 form-group">
                                    <label >Formato del archivo que se subio </label>
                                    <select class="form-control m-bot15" name="formato_id7" id="formato_id7" required>
                                        <option selected="true" disabled="disabled">Seleccionar formato del archivo</option>
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
                                    <label >Monto en $ pesos mexicanos de ingresos  </label>
                                    <input type="text" class="form-control" name="preg_003" id="preg_003" placeholder="Monto en $ pesos mexicanos de ingresos" value="{{$regcontable->preg_003}}" required>
                                </div>                                                                        
                                <div class="col-xs-4 form-group">
                                    <label >Monto en $ pesos mexicanos de egresos </label>
                                    <input type="text" class="form-control" name="preg_004" id="preg_004" placeholder="Monto en $ pesos mexicanos de egresos" value="{{$regcontable->preg_004}}" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Monto en $ pesos mexicanos de inversión </label>
                                    <input type="text" class="form-control" name="preg_005" id="preg_005" placeholder="Monto en $ pesos mexicanos de inversión" value="{{$regcontable->preg_005}}" required>
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
    {!! JsValidator::formRequest('App\Http\Requests\reqcontablesRequest','#actualizarRec7') !!}
@endsection

@section('javascrpt')
@endsection