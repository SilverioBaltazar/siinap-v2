<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regCursosModel extends Model
{
    protected $table      = "JP_IAPS_CURSOS";
    protected $primaryKey = 'CURSO_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'CURSO_ID',
        'IAP_ID',
        'PERIODO_ID',
        'MES_ID',
        'CURSO_DESC',
        'CURSO_OBJ',
        'CURSO_COSTO',
        'CURSO_THORAS',
        'CURSO_FINICIO',
        'CURSO_FFIN',
        'CURSO_OBS',
        'CURSO_STATUS',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];
}