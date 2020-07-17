<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regInventarioModel extends Model
{
    protected $table      = "JP_INVENTARIO";
    protected $primaryKey = ['IAP_ID','ACTIVO_ID'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PERIODO_ID',
        'ID',
        'IAP_ID',
        'ACTIVO_ID',
        'ACTIVO_DESC',
        'INVENTARIO_FECADQ',
        'INVENTARIO_FECADQ2',
        'PERIODO_ID1',
        'MES_ID1',
        'DIA_ID1',
        'INVENTARIO_DOC',
        'ACTIVO_VALOR',
        'CONDICION_ID',
        'INVENTARIO_OBS',
        'INVENTARIO_STATUS',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];
}
