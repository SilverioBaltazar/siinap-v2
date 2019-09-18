<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regAsistContableModel extends Model
{
    protected $table      = "JP_IAPS_ASISTCONTABLE";
    protected $primaryKey = 'IAP_FOLIO';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'IAP_FOLIO',
        'IAP_ID',
        'PERIODO_ID',
        'IAP_D01',
        'PER01_ID',
        'NUM01_ID',
        'IAP_EDO01',
        'IAP_D02',
        'PER02_ID',
        'NUM02_ID',
        'IAP_EDO02',
        'IAP_D03',
        'PER03_ID',
        'NUM03_ID',
        'IAP_EDO03',
        'IAP_D04',
        'PER04_ID',
        'NUM04_ID',
        'IAP_EDO04',
        'IAP_D05',
        'PER05_ID',
        'NUM05_ID',
        'IAP_EDO05',
        'IAP_D06',
        'PER06_ID',
        'NUM06_ID',
        'IAP_EDO06',
        'IAP_D07',
        'PER07_ID',
        'NUM07_ID',
        'IAP_EDO07',
        'IAP_D08',
        'PER08_ID',
        'NUM08_ID',
        'IAP_EDO08',
        'IAP_D09',
        'PER09_ID',
        'NUM09_ID',
        'IAP_EDO09',
        'IAP_D10',
        'PER10_ID',
        'NUM10_ID',
        'IAP_EDO10',
        'IAP_STATUS',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];
}

