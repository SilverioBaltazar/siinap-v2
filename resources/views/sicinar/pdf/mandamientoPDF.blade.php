@extends('sicinar.pdf.layoutMandamiento')

@section('content')
    
    <!--<h1 class="page-header">Listado de productos</h1>-->
    <table class="table table-hover table-striped" align="center" width="100%">
        <thead>
            <tr>
                <td style="border:0; width:800px;">
                    <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="75px" height="55px" style="margin-right: 15px;"/>
                </td>
                <td style="border:0; width:800px;">
                    <img src="{{ asset('images/japem.jpg') }}" alt="JAPEM" width="65px" height="55px" style="margin-left: 15px;"/>
                    <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="60px" height="55px" style="margin-left:15px;"/>
                </td>
            </tr>
            <tr>
                <td style="border:0; width:800px; text-align:center;"><small>“2019. Año del centésimo  aniversario luctuoso de Emiliano Zapata Salazar. El Caudillo del Sur”</small>
                </td>
            </tr>        
        </thead>
    <!--</table>

    <table class="table table-hover table-striped" align="center" width="100%"> -->
        <tbody>

            <tr>
                <td style="border:0; width:800px;text-align:right;">Toluca de Lerdo, México a {{date('d')}} de {{strftime("%B")}} de {{date('Y')}}</td>
            </tr>
            <tr><td style="border:0; width:800px;text-align:right;">MANDAMIENTO ESCRITO No. JAP/________/_____</td></tr>        
            <tr><td style="border:0; width:800px;text-align:right;">No. DE REGISTRO JAP/______/_____ </td></tr>
            <tr>
            @foreach($regprogdil as $program)
                
                    <td style="border:0; width:800px;font-size: x-small;  text-align:center;vertical-align: middle;">
                    ____________________________________________; 
                    Secretario Ejecutivo de la Junta de Asistencia Privada del Estado de México, en el ejercicio de las funciones y atribuciones que me confiere el Artículo 24  Fracciones II y III de la Ley de Instituciones de Asistencia Privada del Estado de México, con fundamento en el artículo 16 de la Constitución Política de los Estados Unidos Mexicanos y los artículos 64 inciso a) fracciones II, III, IV, V, X, XII, XIII y XIV,  inciso b) fracción IV, 72, 73, 74, 92, 93, fracciones I, II, III, IV, 94 y 95 de la Ley de Instituciones de Asistencia Privada del Estado de México y 128 del Código de Procedimientos Administrativos del Estado de México, por este medio, le informo a usted que la Institución de Asistencia Privada que representa, será verificada el día <b>{{$program->dia_id}}</b> de <b> 
                    @foreach($regmeses as $mes)
                        @if($mes->mes_id == $program->mes_id)
                            {{$mes->mes_desc}}
                        @endif
                    @endforeach
                    </b>
                    del <b>{{$program->periodo_id}}</b>; por lo que deberá permitir el acceso al siguiente personal: <b>{{$program->visita_spub2}}</b>, adscrito a la Junta de Asistencia Privada del Estado de México, quien se identifica con gafete, que cuenta con fotografía al frente expedido y firmado autógrafamente por el Secretario Ejecutivo de esta Junta respectivamente, en las instalaciones de la institución denominada: 
                    <b> {{$program->iap_id}} </b>, I.A.P. que se ubica en: <b>{{$program->visita_dom}}</b>, con el objeto de  revisar los CRITERIOS DE VERIFICACIÓN, el CUMPLIMIENTO DEL OBJETO SOCIAL mismo que se describe a continuación: <b>{{$program->visita_obj}}</b> y TODA AQUELLA DOCUMENTACIÓN QUE SUSTENTEN LAS ACTIVIDADES ASISTENCIALES REALIZADAS POR LA INSTITUCIÓN, con fundamento en el artículo 22, fracción VII y articulo 64, fracción III, de la Ley de Instituciones de Asistencia Privada del Estado de México. No omito comentar, que en caso de existir alguna manifestación al respecto; los visitados, su representante o la persona que  atienda la verificación, podrá formular sus observaciones en el acto de la diligencia y ofrecer pruebas en relación a los hechos u omisiones contenidos en el acta de verificación o bien hacer uso de ese derecho, por escrito, dentro del término de los tres días siguientes a la fecha en que se hubiera levantado el acta; lo anterior con fundamento en el artículo 128 fracción X del Código de Procedimientos Administrativos del Estado de México.
                    <br><br>
                    Agradeciendo la atención brindada al presente, me despido de usted.
                    </td>

            @endforeach
            </tr>
            <tr>
                <td style="border:0;"></td>
            </tr>
            <tr>
                <td style="border:0;"></td>
            </tr>
            <tr>
                <td style="border:0;"></td>
            </tr>

            <tr><td style="text-align:center;">A T E N T A M E N T E</td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr>
               <td style="border:0; text-align:center;">NOMBRE Y FIRMA DEL SECRETARIO EJECUTIVO DE LA JAPEM</td>
            </tr>
            <tr>
                    <td style="border:0;"></td>
                </tr>
                <tr>
                    <td style="border:0;"></td>
                </tr>                                
                <tr>
                    <td style="border:0; text-align:left;"><b style="font-size: x-small;">c.c.p. NOMBRE DE LA DIRECTORA DEL DESARROLLO ASISTENCIA DE LA JAPEM.</b>
                    </td>
                </tr>

            <tr>
                <td style="border:0; text-align:right;"><b style="font-size: x-small;">SECRETARIA DE DESARROLLO SOCIAL</b>
                </td>
            </tr>
            <tr>
                <td style="border:0; text-align:right;"><b style="font-size: x-small;">JUNTA DE ASISTENCIA PRIVADA DEL ESTADO DE MÉXICO</b>
                </td>
            </tr>

        </tbody>
    </table>
    
    <!-- :::::::::::::::::::::::APARTADO 5::::::::::::::::::::::::: -->
    <table style="page-break-inside: avoid;" class="table table-hover table-striped" align="center">
        <thead>
            <tr>
                <td style="border:0; text-align:right;"><b style="font-size: x-small;">SECRETARIA DE DESARROLLO SOCIAL</b>
                </td>
            </tr>
            <tr>
                <td style="border:0; text-align:right;"><b style="font-size: x-small;">JUNTA DE ASISTENCIA PRIVADA DEL ESTADO DE MÉXICO</b>
                </td>
            </tr>
        </thead>
    </table>
@endsection