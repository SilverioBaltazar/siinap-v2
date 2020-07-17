@extends('sicinar.principal')

@section('title','Ver indicador de cumpliento de las IAPS')

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
            <h1>Tablero de control
                <small> Seleccionar alguna para editar o registrar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Tablero de control </a></li>
                <li><a href="#">Indicadores        </a></li>   
                <li><a href="#">Cumplimiento       </a></li>               
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="page-header" style="text-align:right;">
                            <label style="color:green;"><small><i class="fa fa-check"></i>Activas :</small></label>
                            @foreach($regtotactivas as $total_a)
                               <label style="color:green;"><small>{{$total_a->total_activas}}</small></label>
                            @endforeach
                            <label style="color:red;"><small><i class="fa fa-times"></i>  Inactivas :</small></label>
                            @foreach($regtotinactivas as $total_i)
                               <label style="color:red;"><small>{{$total_i->total_inactivas}} </small></label>
                            @endforeach
                            
                            Buscar  
                            {{ Form::open(['route' => 'buscarcumplimiento', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('idd', null, ['class' => 'form-control', 'placeholder' => 'id IAP']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre IAP']) }}
                                </div>
                                
                                <div class="form-group">
                                    {{ Form::text('bio', null, ['class' => 'form-control', 'placeholder' => 'Objeto social']) }}
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>                             
                            {{ Form::close() }}
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="font-size:10px;" class="justify">
                                    <tr>
                                        <th style="background-color:pink;text-align:left;   vertical-align: middle;">Id.                   </th>
                                        <th style="background-color:pink;text-align:left;   vertical-align: middle;">Nombre de la IAP      </th>

                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;">Acta     <br>Const.   </th>
                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;">Reg.     <br>IFREM    </th>
                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;">Sit.     <br>Inm.     </th>   
                                        <th style="background-color:green;color:white;text-align:center; vertical-align: middle;">Ult.     <br>Protocol.</th>

                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;">Padrón   <br>Benef.   </th>
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;">Plantilla<br>Personal </th>
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;">Prog.    <br>Trabajo  </th>
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;">Cédula   <br>det.nec. </th>
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;">Informe  <br>Labores  </th>

                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Inv Act. <br>Fijos    </th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Edos.Fin.<br>Bal.Comp.</th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Presup.  <br>Anual    </th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Const.   <br>Donativos</th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Dec.     <br>Anual    </th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Cuotas 5 <br>al millar</th> 

                                        <th style="background-color:pink;text-align:center; vertical-align: middle;">Activa   <br>Inact.   </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regiap as $iap)
                                    <tr>
                                        <td style="tfont-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:left; vertical-align: middle;">{{$iap->iap_id}}        
                                        </td>
                                        <td style="tfont-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:left; vertical-align: middle;">{{Trim($iap->iap_desc)}}
                                        </td>

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->iap_id == $iap->iap_id)
                                                    @if(!empty($rjuridico->iap_d12)&&(!is_null($rjuridico->iap_d12)))
                                                        <a href="/images/{{$rjuridico->iap_d12}}" class="btn btn-danger" title="Acta Constitutiva"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->iap_id == $iap->iap_id)
                                                    @if(!empty($rjuridico->iap_d13)&&(!is_null($rjuridico->iap_d13)))
                                                        <a href="/images/{{$rjuridico->iap_d13}}" class="btn btn-danger" title="Documento de registro en el IFREM"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Documento de registro en el IFREM" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>            

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->iap_id == $iap->iap_id)
                                                    @if(!empty($rjuridico->iap_d14)&&(!is_null($rjuridico->iap_d14)))
                                                        <a href="/images/{{$rjuridico->iap_d14}}" class="btn btn-danger" title="Documento de Situación del inmueble"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Documento de Situación del inmueble" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break 
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                    
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->iap_id == $iap->iap_id)
                                                    @if(!empty($rjuridico->iap_d15)&&(!is_null($rjuridico->iap_d15)))
                                                        <a href="/images/{{$rjuridico->iap_d15}}" class="btn btn-danger" title="Documento de ultima protocolización"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Documento de ultima protocolización" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                                                                                        
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regpadron as $padron)
                                                @if($padron->iap_id === $iap->iap_id)
                                                    @if($padron->total > 0)
                                                        <img src="{{asset('images/semaforo_verde.jpg')}}" width="15px" height="15px" title="Padrón de beneficiarios" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>({{$padron->total}})
                                                        @break                                                        
                                                    @endif                                                    
                                                @endif
                                            @endforeach 
                                            </small>
                                        </td>                                                                                                           
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regpersonal as $personal)
                                                @if($personal->iap_id == $iap->iap_id)
                                                    @if($personal->total > 0)
                                                        <img src="{{asset('images/semaforo_verde.jpg')}}" width="15px" height="15px" title="Plantilla de personal" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> ({{$personal->total}})
                                                        @break                                                       
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                                                              

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regprogtrab as $progtrab)
                                                @if($progtrab->iap_id == $iap->iap_id)
                                                    @if($progtrab->total > 0)
                                                        @foreach($totactivs as $actividad)
                                                            @if($actividad->totactividades > 0)
                                                                <img src="{{asset('images/semaforo_verde.jpg')}}" width="15px" height="15px" title="Programa de trabajo" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>({{$progtrab->total}}/{{$actividad->totactividades}})
                                                                @break                                                        
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>       

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcedula as $cedula)
                                                @if($cedula->iap_id == $iap->iap_id)
                                                    @if($cedula->total > 0)
                                                        @foreach($totarticulos as $articulo)
                                                            @if($articulo->totarticulos > 0)
                                                                <img src="{{asset('images/semaforo_verde.jpg')}}" width="15px" height="15px" title="Cédula de detección de necesidades" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                                                @break                                                         
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>       

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regprogdtrab as $informe)
                                                @if($informe->iap_id == $iap->iap_id)
                                                    @if(($informe->metac1+$informe->metac2+$informe->metac3+$informe->metac4) > 0)
                                                        <img src="{{asset('images/semaforo_verde.jpg')}}" width="15px" height="15px" title="Informe de labores" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                                                              

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($reginventario as $inventario)
                                                @if($inventario->iap_id == $iap->iap_id)
                                                    @if($inventario->total > 0)
                                                        <img src="{{asset('images/semaforo_verde.jpg')}}" width="15px" height="15px" title="Inventario de activos fijos" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                                                              
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regbalanza as $balanza)
                                                @if($balanza->iap_id == $iap->iap_id)
                                                    @if(!empty($balanza->edofinan_foto1)&&(!is_null($balanza->edofinan_foto1)))
                                                        <a href="/images/{{$balanza->edofinan_foto1}}" class="btn btn-danger" title="Estados financieros y Balanza de comprobación"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Estados financieros y Balanza de comprobación" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->iap_id == $iap->iap_id)
                                                    @if(!empty($contable->iap_d7)&&(!is_null($contable->iap_d7)))
                                                        <a href="/images/{{$contable->iap_d7}}" class="btn btn-danger" title="Presupuesto anual"><i class="fa fa-file-excel-o"></i><small>Excel</small>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Presupuesto anual" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->iap_id == $iap->iap_id)
                                                    @if(!empty($contable->iap_d8)&&(!is_null($contable->iap_d8)))
                                                        <a href="/images/{{$contable->iap_d8}}" class="btn btn-danger" title="Constancia de autorización para recibir donativos"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Constancia de autorización para recibir donativos" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break 
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>           
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->iap_id == $iap->iap_id)
                                                    @if(!empty($contable->iap_d9)&&(!is_null($contable->iap_d9)))
                                                        <a href="/images/{{$contable->iap_d9}}" class="btn btn-danger" title="Declaración anual"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Declaración anual" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break 
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                                                                           
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->iap_id == $iap->iap_id)
                                                    @if(!empty($contable->iap_d10)&&(!is_null($contable->iap_d10)))
                                                        <a href="/images/{{$contable->iap_d10}}" class="btn btn-danger" title="Cuotas 5 al millar"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Cuotas 5 al millar" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                                                                           

                                        @if($iap->iap_status == 'S')
                                            <td style="font-family:'Arial, Helvetica, sans-serif'; font-size:10px; text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="tfont-family:'Arial, Helvetica, sans-serif'; font-size:10px; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                                                                
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