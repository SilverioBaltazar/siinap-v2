<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regPadronModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPadronExcel implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'FOLIO',
            'IAP',
            'FECHA_INGRESO',
            'APELLIDO_PATERNO',
            'APELLIDO_MATERNO',
            'NOMBRES',
            'FECHA_NACIMIENTO',
            'CURP',
            'SEXO',
            'DOMICILIO',
            'CP',            
            'COLONIA',
            'ENTIDAD_FEDERATIVA',
            'MUNICIPIO',
            'MOTIVO_INGRESO',
            'INTEG_FAMILIA',
            'SERVICIO',
            'CUOTA_RECUP',
            'QUIEN_CANALIZO',
            'STATUS',
            'FECHA_REGISTRO'
        ];
    }

    public function collection()
    {
        $arbol_id     = session()->get('arbol_id');        
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            //return regPadronModel::join('JP_CAT_MUNICIPIOS_SEDESEM','JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=',
            //                                                        'JP_METADATO_PADRON.MUNICIPIO_ID') 
            //                ->wherein('JP_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9,15,22])
            return regPadronModel::join('JP_CAT_ENTIDADES_FED','JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                               'JP_METADATO_PADRON.ENTIDAD_FED_ID')
                            ->join('JP_CAT_SERVICIOS'  ,'JP_CAT_SERVICIOS.SERVICIO_ID','=','JP_METADATO_PADRON.SERVICIO_ID')
                            ->join('JP_IAPS'           ,'JP_IAPS.IAP_ID'              ,'=','JP_METADATO_PADRON.IAP_ID')
                            ->select('JP_METADATO_PADRON.FOLIO',
                                     'JP_IAPS.IAP_DESC'        ,  
                                     'JP_METADATO_PADRON.FECHA_INGRESO2', 
                                     'JP_METADATO_PADRON.PRIMER_APELLIDO',
                                     'JP_METADATO_PADRON.SEGUNDO_APELLIDO',
                                     'JP_METADATO_PADRON.NOMBRES',
                                     'JP_METADATO_PADRON.FECHA_NACIMIENTO2',     
                                     'JP_METADATO_PADRON.CURP',     
                                     'JP_METADATO_PADRON.SEXO',     
                                     'JP_METADATO_PADRON.DOMICILIO',     
                                     'JP_METADATO_PADRON.CP', 
                                     'JP_METADATO_PADRON.COLONIA',
                                     'JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
                                     'JP_METADATO_PADRON.LOCALIDAD',         
                                     'JP_METADATO_PADRON.MOTIVO_ING',
                                     'JP_METADATO_PADRON.INTEG_FAM', 
                                     'JP_CAT_SERVICIOS.SERVICIO_DESC', 
                                     'JP_METADATO_PADRON.CUOTA_RECUP',
                                     'JP_METADATO_PADRON.QUIEN_CANALIZO', 
                                     'JP_METADATO_PADRON.STATUS_1',  
                                     'JP_METADATO_PADRON.FECHA_REG'
                                    )
                            ->orderBy('JP_METADATO_PADRON.NOMBRE_COMPLETO','ASC')
                            ->get();                               
        }else{
            return regPadronModel::join('JP_CAT_ENTIDADES_FED','JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                               'JP_METADATO_PADRON.ENTIDAD_FED_ID')
                            ->join('JP_CAT_SERVICIOS'  ,'JP_CAT_SERVICIOS.SERVICIO_ID','=','JP_METADATO_PADRON.SERVICIO_ID')
                            ->join('JP_IAPS'           ,'JP_IAPS.IAP_ID'              ,'=','JP_METADATO_PADRON.IAP_ID')
                            ->select('JP_METADATO_PADRON.FOLIO',
                                     'JP_IAPS.IAP_DESC'        ,  
                                     'JP_METADATO_PADRON.FECHA_INGRESO2', 
                                     'JP_METADATO_PADRON.PRIMER_APELLIDO',
                                     'JP_METADATO_PADRON.SEGUNDO_APELLIDO',
                                     'JP_METADATO_PADRON.NOMBRES',
                                     'JP_METADATO_PADRON.FECHA_NACIMIENTO2',     
                                     'JP_METADATO_PADRON.CURP',     
                                     'JP_METADATO_PADRON.SEXO',     
                                     'JP_METADATO_PADRON.DOMICILIO',     
                                     'JP_METADATO_PADRON.CP', 
                                     'JP_METADATO_PADRON.COLONIA',
                                     'JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
                                     'JP_METADATO_PADRON.LOCALIDAD',          
                                     'JP_METADATO_PADRON.MOTIVO_ING',
                                     'JP_METADATO_PADRON.INTEG_FAM', 
                                     'JP_CAT_SERVICIOS.SERVICIO_DESC', 
                                     'JP_METADATO_PADRON.CUOTA_RECUP',
                                     'JP_METADATO_PADRON.QUIEN_CANALIZO', 
                                     'JP_METADATO_PADRON.STATUS_1',
                                     'JP_METADATO_PADRON.FECHA_REG'
                                    )
                            ->where('JP_METADATO_PADRON.IAP_ID',$arbol_id)
                            ->orderBy('JP_METADATO_PADRON.NOMBRE_COMPLETO','ASC')
                            ->get();               
        }                            
    }
}
