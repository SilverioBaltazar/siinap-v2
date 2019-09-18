@extends('sicinar.principal')

@section('title','Ver Datos jurídicos de la IAP')

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
            <h1>IAPS datos jurídicos
                <small> Seleccionar alguna IAP para editar o registrar datos juridicos</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Instituciones de Asistencia Privada (IAPS) </a></li>
                <li><a href="#">Datos Jurídicos de la IAP  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('nuevaIapj')}}"   class="btn btn-primary btn_xs" title="Registrar datos jurídicos"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Registrar datos jurídicos</a>
                        </div>                        
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.                  </th>
                                        <th style="text-align:left;   vertical-align: middle;">Nombre de la IAP     </th>
                                        <th style="text-align:center; vertical-align: middle;">Activa /<br> Inact.  </th>
                                    
                                        <th style="text-align:center; vertical-align: middle;">Acta <br>Constitutiva</th>
                                        <th style="text-align:left;   vertical-align: middle;">RFC (SAT)            </th>
                                        <th style="text-align:center; vertical-align: middle;">Reg.Pub.<br>Prop.    </th>
                                        <th style="text-align:center; vertical-align: middle;">Vigencia             </th>
                                        <th style="text-align:center; vertical-align: middle;">Fecha<br>Vencim.     </th>
                                        <th style="text-align:center; vertical-align: middle;">Situación<br>Inm.    </th>
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($regiapjuridico as $juridico)
                                    <tr>
                                    <!--@if(!empty($juridico->iap_act_const)&&(!is_null($juridico->iap_act_const))) -->
                                        @foreach($regiap as $iap)
                                            @if($iap->iap_id == $juridico->iap_id)
                                                <td style="text-align:left; vertical-align: middle;">{{$iap->iap_id}}</td>
                                                <td style="text-align:left; vertical-align: middle;">{{Trim($iap->iap_desc)}}</td>                                                        
                                                @if($iap->iap_status == 'S')
                                                    <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activa"><i class="fa fa-check"></i>
                                                    </td>                                            
                                                @else
                                                    <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactiva"><i class="fa fa-times"></i>
                                                    </td>                                            
                                                @endif                               
                                                @break
                                            @endif
                                        @endforeach    
                    
                                        @if(!empty($juridico->iap_act_const)&&(!is_null($juridico->iap_act_const)))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Acta Constitutiva en formato PDF">
                                                <a href="/images/{{$juridico->iap_act_const}}" class="btn btn-danger" title="Acta Constitutiva en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF</a>
                                                <a href="{{route('editarIapj1',$juridico->iap_id)}}" class="btn badge-warning" title="Editar Acta Constitutiva"><i class="fa fa-edit"></i></a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Sin Acta Constitutiva"><i class="fa fa-times"> </i>
                                            </td>   
                                        @endif   
                                        @if(!empty($juridico->iap_rfc)&&(!is_null($juridico->iap_rfc)))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="RFC del SAT en formato PDF">
                                                <a href="/images/{{$juridico->iap_rfc}}" class="btn btn-danger" title="RFC del SAT en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF</a>
                                                <a href="{{route('editarIapj2',$juridico->iap_id)}}" class="btn badge-warning" title="Editar RFC del SAT"><i class="fa fa-edit"></i></a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Sin RFC del SAT"><i class="fa fa-times"> </i>
                                            </td>   
                                        @endif

                                        <td style="text-align:center; vertical-align: middle;">{{$juridico->iap_rpp}}</td>
                                        <td style="text-align:left; vertical-align: middle;">
                                            @foreach($regvigencia as $vigencia)
                                                @if($vigencia->anio_id == $juridico->anio_id)
                                                    {{$vigencia->anio_desc}}
                                                    @break
                                                @endif
                                            @endforeach    
                                        </td>
                                        <td style="text-align:center; vertical-align:middle;">{{date("d/m/Y", strtotime($juridico->iap_fvp))}}
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;">
                                            @foreach($reginmuebles as $inmueble)
                                                @if($inmueble->inm_id == $juridico->inm_id)
                                                    {{$inmueble->inm_desc}}
                                                    @break
                                                @endif
                                            @endforeach  
                                        </td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editarIapj',$iap->iap_id)}}" class="btn badge-warning" title="Editar datos jurídicos"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarIapj',$iap->iap_id)}}" class="btn badge-danger" title="Borrar Registro de datos jurídicos" onclick="return confirm('¿Seguro que desea borrar los datos jurídicos?')"><i class="fa fa-times"></i></a>
                                        </td>
                                    <!--@else
                                        <td style="color:darkred; text-align:center; vertical-align: middle;" title="Sin datos jurídicosInactiva"><i class="fa fa-times"></i>
                                        </td> 
                                        <td style="text-align:left; vertical-align: middle;"> </td>
                                        <td style="text-align:left; vertical-align: middle;"> </td>
                                        <td style="text-align:left; vertical-align: middle;"> </td>
                                        <td style="text-align:center;vertical-align:middle;"> </td>
                                        <td style="text-align:left; vertical-align: middle;"> </td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editarIapj',$iap->iap_id)}}" class="btn badge-warning" title="Registrar datos juridicos"><i class="fa fa-edit"></i></a>
                                            <a href="" class="btn badge-danger" title="Borrar datos juridicos" onclick="return confirm('¿Seguro que desea borrar datos jurídicos?')"><i class="fa fa-times"></i></a>
                                        </td>                                            
                                    @endif 
                                    -->  
                                    </tr>   
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regiapjuridico->appends(request()->input())->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
@endsection

@section('javascrpt')
@endsection