<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regIapJuridicoModel extends Model
{
    protected $table      = "JP_IAPS_JURIDICO";
    protected $primaryKey = 'IAP_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'IAP_ID',
        'IAP_ACT_CONST',
        'IAP_RFC',
        'IAP_RPP',
        'ANIO_ID',
        'IAP_FVP',
        'INM_ID',
        'FECREG', 
        'IP', 
        'LOGIN', 
        'FECHA_M', 
        'IP_M', 
        'LOGIN_M'              
    ];

}