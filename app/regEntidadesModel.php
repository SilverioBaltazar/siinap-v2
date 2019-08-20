<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regEntidadesModel extends Model
{
    protected $table      = "CAT_ENTIDADES_FEDERATIVAS";
    protected $primaryKey = 'ENTIDADFEDERATIVA_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'ENTIDADFEDERATIVA_ID',
        'ENTIDADFEDERATIVA_DESC',
        'ENTIDADFEDERATIVA_ABREVIACION'
    ];
}