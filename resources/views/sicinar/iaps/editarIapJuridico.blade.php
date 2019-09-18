@extends('sicinar.principal')

@section('title','Editar Datos Juridicos')

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
                <small> Instituciones Privadas (IAPS) - Datos Jurídicos - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Editar Datos Jurídicos</h3>
                        </div>
                        {!! Form::open(['route' => ['actualizarIapj',$regiapjuridico->iap_id], 'method' => 'PUT', 'id' => 'actualizarIapj', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 offset-md-12">
                                    <label>Id.: {{$regiapjuridico->iap_id.' '}}</label>
                                    @foreach($regiap as $iap)
                                            @if($iap->iap_id == $regiapjuridico->iap_id)
                                                <label>{{$iap->iap_desc}}</label>
                                            @else                                        
                                               <label>***</label>
                                            @endif
                                    @endforeach
                                </div>                                     
                            </div>

                            <div class="row">                                
                                <div class="col-xs-4 form-group">                        
                                    <label>* ¿Está registrado en el registro público de la propiedad? </label>
                                    <select class="form-control m-bot15" name="iap_rpp" required>
                                        @if($regiapjuridico->iap_rpp == 'S')
                                            <option value="S" selected>Si</option>
                                            <option value="N">         No</option>
                                        @else
                                            <option value="S">         Si</option>
                                            <option value="N" selected>No</option>
                                        @endif
                                    </select>
                                </div>                                                                  
                                <div class="col-xs-4 form-group">
                                    <label>Fecha de vencimiento del Patronato (dd/mm/aaaa) </label>
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" class="form-control pull-right" id="datepicker1"  name="iap_fvp" placeholder="* Fecha de vencimiento del patronato (dd/mm/aaaa)" value="{!! date('d/m/Y',strtotime($regiapjuridico->iap_fvp)) !!}" required>
                                    </div>
                                </div> 
                            </div>

                            <div class="row">                                    
                                <div class="col-xs-4 form-group">
                                    <label >Vigencia del patronato en años</label>
                                    <select class="form-control m-bot15" name="anio_id" id="anio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar la vigencia </option>
                                        @foreach($regvigencia as $vigencia)
                                            @if($vigencia->anio_id == $regiapjuridico->anio_id)
                                                <option value="{{$vigencia->anio_id}}" selected>{{$vigencia->anio_desc}}</option>
                                            @else                                        
                                               <option value="{{$vigencia->anio_id}}">{{$vigencia->anio_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div> 
                                <div class="col-xs-4 form-group">
                                    <label >Situación del inmueble</label>
                                    <select class="form-control m-bot15" name="inm_id" id="inm_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar situación del inmueble</option>
                                        @foreach($reginmuebles as $inmueble)
                                            @if($inmueble->inm_id == $regiapjuridico->inm_id)
                                                <option value="{{$inmueble->inm_id}}" selected>{{$inmueble->inm_desc}}</option>
                                            @else 
                                               <option value="{{$inmueble->inm_id}}">{{$inmueble->inm_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>                                    
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    @if (!empty($regiapjuridico->iap_act_const)||!is_null($regiapjuridico->iap_act_const))  
                                        <label >Acta constitutiva</label>
                                        <label ><a href="/images/{{$regiapjuridico->iap_act_const}}" class="btn btn-danger" title="Acta constitutiva en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF</a>
                                        </label>
                                    @else     <!-- se captura archivo 1 -->
                                        <label >Sin Acta constitutiva</label>               
                                    @endif 
                                </div>                                       
                            </div>

                            <div class="row">                    
                                <div class="col-xs-4 form-group">            
                                    @if (!empty($regiapjuridico->iap_rfc)||!is_null($regiapjuridico->iap_rfc)) 
                                        <label >RFC de SAT</label>
                                        <label ><a href="/images/{{$regiapjuridico->iap_act_const}}" class="btn btn-danger" title="RFC de SAT en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF</a>
                                        </label>
                                    @else     <!-- se captura archivo 1 -->
                                        <label >Sin RFC del SAT</label>
                                    @endif 
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
    {!! JsValidator::formRequest('App\Http\Requests\iapsjuridicoRequest','#actualizarIapj') !!}
@endsection

@section('javascrpt')
@endsection