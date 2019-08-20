@extends('sicinar.principal')

@section('title','Ver IAPS')

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
            <h1>Instituciones de Asistencia Privada (IAPS)
                <small> Seleccionar alguna para editar o registrar nueva IAP</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Instituciones de Asistencia Privada (IAPS) </a></li>
                <li><a href="#">IAPS  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('downloadiap')}}" class="btn btn-success" title="Exportar catálogo de IAPS (formato Excel)"><i class="fa fa-file-excel-o"></i> Excel</a>                            
                            <a href="{{route('catiapPDF')}}" class="btn btn-danger" title="Exportar catálogo de IAPS (formato PDF)"><i class="fa fa-file-pdf-o"></i> PDF</a>
                            <a href="{{route('nuevaIap')}}"   class="btn btn-primary btn_xs" title="Alta de nueva IAP"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nueva IAP</a>                             
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.              </th>
                                        <th style="text-align:left;   vertical-align: middle;">Nombre de la IAP </th>
                                        <th style="text-align:left;   vertical-align: middle;">Calle            </th>     
                                        <th style="text-align:left;   vertical-align: middle;">No. Ext./Int.    </th>
                                        <th style="text-align:left;   vertical-align: middle;">Colonia          </th>
                                        <th style="text-align:center; vertical-align: middle;">Activa / Inactiva</th>
                                        <th style="text-align:center; vertical-align: middle;">Fecha registro   </th>
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regiap as $iap)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$iap->iap_id}}        </td>
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($iap->iap_desc)}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$iap->iap_calle}}     </td>
                                        <td style="text-align:left; vertical-align: middle;">{{$iap->iap_num}}       </td>
                                        <td style="text-align:left; vertical-align: middle;">{{$iap->iap_colonia}}   </td>
                                        @if($iap->iap_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;">{{date("d/m/Y", strtotime($iap->iap_fecreg))}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editarIap',$iap->iap_id)}}" class="btn badge-warning" title="Editar IAP"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarIap',$iap->iap_id)}}" class="btn badge-danger" title="Borrar IAP" onclick="return confirm('¿Seguro que desea borrar la IAP?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regiap->appends(request()->input())->links() !!}
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