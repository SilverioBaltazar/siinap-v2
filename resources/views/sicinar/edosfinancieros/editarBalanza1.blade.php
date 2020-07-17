@extends('sicinar.principal')

@section('title','Editar Edo. financiero')

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
                <small>Requisitos contables - Edo. Financiero - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Editar Edo. Financiero</h3>
                        </div>
                        {!! Form::open(['route' => ['actualizarBalanza1',$regbalanza->edofinan_folio], 'method' => 'PUT', 'id' => 'actualizarBalanza1', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label style="color:green; text-align:left;">Periodo fiscal : </label>
                                    {{$regbalanza->periodo_id}}
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label style="color:green; text-align:center;">IAP :</label>
                                        @foreach($regiap as $iap)
                                            @if($iap->iap_id == $regbalanza->iap_id)
                                               {{$iap->iap_desc}} 
                                               @break
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                 
                                <div class="col-xs-4 form-group">
                                    <label style="color:green; text-align:right;">Folio : {{$regbalanza->edofinan_folio}}</label>
                                </div>                                             
                            </div>

                            <div class="row">       
                                <div class="col-xs-4 form-group">
                                    <label style="color:green;text-align:left;">Periodicidad de cumplimiento : </label>
                                        @foreach($regperiodicidad as $periodicidad)
                                            @if($periodicidad->per_id == $regbalanza->per_id)
                                                {{$periodicidad->per_desc}}
                                                @break
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label style="color:green; text-align:right;">Semestre a reportar : </label>
                                        @foreach($regnumeros as $numeros)
                                            @if($numeros->num_id == $regbalanza->num_id)
                                                {{$numeros->num_desc}}
                                                @break
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>                                                                   
                            </div>


                            <div class="row">
                                @if (!empty($regbalanza->edofinan_foto1)||!is_null($regbalanza->edofinan_foto1))   
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de edo. financiero y Balanza de comprobación en formato PDF</label><br>
                                        <label ><a href="/images/{{$regbalanza->edofinan_foto1}}" class="btn btn-danger" title="Archivo de edo. financiero y Balanza de comprobación en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF
                                        </a>&nbsp;&nbsp;&nbsp;{{$regbalanza->edofinan_foto1}}
                                        </label>
                                    </div>   
                                    <div class="col-xs-4 form-group">
                                        <label >Actualizar Archivo de edo. financiero y Balanza de comprobación en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="edofinan_foto1" id="edofinan_foto1" placeholder="Subir Archivo de edo. financiero y Balanza de comprobación en formato PDF" >
                                    </div>      
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de edo. financiero y Balanza de comprobación en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="edofinan_foto1" id="edofinan_foto1" placeholder="Subir Archivo de edo. financiero y Balanza de comprobación en formato PDF" >
                                    </div>                                                
                                @endif 
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
                                    <a href="{{route('verBalanza')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\balanza1Request','#actualizarBalanza1') !!}
@endsection

@section('javascrpt')
@endsection
