<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regProgtrabModel;
use App\regProgdtrabModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportProgtrabExcel implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'FOLIO',
            'PERIODO',
            'FECHA_ELAB',
            'IAP_ID',            
            'IAP',            
            'RESPONSABLE',
            'PROGRAMA',
            'ACTIVIDAD',
            'OBJETIVO',
            'UNID_MEDIDA',
            'ELABORO',
            'AUTORIZO',
            'OBSERVACIONES',
            'ESTADO',
            'FECHA_REGISTRO',            
            'ENE',
            'FEB',
            'MAR',
            'ABR',
            'MAY',            
            'JUN',
            'JUL',
            'AGO',
            'SEP',
            'OCT',
            'NOV',            
            'DIC',
            'TOTAL_META_PROGRAMADA'
        ];
    }

    public function collection()
    {
        $arbol_id     = session()->get('arbol_id');  
        //$id           = session()->get('sfolio');        
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            //JP_PROGRAMA_ETRABAJO on JP_PROGRAMA_ETRABAJO.FOLIO   = JP_PROGRAMA_DTRABAJO.FOLIO 
            //            inner join JP_IAPS              on JP_IAPS.IAP_ID               = JP_PROGRAMA_ETRABAJO.IAP_ID 
            //            inner join JP_CAT_UNID_MEDIDA   on JP_CAT_UNID_MEDIDA.UMEDIDA_ID= JP_PROGRAMA_DTRABAJO.UMEDIDA_ID
            return regProgdtrabModel::join('JP_PROGRAMA_ETRABAJO','JP_PROGRAMA_ETRABAJO.FOLIO','=',
                                                                  'JP_PROGRAMA_DTRABAJO.FOLIO')
                   ->join('JP_IAPS'            ,'JP_IAPS.IAP_ID',               '=','JP_PROGRAMA_ETRABAJO.IAP_ID')
                   ->join('JP_CAT_UNID_MEDIDA' ,'JP_CAT_UNID_MEDIDA.UMEDIDA_ID','=','JP_PROGRAMA_DTRABAJO.UMEDIDA_ID')
                   ->select('JP_PROGRAMA_ETRABAJO.FOLIO', 
                            'JP_PROGRAMA_ETRABAJO.PERIODO_ID', 
                            'JP_PROGRAMA_ETRABAJO.FECHA_ELAB2', 
                            'JP_IAPS.IAP_ID',               
                            'JP_IAPS.IAP_DESC',        
                            'JP_PROGRAMA_ETRABAJO.RESPONSABLE', 
                            'JP_PROGRAMA_DTRABAJO.PROGRAMA_DESC', 
                            'JP_PROGRAMA_DTRABAJO.ACTIVIDAD_DESC', 
                            'JP_PROGRAMA_DTRABAJO.OBJETIVO_DESC',
                            'JP_CAT_UNID_MEDIDA.UMEDIDA_DESC', 
                            'JP_PROGRAMA_ETRABAJO.ELABORO', 
                            'JP_PROGRAMA_ETRABAJO.AUTORIZO', 
                            'JP_PROGRAMA_ETRABAJO.OBS_1', 
                            'JP_PROGRAMA_ETRABAJO.STATUS_1', 
                            'JP_PROGRAMA_ETRABAJO.FECREG',                                   
                            'JP_PROGRAMA_DTRABAJO.MESP_01', 'JP_PROGRAMA_DTRABAJO.MESP_02', 'JP_PROGRAMA_DTRABAJO.MESP_03', 
                            'JP_PROGRAMA_DTRABAJO.MESP_04', 'JP_PROGRAMA_DTRABAJO.MESP_05', 'JP_PROGRAMA_DTRABAJO.MESP_06', 
                            'JP_PROGRAMA_DTRABAJO.MESP_07', 'JP_PROGRAMA_DTRABAJO.MESP_08', 'JP_PROGRAMA_DTRABAJO.MESP_09', 
                            'JP_PROGRAMA_DTRABAJO.MESP_10', 'JP_PROGRAMA_DTRABAJO.MESP_11', 'JP_PROGRAMA_DTRABAJO.MESP_12' 
                            )
                   ->selectRaw('(JP_PROGRAMA_DTRABAJO.MESP_01+JP_PROGRAMA_DTRABAJO.MESP_02+JP_PROGRAMA_DTRABAJO.MESP_03+
                                 JP_PROGRAMA_DTRABAJO.MESP_04+JP_PROGRAMA_DTRABAJO.MESP_05+JP_PROGRAMA_DTRABAJO.MESP_06+
                                 JP_PROGRAMA_DTRABAJO.MESP_07+JP_PROGRAMA_DTRABAJO.MESP_08+JP_PROGRAMA_DTRABAJO.MESP_09+
                                 JP_PROGRAMA_DTRABAJO.MESP_10+JP_PROGRAMA_DTRABAJO.MESP_11+JP_PROGRAMA_DTRABAJO.MESP_12)
                                 META_PROGRAMADA')
                   ->where('JP_PROGRAMA_ETRABAJO.FOLIO',$id)
                   ->orderBy('JP_PROGRAMA_ETRABAJO.PERIODO_ID','ASC')                   
                   ->orderBy('JP_PROGRAMA_ETRABAJO.FOLIO','ASC')
                   ->get();                               
        }else{
            return regProgdtrabModel::join('JP_PROGRAMA_ETRABAJO','JP_PROGRAMA_ETRABAJO.FOLIO','=',
                                                                  'JP_PROGRAMA_DTRABAJO.FOLIO')
                   ->join('JP_IAPS'            ,'JP_IAPS.IAP_ID',               '=','JP_PROGRAMA_ETRABAJO.IAP_ID')
                   ->join('JP_CAT_UNID_MEDIDA' ,'JP_CAT_UNID_MEDIDA.UMEDIDA_ID','=','JP_PROGRAMA_DTRABAJO.UMEDIDA_ID')
                   ->select('JP_PROGRAMA_ETRABAJO.FOLIO', 
                            'JP_PROGRAMA_ETRABAJO.PERIODO_ID', 
                            'JP_PROGRAMA_ETRABAJO.FECHA_ELAB2', 
                            'JP_IAPS.IAP_ID',               
                            'JP_IAPS.IAP_DESC',        
                            'JP_PROGRAMA_ETRABAJO.RESPONSABLE', 
                            'JP_PROGRAMA_DTRABAJO.PROGRAMA_DESC', 
                            'JP_PROGRAMA_DTRABAJO.ACTIVIDAD_DESC', 
                            'JP_PROGRAMA_DTRABAJO.OBJETIVO_DESC',
                            'JP_CAT_UNID_MEDIDA.UMEDIDA_DESC', 
                            'JP_PROGRAMA_ETRABAJO.ELABORO', 
                            'JP_PROGRAMA_ETRABAJO.AUTORIZO', 
                            'JP_PROGRAMA_ETRABAJO.OBS_1', 
                            'JP_PROGRAMA_ETRABAJO.STATUS_1', 
                            'JP_PROGRAMA_ETRABAJO.FECREG',                         
                            'JP_PROGRAMA_DTRABAJO.MESP_01', 'JP_PROGRAMA_DTRABAJO.MESP_02', 'JP_PROGRAMA_DTRABAJO.MESP_03', 
                            'JP_PROGRAMA_DTRABAJO.MESP_04', 'JP_PROGRAMA_DTRABAJO.MESP_05', 'JP_PROGRAMA_DTRABAJO.MESP_06', 
                            'JP_PROGRAMA_DTRABAJO.MESP_07', 'JP_PROGRAMA_DTRABAJO.MESP_08', 'JP_PROGRAMA_DTRABAJO.MESP_09', 
                            'JP_PROGRAMA_DTRABAJO.MESP_10', 'JP_PROGRAMA_DTRABAJO.MESP_11', 'JP_PROGRAMA_DTRABAJO.MESP_12' 
                            )
                   ->selectRaw('(JP_PROGRAMA_DTRABAJO.MESP_01+JP_PROGRAMA_DTRABAJO.MESP_02+JP_PROGRAMA_DTRABAJO.MESP_03+
                                 JP_PROGRAMA_DTRABAJO.MESP_04+JP_PROGRAMA_DTRABAJO.MESP_05+JP_PROGRAMA_DTRABAJO.MESP_06+
                                 JP_PROGRAMA_DTRABAJO.MESP_07+JP_PROGRAMA_DTRABAJO.MESP_08+JP_PROGRAMA_DTRABAJO.MESP_09+
                                 JP_PROGRAMA_DTRABAJO.MESP_10+JP_PROGRAMA_DTRABAJO.MESP_11+JP_PROGRAMA_DTRABAJO.MESP_12)
                                 META_PROGRAMADA')
                   ->where(['JP_PROGRAMA_ETRABAJO.FOLIO' => $id, 'JP_PROGRAMA_ETRABAJO.IAP_ID' => $arbol_id])
                   //->where(  'JP_PROGRAMA_ETRABAJO.IAP_ID',$arbol_id)
                   ->orderBy('JP_PROGRAMA_ETRABAJO.PERIODO_ID','ASC')
                   ->orderBy('JP_PROGRAMA_ETRABAJO.FOLIO','ASC')
                   ->get();               
        }                            
    }
}
