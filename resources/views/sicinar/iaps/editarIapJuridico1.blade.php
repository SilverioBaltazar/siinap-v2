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
                        {!! Form::open(['route' => ['actualizarIapj1',$regiapjuridico->iap_id], 'method' => 'PUT', 'id' => 'actualizarIapj1', 'enctype' => 'multipart/form-data']) !!}
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
                                @if (!empty($regiapjuridico->iap_act_const)||!is_null($regiapjuridico->iap_act_const))   
                                    <div class="col-xs-4 form-group">
                                        <label >Acta constitutiva en formato PDF</label>
                                        <label ><a href="/images/{{$regiapjuridico->iap_act_const}}" class="btn btn-danger" title="Acta constitutiva en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF</a>
                                        </label>
                                    </div>   
                                    <div class="col-xs-4 form-group">
                                        <label >Actualizar archivo de Acta constitutiva en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="iap_act_const" id="iap_act_const" placeholder="Subir archivo de Acta constitutiva en formato PDF" >
                                    </div>      
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de Acta constitutiva en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="iap_act_const" id="iap_act_const" placeholder="Subir archivo de Acta constitutiva en formato PDF" >
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
    {!! JsValidator::formRequest('App\Http\Requests\iapsjuridicoRequest','#actualizarIapj1') !!}
@endsection

@section('javascrpt')
@endsection