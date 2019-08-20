<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regIapModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportCatIaps implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'IAP_ID',
            'IAP_DESC',
            'IAP_CALLE',
            'IAP_NUM',
            'IAP_COLONIA',
            'MUNICIPIO_ID',
            'RUBRO_ID',
            'IAP_REGCONS',
            'IAP_RFC',
            'IAP_CP',
            'IAP_FECCONS',
            'IAP_TELEFONO',
            'IAP_EMAIL',
            'IAP_SWEB',
            'IAP_PRES',
            'IAP_REPLEGAL',
            'IAP_SRIO',
            'IAP_TESORERO',
            'IAP_OBJSOC',
            'GRUPO_ID',
            'IAP_STATUS',
            'IAP_FECCERTIFIC',
            'IAP_FECREG',
            'IP',
            'LOGIN',
            'FECHA_M',
            'IP_M',
            'LOGIN_M'
        ];
    }

    public function collection()
    {
        return regIapModel::join('CAT_MUNICIPIOS_SEDESEM',[['CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                           ['CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','JP_IAPS.MUNICIPIO_ID']])
                          ->join('JP_CAT_RUBROS'         ,'JP_CAT_RUBROS.RUBRO_ID','=','JP_IAPS.RUBRO_ID')
                          ->select('JP_IAPS.IAP_ID', 'JP_IAPS.IAP_DESC',    'JP_IAPS.IAP_CALLE', 'JP_IAPS.IAP_NUM',             'JP_IAPS.IAP_COLONIA', 'JP_IAPS.MUNICIPIO_ID','CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE',         
                              'JP_IAPS.RUBRO_ID',    'JP_CAT_RUBROS.RUBRO_DESC', 'JP_IAPS.IAP_REGCONS', 'JP_IAPS.IAP_RFC',    
                              'JP_IAPS.IAP_CP',      'JP_IAPS.IAP_FECCONS',      'JP_IAPS.IAP_TELEFONO','JP_IAPS.IAP_EMAIL',   
                              'JP_IAPS.IAP_SWEB',    'JP_IAPS.IAP_PRES',         'JP_IAPS.IAP_REPLEGAL','JP_IAPS.IAP_SRIO',    
                              'JP_IAPS.IAP_TESORERO','JP_IAPS.IAP_OBJSOC',       'JP_IAPS.GRUPO_ID',    'JP_IAPS.IAP_STATUS',  
                              'JP_IAPS.IAP_FECCERTIFIC','JP_IAPS.IAP_FECREG',    'JP_IAPS.IP',          'JP_IAPS.LOGIN',       
                              'JP_IAPS.FECHA_M',    'JP_IAPS.IP_M',               'JP_IAPS.LOGIN_M')
                            ->orderBy('JP_IAPS.IAP_ID','DESC')
                            ->get();                               
    }
}
