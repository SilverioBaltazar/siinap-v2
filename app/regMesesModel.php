<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regMesesModel extends Model
{
    protected $table      = "JP_CAT_MESES";
    protected $primaryKey = 'MES_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'MES_ID',
        'MES_DESC',
        'MES_STATUS', //S ACTIVO      N INACTIVO
        'FECREG'
    ];
}