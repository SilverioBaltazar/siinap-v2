<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regAportacionesModel extends Model
{
    protected $table      = "JP_IAPS_APORTACIONES";
    protected $primaryKey = 'APOR_FOLIO';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'APOR_FOLIO',
        'PERIODO_ID',
        'IAP_ID',        
        'MES_ID',
        'BANCO_ID',
        'APOR_NOCHEQUE',
        'APOR_CONCEPTO',
        'APOR_MONTO',
        'APOR_ENTREGA',
        'APOR_RECIBE',
        'APOR_COMPDEPO',
        'APOR_STATUS',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];
}