@extends('sicinar.principal')

@section('title','Ver requisitos contables')

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
            <h1>Menu 
                <small>Requisitos contables - Otros requisitos - Seleccionar para editar o registrar</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Requisitos contables  </a></li>
                <li><a href="#">Otros requisitos      </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('nuevoReqc')}}" class="btn btn-primary btn_xs" title="Registrar Requisitos contables"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Registrar requisitos contables</a>
                        </div>                        
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.              </th>
                                        <th style="text-align:left;   vertical-align: middle;">IAP              </th>
                                        <th style="text-align:center; vertical-align: middle;">Presup.<br>Anual </th>
                                        <th style="text-align:center; vertical-align: middle;">Constan.<br>Recibir<br>Donativos
                                        </th>
                                        <th style="text-align:center; vertical-align: middle;">Declar.<br>Anual </th>
                                        <th style="text-align:center; vertical-align: middle;">Cuotas 5<br>al millar</th>
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($regcontable as $contable)
                                    <tr>
                                        @foreach($regiap as $iap)
                                            @if($iap->iap_id == $contable->iap_id)
                                                <td style="text-align:left; vertical-align: middle;">{{$iap->iap_id}}</td>
                                                <td style="text-align:left; vertical-align: middle;">{{Trim($iap->iap_desc)}}
                                                </td>                                                        
                                                @break
                                            @endif
                                        @endforeach    

                                        @if(!empty($contable->iap_d7)&&(!is_null($contable->iap_d7)))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Presupuesto anual">
                                                <a href="/images/{{$contable->iap_d7}}" class="btn btn-success" title="Presupuesto anual"><i class="fa fa-file-excel-o"></i><small>Excel</small>
                                                </a>
                                                <a href="{{route('editarReqc7',$contable->iap_id)}}" class="btn badge-warning" title="Editar Presupuesto anual"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Presupuesto anual"><i class="fa fa-times"></i>
                                                <a href="{{route('editarReqc7',$contable->iap_id)}}" class="btn badge-warning" title="Editar Presupuesto anual"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>   
                                        @endif
                                        @if(!empty($contable->iap_d8)&&(!is_null($contable->iap_d8)))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Contancia para recibir donativos">
                                                <a href="/images/{{$contable->iap_d8}}" class="btn btn-danger" title="Contancia para recibir donativos"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                                </a>
                                                <a href="{{route('editarReqc8',$contable->iap_id)}}" class="btn badge-warning" title="Editar Contancia para recibir donativos"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Contancia para recibir donativos"><i class="fa fa-times"></i>
                                                <a href="{{route('editarReqc8',$contable->iap_id)}}" class="btn badge-warning" title="Editar Contancia para recibir donativos"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>   
                                        @endif
                                        @if(!empty($contable->iap_d9)&&(!is_null($contable->iap_d9)))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Declaración anual">
                                                <a href="/images/{{$contable->iap_d9}}" class="btn btn-danger" title="Declaración anual"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                                </a>
                                                <a href="{{route('editarReqc9',$contable->iap_id)}}" class="btn badge-warning" title="Editar Declaración anual"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Declaración anual"><i class="fa fa-times"></i>
                                                <a href="{{route('editarReqc9',$contable->iap_id)}}" class="btn badge-warning" title="Editar Declaración anual"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>   
                                        @endif
                                        @if(!empty($contable->iap_d10)&&(!is_null($contable->iap_d10)))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Cuotas de 5 al millar">
                                                <a href="/images/{{$contable->iap_d10}}" class="btn btn-danger" title="Cuotas de 5 al millar"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                                </a>
                                                <a href="{{route('editarReqc10',$contable->iap_id)}}" class="btn badge-warning" title="Editar Cuotas de 5 al millar"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Cuotas de 5 al millar"><i class="fa fa-times"></i>
                                                <a href="{{route('editarReqc10',$contable->iap_id)}}" class="btn badge-warning" title="Editar Cuotas de 5 al millar"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>   
                                        @endif


                                        <td style="text-align:center; vertical-align: middle;">
                                            <a href="{{route('editarReqc',$contable->iap_id)}}" class="btn badge-warning" title="Editar requisitos contables"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarReqc',$contable->iap_id)}}" class="btn badge-danger" title="Borrar registro" onclick="return confirm('¿Seguro que desea borrar requisitos contables?')"><i class="fa fa-times"></i>
                                            </a>                                            
                                        </td>
                    
                                    </tr>   
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regcontable->appends(request()->input())->links() !!}
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
