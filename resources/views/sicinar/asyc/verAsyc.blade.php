@extends('sicinar.principal')

@section('title','Ver información de asistencia social y contable')

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
            <h1>Asistencia Social y Contable
                <small> Seleccionar registro para editar o registrar nueva información</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">IAPS </a></li>
                <li><a href="#">Información de asistencia social y contable  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('nuevaAsyc')}}"   class="btn btn-primary btn_xs" title="Registrar datos de asistencia social y contable"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Registrar datos de asistencia social y contable</a>
                        </div>                        

                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:center; vertical-align: middle;">Id.<br>Trx          </th>
                                        <th style="text-align:center; vertical-align: middle;">IAP                 </th>
                                        <th style="text-align:center; vertical-align: middle;">Per.<br>Fiscal      </th>
                                        <th style="text-align:center; vertical-align: middle;">Padrón<br>Benef.    </th>
                                        <th style="text-align:center; vertical-align: middle;">Listado<br>Personal </th>
                                        <th style="text-align:center; vertical-align: middle;">Necesid.<br>Capacit.</th>
                                        <th style="text-align:center; vertical-align: middle;">Inv.<br>Activo<br>Fijo</th>
                                        <th style="text-align:center; vertical-align: middle;">Presup.             </th>
                                        <th style="text-align:center; vertical-align: middle;">Prog.<br>Trabajo    </th>
                                        <th style="text-align:center; vertical-align: middle;">Const.<br>Aut.<br>Donac.</th>
                                        <th style="text-align:center; vertical-align: middle;">Declarac.<br>Ante<br>SAT</th>
                                        <th style="text-align:center; vertical-align: middle;">Cuotas<br>5 al <br>millar   </th>
                                        <th style="text-align:center; vertical-align: middle;">Edos.<br>Finan.</th> 
                                        <th style="text-align:center; vertical-align: middle; width:100px;" colspan="1">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regasyc as $asyc)
                                        <tr>
                                           <td style="text-align:left; vertical-align: middle;">{{$asyc->iap_folio}}</td> 
                                            <td style="text-align:left; vertical-align: middle;">
                                                @foreach($regiap as $iap)
                                                    @if($iap->iap_id == $iap->iap_id)
                                                        {{$iap->iap_desc}}
                                                        @break
                                                    @endif
                                                @endforeach 
                                            </td>
                                            <td style="text-align:left; vertical-align: middle;">{{$asyc->periodo_id}}</td>
                                     
                                            @if(!empty($asyc->iap_d01)&&(!is_null($asyc->iap_d01)))
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Padrón de beneficiarios">
                                                <a href="/images/{{$asyc->iap_d01}}" class="btn btn-success width:80px; height:20px;" title="Padron de beneficiarios"><i class="fa fa-file-excel-o"></i><small>Excel</small></a>
                                                <a href="{{route('editarAsyc',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar padron de beneficiarios"><i class="fa fa-edit"></i></a>
                                                </td>
                                            @else
                                                <td style="color:darkred; text-align:center; vertical-align: middle;" title="Sin padron de beneficiarios"><i class="fa fa-times"></i>
                                                    <a href="{{route('editarAsyc',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar padron de beneficiarios"><i class="fa fa-edit"></i></a>
                                                </td>                                                   
                                            @endif
                                            
                                            @if(!empty($asyc->iap_d02)&&(!is_null($asyc->iap_d02)))
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Lista de personal">
                                                    <a href="/images/{{$asyc->iap_d02}}" class="btn btn-success" title="Lista de personal"><i class="fa fa-file-excel-o"></i><small>Excel</small>
                                                    </a>
                                                    <a href="{{route('editarAsyc2',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Lista de personal"><i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            @else
                                                <td style="color:darkred; text-align:center; vertical-align: middle;" title="Sin Lista de personal (recursos humanos)"><i class="fa fa-times"></i>
                                                    <a href="{{route('editarAsyc2',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Lista de personal"><i class="fa fa-edit"></i></a>
                                                </td>   
                                            @endif
                                            @if(!empty($asyc->iap_d03)&&(!is_null($asyc->iap_d03)))
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Necesidades de capacitación">
                                                <a href="/images/{{$asyc->iap_d03}}" class="btn btn-success" title="Necesidades de capacitación"><i class="fa fa-file-excel-o"></i><small>Excel</small></a>
                                                <a href="{{route('editarAsyc3',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Necesidades de capacitación"><i class="fa fa-edit"></i></a>
                                                </td>
                                            @else
                                                <td style="color:darkred; text-align:center; vertical-align: middle;" title="Necesidades de capaciatación"><i class="fa fa-times"></i>
                                                    <a href="{{route('editarAsyc3',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Necesidades de capacitación"><i class="fa fa-edit"></i></a>
                                                </td>   
                                            @endif
                                            @if(!empty($asyc->iap_d04)&&(!is_null($asyc->iap_d04)))
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Inventario de activo fisico">
                                                    <a href="/images/{{$asyc->iap_d04}}" class="btn btn-success" title="Inventario de activo fisico"><i class="fa fa-file-excel-o"></i><small>Excel</small></a>
                                                    <a href="{{route('editarAsyc4',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Lista de personal"><i class="fa fa-edit"></i></a>
                                                </td>
                                            @else
                                                <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inventario de activo fisico"><i class="fa fa-times"></i>
                                                    <a href="{{route('editarAsyc4',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Lista de personal"><i class="fa fa-edit"></i></a>
                                                </td>   
                                            @endif                              
                                                      
                                            @if(!empty($asyc->iap_d05)&&(!is_null($asyc->iap_d05)))
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Presupuesto anual">
                                                    <a href="/images/{{$asyc->iap_d05}}" class="btn btn-success" title="Presupuesto anual"><i class="fa fa-file-excel-o"></i><small>Excel</small></a>
                                                    <a href="{{route('editarAsyc5',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Presupuesto anual"><i class="fa fa-edit"></i></a>
                                                </td>
                                            @else
                                                <td style="color:darkred; text-align:center; vertical-align: middle;" title="Presupuesto anual"><i class="fa fa-times"></i>
                                                    <a href="{{route('editarAsyc5',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Presupuesto anual"><i class="fa fa-edit"></i></a>
                                                </td>   
                                            @endif

                                            @if(!empty($asyc->iap_d06)&&(!is_null($asyc->iap_d06)))
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Programa de trabajo">
                                                    <a href="/images/{{$asyc->iap_d06}}" class="btn btn-success" title="Programa de trabajo"><i class="fa fa-file-excel-o"></i><small>Excel</small></a>
                                                    <a href="{{route('editarAsyc6',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Programa de trabajo"><i class="fa fa-edit"></i></a>                                                    
                                                </td>
                                            @else
                                                <td style="color:darkred; text-align:center; vertical-align: middle;" title="Programa de trabajo."><i class="fa fa-times"></i>
                                                    <a href="{{route('editarAsyc6',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Programa de trabajo"><i class="fa fa-edit"></i></a>  
                                                </td>   
                                            @endif
                                            
            
                                            @if(!empty($asyc->iap_d07)&&(!is_null($asyc->iap_d07)))
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Constancia de autorización de donaciones">
                                                    <a href="/images/{{$asyc->iap_d07}}" class="btn btn-danger" title="Constancia de autorización de donaciones"><i class="fa fa-file-pdf-o"></i><small>PDF</small></a>
                                                    <a href="{{route('editarAsyc7',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Constancia de autoriazación de donaciones"><i class="fa fa-edit"></i></a>
                                                </td>
                                            @else
                                                <td style="color:darkred; text-align:center; vertical-align: middle;" title="Constancia de autorización de donaciones"><i class="fa fa-times"></i>
                                                    <a href="{{route('editarAsyc7',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Constancia de autoriazación de donaciones"><i class="fa fa-edit"></i></a>
                                                </td>   
                                            @endif
                                            
                                            @if(!empty($asyc->iap_d08)&&(!is_null($asyc->iap_d08)))
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Constancia de cumplimiento de declaración ante el SAT">
                                                    <a href="/images/{{$asyc->iap_d08}}" class="btn btn-danger" title="Constancia de cumplimiento ante el SAT"><i class="fa fa-file-pdf-o"></i><small>PDF</small></a>
                                                    <a href="{{route('editarAsyc8',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Constancia de cumplimiento de declaración ante el SAT"><i class="fa fa-edit"></i></a>
                                                </td>
                                            @else
                                                <td style="color:darkred; text-align:center; vertical-align: middle;" title="Constancia de cumplimiento de declaración ante el SAT"><i class="fa fa-times"></i>
                                                    <a href="{{route('editarAsyc8',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Constancia de cumplimiento de declaración ante el SAT"><i class="fa fa-edit"></i></a>
                                                </td>   
                                            @endif

                                            @if(!empty($asyc->iap_d09)&&(!is_null($asyc->iap_d09)))
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Reporte de cuotas 5 al millar">
                                                    <a href="/images/{{$asyc->iap_d09}}" class="btn btn-success" title="Reporte de cuotas 5 al millar"><i class="fa fa-file-excel-o"></i><small>Excel</small></a>
                                                    <a href="{{route('editarAsyc9',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Reporte de cuotas 5 al millar"><i class="fa fa-edit"></i></a>                                                    
                                                </td>
                                            @else
                                                <td style="color:darkred; text-align:center; vertical-align: middle;" title="Reporte de cuotas 5 al millar"><i class="fa fa-times"></i>
                                                    <a href="{{route('editarAsyc9',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Reporte de cuotas 5 al millar"><i class="fa fa-edit"></i></a>
                                                </td>   
                                            @endif


                                            @if(!empty($asyc->iap_d10)&&(!is_null($asyc->iap_d10)))
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Estados financieros">
                                                    <a href="/images/{{$asyc->iap_d10}}" class="btn btn-success" title="Estados financieros"><i class="fa fa-file-excel-o"></i><small>Excel </small></a>
                                                    <a href="{{route('editarAsyc10',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Estados financieros"><i class="fa fa-edit"></i></a>
                                                </td>
                                            @else
                                                <td style="color:darkred; text-align:center; vertical-align: middle;" title="Estados financieros"><i class="fa fa-times"></i>
                                                    <a href="{{route('editarAsyc10',$asyc->iap_folio)}}" class="btn badge-warning" title="Editar Estados financieros"><i class="fa fa-edit"></i></a>
                                                </td>   
                                            @endif                                               
                                            
                                            <td style="text-align:center; vertical-align: middle;">
                                               <a href="{{route('borrarAsyc',$asyc->iap_folio)}}" class="btn badge-danger" title="Borrar registro" onclick="return confirm('¿Seguro que desea borrar registro de información de asistencia social y contable?')"><i class="fa fa-times"></i></a>
                                            </td>                                          
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regasyc->appends(request()->input())->links() !!}
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