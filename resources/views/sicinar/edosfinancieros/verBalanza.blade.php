@extends('sicinar.principal')

@section('title','Ver Estados financieros')

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
            <h1>Requisitos contables
                <small> Seleccionar edo. financiero para editar o registrar</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Requisitos contables </a></li>
                <li><a href="#">Edos. financieros  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('nuevaBalanza')}}" class="btn btn-primary btn_xs" title="Registrar Edo. financiero"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Registrar Edo. financiero</a>
                        </div>                        
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Periodo<br>Fiscal    </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Folio                </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Periodicidad         </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Semestre             </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">IAP                  </th>

                                        <th style="text-align:center; vertical-align: middle;font-size:11px;">$ Donativos<br>Efectivo</th>
                                        <th style="text-align:center; vertical-align: middle;font-size:11px;">$ Donativos<br>Especie </th>
                                        <th style="text-align:center; vertical-align: middle;font-size:11px;">Edo. <br>Financiero... </th>
                                        <th style="text-align:center; vertical-align: middle;font-size:11px;width:100px;">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($regbalanza as $balanza)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">{{$balanza->periodo_id}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">{{$balanza->edofinan_folio}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">
                                        @foreach($regperiodicidad as $per)
                                            @if($per->per_id == $balanza->per_id)
                                                {{Trim($per->per_desc)}}
                                                @break
                                            @endif
                                        @endforeach                                            
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;font-size:11px;">{{$balanza->num_id}}</td>
                                                                                
                                        @foreach($regiap as $iap)
                                            @if($iap->iap_id == $balanza->iap_id)
                                                <td style="text-align:left; vertical-align: middle;font-size:11px;">{{$iap->iap_id.' '.Trim($iap->iap_desc)}}
                                                </td>                                                        
                                                @break
                                            @endif
                                        @endforeach    
                                        <td style="text-align:center; vertical-align: middle;font-size:11px;">{{number_format($balanza->ids_dreef,2)}}</td>
                                        <td style="text-align:center; vertical-align: middle;font-size:11px;">{{number_format($balanza->ids_drees,2)}}</td>
                                        
                                        @if(!empty($balanza->edofinan_foto1)&&(!is_null($balanza->edofinan_foto1)))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;font-size:10px;" title="Edo. financiero y Balanza de comprobación">
                                                <a href="/images/{{$balanza->edofinan_foto1}}" class="btn btn-danger" title="Edo. financiero y Balanza de comprobación"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                                </a>
                                                <a href="{{route('editarBalanza1',$balanza->edofinan_folio)}}" class="btn badge-warning" title="Editar Edo. financiero y Balanza de comprobación"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;font-size:10px;" title="Edo. financiero y Balanza de comprobación"><i class="fa fa-times"></i>
                                                <a href="{{route('editarBalanza1',$balanza->edofinan_folio)}}" class="btn badge-warning" title="Editar Edo. financiero y Balanza de comprobación"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>   
                                        @endif

                                        <td style="text-align:center; vertical-align: middle;font-size:10px;">
                                            <a href="{{route('editarBalanza',$balanza->edofinan_folio)}}" class="btn badge-warning" title="Editar edo. financiero y Balnaza de comprobación"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarBalanza',$balanza->edofinan_folio)}}" class="btn badge-danger" title="Borrar edo. financiero y Balanza de comprobación" onclick="return confirm('¿Seguro que desea borrar edo. financiero y Balanza de comprobación?')"><i class="fa fa-times"></i>
                                            </a>                                            
                                        </td>
                    
                                    </tr>   
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regbalanza->appends(request()->input())->links() !!}
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
