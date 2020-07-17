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
                        {!! Form::open(['route' => ['actualizarReqc8',$regcontable->iap_id], 'method' => 'PUT', 'id' => 'actualizarReqc8', 'enctype' => 'multipart/form-data']) !!}
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
                                @if (!empty($regcontable->iap_d8)||!is_null($regcontable->iap_d8))   
                                    <div class="col-xs-4 form-group">
                                        <label >Constancia de autorización para recibir donativos en formato PDF</label><br>
                                        <label ><a href="/images/{{$regcontable->iap_d8}}" class="btn btn-danger" title="Constancia de autorización para recibir donativos en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF
                                        </a>&nbsp;&nbsp;&nbsp;{{$regcontable->iap_d8}}
                                        </label>
                                        <input type="hidden" name="doc_id8" id="doc_id8" value="14">
                                    </div>   
                                    <div class="col-xs-4 form-group">
                                        <label >Constancia de autorización para recibir donativos en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="iap_d8" id="iap_d8" placeholder="Subir archivo de constancia de autorización para recibir donativos en formato PDF" >
                                    </div>      
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de Constancia de autorización para recibir donativos en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="iap_d8" id="iap_d8" placeholder="Subir archivo de constancia de autorización para recibir donativos en formato PDF" >
                                        <input type="hidden" name="doc_id8" id="doc_id8" value="14">
                                    </div>                                                
                                @endif 
                                <div class="col-xs-4 form-group">
                                    <label >Formato del archivo que se subio </label>
                                    <select class="form-control m-bot15" name="formato_id8" id="formato_id8" required>
                                        <option selected="true" disabled="disabled">Seleccionar formato del archivo</option>
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
    {!! JsValidator::formRequest('App\Http\Requests\reqcontablesRequest','#actualizarRec8') !!}
@endsection

@section('javascrpt')
@endsection
