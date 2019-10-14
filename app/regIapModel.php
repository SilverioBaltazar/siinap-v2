<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regIapModel extends Model
{
    protected $table      = "JP_IAPS";
    protected $primaryKey = 'IAP_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
            'IAP_ID',
            'IAP_DESC',
            'IAP_CALLE',
            'IAP_NUM',
            'IAP_DOM1',
            'IAP_DOM2',
            'IAP_DOM3',
            'IAP_COLONIA',
            'MUNICIPIO_ID',
            'ESTADO_ID',
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
            'IAP_STATUS',         //S ACTIVO N INACTIVO
            'IAP_FECCERTIFIC',
            'IAP_GEOREF_LATITUD', 
            'IAP_GEOREF_LONGITUD', 
            'IAP_FOTO1', 
            'IAP_FOTO2',
            'IAP_FECREG',
            'IP',
            'LOGIN',
            'FECHA_M',
            'IP_M',
            'LOGIN_M'
    ];

    public static function ObtIap($id){
        return (regIapModel::select('IAP_ID')->where('IAP_ID','=',$id)
                             ->get());
    }

    public static function obtCatMunicipios(){
        return regIapModel::select('ENTIDADFEDERATIVAID','MUNICIPIOID','MUNICIPIONOMBRE')
                           ->where('ENTIDADFEDERATIVAID','=', 15)
                           ->orderBy('MUNICIPIOID','asc')
                           ->get();
    }

    public static function obtMunicipio($id){
        return regIapModel::select('MUNICIPIOID','MUNICIPIONOMBRE')
                            ->where([ 
                                     ['ENTIDADFEDERATIVAID','=', 15], 
                                     ['MUNICIPIOID',        '=',$id]
                                    ])
                            ->get();
                            //->where('ENTIDADFEDERATIVAID','=', 15)
                            //->where('MUNICIPIOID',        '=',$id)
    }

    public static function obtCatRubros(){
        return regIapModel::select('RUBRO_ID','RUBRO_DESC')
                            ->orderBy('RUBRO_ID','asc')
                            ->get();
    }    

    public static function obtRubro($id){
        return regIapModel::select('RUBRO_ID','RUBRO_DESC')
                           ->where('RUBRO_ID','=',$id )
                           ->get();
    }    

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeName($query, $name)
    {
        if($name)
            return $query->where('IAP_DESC', 'LIKE', "%$name%");
    }

    public function scopeEmail($query, $email)
    {
        if($email)
            return $query->where('IAP_EMAIL', 'LIKE', "%$email%");
    }

    public function scopeBio($query, $bio)
    {
        if($bio)
            return $query->where('IAP_OBJSOC', 'LIKE', "%$bio%");
    } 

}