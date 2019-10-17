<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regAgendaModel extends Model
{
    protected $table      = "JP_AGENDA";
    protected $primaryKey = 'VISITA_FOLIO';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'VISITA_FOLIO',
        'PERIODO_ID',
        'MES_ID',
        'DIA_ID',
        'HORA_ID',
        'NUM_ID',
        'IAP_ID',
        'ENTIDAD_ID',
        'MUNICIPIO_ID',
        'VISITA_CONTACTO',
        'VISITA_TEL',
        'VISITA_EMAIL',
        'VISITA_DOM',
        'VISITA_OBJ',
        'VISITA_SPUB',
        'PERIODO2_ID',
        'MES2_ID',
        'DIA2_ID',
        'HORA2_ID',
        'NUM2_ID',
        'NUM3_ID',
        'ENTIDAD2_ID',
        'MUNICIPIO2_ID',        
        'VISITA_TIPO1',
        'VISITA_TIPO2',
        'VISITA_TIPO3',
        'VISITA_TIPO4',
        'VISITA_TIPO5',
        'VISITA_AUDITADO1',
        'VISITA_PUESTO1',
        'VISITA_IDENT1',
        'VISITA_EXPED1',
        'VISITA_AUDITADO2',
        'VISITA_PUESTO2',
        'VISITA_IDENT2',
        'VISITA_EXPED2',
        'VISITA_AUDITADO3',
        'VISITA_PUESTO3',
        'VISITA_IDENT3',
        'VISITA_EXPED3',
        'VISITA_AUDITADO4',
        'VISITA_PUESTO4',
        'VISITA_IDENT4',
        'VISITA_EXPED4',        
        'VISITA_AUDITOR1',
        'VISITA_AUDITOR2',
        'VISITA_AUDITOR3',
        'VISITA_AUDITOR4',
        'VISITA_TESTIGO1',
        'VISITA_TESTIGO2',
        'VISITA_TESTIGO3',
        'VISITA_TESTIGO4',
        'PREG01_ID',
        'PREG02_ID',
        'PREG03_ID',
        'PREG04_ID',
        'PREG05_ID',
        'PREG06_ID',
        'PREG07_ID',
        'PREG08_ID',
        'PREG09_ID',
        'PREG10_ID',
        'PREG11_ID',
        'PREG12_ID',
        'PREG13_ID',
        'PREG14_ID',
        'PREG15_ID',
        'PREG16_ID',
        'PREG17_ID',
        'PREG18_ID',
        'PREG19_ID',
        'PREG20_ID',
        'PREG21_ID',
        'PREG22_ID',
        'PREG23_ID',
        'PREG24_ID',
        'PREG25_ID',
        'PREG26_ID',
        'PREG27_ID',
        'PREG28_ID',
        'PREG29_ID',
        'PREG30_ID',
        'PREG31_ID',
        'PREG32_ID',
        'PREG33_ID',
        'PREG34_ID',
        'PREG35_ID',
        'PREG36_ID',
        'PREG37_ID',
        'PREG38_ID',
        'PREG39_ID',
        'PREG40_ID',
        'PREG41_ID',
        'PREG42_ID',
        'PREG43_ID',
        'PREG44_ID',
        'PREG45_ID',
        'PREG46_ID',
        'PREG47_ID',
        'PREG48_ID',
        'PREG49_ID',
        'PREG50_ID',
        'PREG51_ID',
        'PREG52_ID',
        'PREG53_ID',
        'PREG54_ID',
        'PREG55_ID',
        'PREG56_ID',
        'PREG57_ID',
        'PREG58_ID',
        'PREG59_ID',
        'PREG60_ID',
        'PREG61_ID',
        'PREG62_ID',
        'PREG63_ID',
        'PREG64_ID',
        'PREG65_ID',
        'PREG66_ID',
        'PREG67_ID',
        'PREG68_ID',
        'PREG69_ID',
        'PREG70_ID',
        'PREG71_ID',
        'PREG72_ID',
        'PREG73_ID',
        'PREG74_ID',
        'PREG75_ID',
        'PREG76_ID',
        'PREG77_ID',
        'PREG78_ID',
        'PREG79_ID',
        'PREG80_ID',        
        'P_RESP01',
        'P_RESP02',
        'P_RESP03',
        'P_RESP04',
        'P_RESP05',
        'P_RESP06',
        'P_RESP07',
        'P_RESP08',
        'P_RESP09',
        'P_RESP10',
        'P_RESP11',
        'P_RESP12',
        'P_RESP13',
        'P_RESP14',
        'P_RESP15',
        'P_RESP16',
        'P_RESP17',
        'P_RESP18',
        'P_RESP19',
        'P_RESP20',
        'P_RESP21',
        'P_RESP22',
        'P_RESP23',
        'P_RESP24',
        'P_RESP25',
        'P_RESP26',
        'P_RESP27',
        'P_RESP28',
        'P_RESP29',
        'P_RESP30',
        'P_RESP31',
        'P_RESP32',
        'P_RESP33',
        'P_RESP34',
        'P_RESP35',
        'P_RESP36',
        'P_RESP37',
        'P_RESP38',
        'P_RESP39',
        'P_RESP40',
        'P_RESP41',
        'P_RESP42',
        'P_RESP43',
        'P_RESP44',
        'P_RESP45',
        'P_RESP46',
        'P_RESP47',
        'P_RESP48',
        'P_RESP49',
        'P_RESP50',
        'P_RESP51',
        'P_RESP52',
        'P_RESP53',
        'P_RESP54',
        'P_RESP55',
        'P_RESP56',
        'P_RESP57',
        'P_RESP58',
        'P_RESP59',
        'P_RESP60',
        'P_RESP61',
        'P_RESP62',
        'P_RESP63',
        'P_RESP64',
        'P_RESP65',
        'P_RESP66',
        'P_RESP67',
        'P_RESP68',
        'P_RESP69',
        'P_RESP70',
        'P_RESP71',
        'P_RESP72',
        'P_RESP73',
        'P_RESP74',
        'P_RESP75',
        'P_RESP76',
        'P_RESP77',
        'P_RESP78',
        'P_RESP79',
        'P_RESP80',        
        'P_OBS01',
        'P_OBS02',
        'P_OBS03',
        'P_OBS04',
        'P_OBS05',
        'P_OBS06',
        'P_OBS07',
        'P_OBS08',
        'P_OBS09',
        'P_OBS10',
        'P_OBS11',
        'P_OBS12',
        'P_OBS13',
        'P_OBS14',
        'P_OBS15',
        'P_OBS16',
        'P_OBS17',
        'P_OBS18',
        'P_OBS19',
        'P_OBS20',
        'P_OBS21',
        'P_OBS22',
        'P_OBS23',
        'P_OBS24',
        'P_OBS25',
        'P_OBS26',
        'P_OBS27',
        'P_OBS28',
        'P_OBS29',
        'P_OBS30',
        'P_OBS31',
        'P_OBS32',
        'P_OBS33',
        'P_OBS34',
        'P_OBS35',
        'P_OBS36',
        'P_OBS37',
        'P_OBS38',
        'P_OBS39',
        'P_OBS40',
        'P_OBS41',
        'P_OBS42',
        'P_OBS43',
        'P_OBS44',
        'P_OBS45',
        'P_OBS46',
        'P_OBS47',
        'P_OBS48',
        'P_OBS49',
        'P_OBS50',
        'P_OBS51',
        'P_OBS52',
        'P_OBS53',
        'P_OBS54',
        'P_OBS55',
        'P_OBS56',
        'P_OBS57',
        'P_OBS58',
        'P_OBS59',
        'P_OBS60',
        'P_OBS61',
        'P_OBS62',
        'P_OBS63',
        'P_OBS64',
        'P_OBS65',
        'P_OBS66',
        'P_OBS67',
        'P_OBS68',
        'P_OBS69',
        'P_OBS70',
        'P_OBS71',
        'P_OBS72',
        'P_OBS73',
        'P_OBS74',
        'P_OBS75',
        'P_OBS76',
        'P_OBS77',
        'P_OBS78',
        'P_OBS79',
        'P_OBS80',
        'VISITA_EDO',
        'VISITA_STATUS1',
        'VISITA_STATUS2',
        'VISITA_DOC1',
        'VISITA_DOC2',
        'VISITA_CRITERIOS',
        'VISITA_VISTO',
        'VISITA_RECOMEN',
        'VISITA_SUGEREN',
        'VISITA_OBS1',
        'VISITA_OBS2',
        'VISITA_FECREGP',
        'VISITA_FECREGD',
        'VISITA_FECREGV',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopefPer($query, $fper)
    {
        if($fper)
            return $query->orwhere('PERIODO_ID', '=', "$fper");
    }

    public function scopefMes($query, $fmes)
    {
        if($fmes)
            return $query->orwhere('MES_ID', '=', "$fmes");
    }

    public function scopefDia($query, $fdia)
    {
        if($fdia)
            return $query->orwhere('DIA_ID', '=', "$fdia");
    } 

    public function scopefIap($query, $fiap)
    {
        if($fiap)
            return $query->orwhere('IAP_ID', '=', "$fiap");
    } 

    
}
