<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class progdilRequest extends FormRequest
{
    public function messages()
    {
        return [
            'periodo_id.required'     => 'El periodo fiscal es obligatorio.',
            'mes_id.required'         => 'El mes es obligatorio.',
            'dia_id.requered'         => 'El dia es obligatorio.',            
            'iap_id.required'         => 'Id de la IAP es obligatorio.',
            'visita_contacto.min'     => 'El contacto de la IAP es de mínimo 1 caracter.',
            'visita_contacto.max'     => 'El contacto de la IAP es de máximo 100 caracteres.',
            'visita_contacto.required'=> 'El contacto de la IAP es obligatorio.',
            'visita_dom.min'          => 'El domicilio de la IAP es de mínimo 1 caracter.',
            'visita_dom.max'          => 'El domicilio de la IAP es de máximo 100 caracteres.',
            'visita_dom.required'     => 'El domicilio de la IAP es obligatorio.',      
            'visita_tel.min'          => 'El teléfono de la IAP es de mínimo 1 caracter.',
            'visita_tel.max'          => 'El teléfono de la IAP es de máximo 60 caracteres.',
            'visita_tel.required'     => 'El teléfono de la IAP es obligatorio.',                        
            'visita_obj.min'          => 'El objetivo de la visita es de mínimo 1 caracter.',
            'visita_obj.max'          => 'El objetivo de la visita es de máximo 300 caracteres.',
            'visita_obj.required'     => 'El objetivo de la visita es obligatorio.', 
            'visita_spub.min'         => 'El servidor público que programo visita es de mínimo 1 caracter.',
            'visita_spub.max'         => 'El servidor público que programo visita es de máximo 60 caracteres.',
            'visita_spub.required'    => 'El servidor público que programo visita es obligatorio.'
            //'iap_foto1.required' => 'La imagen es obligatoria'
        ];
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //'iap_desc.'    => 'required|min:1|max:100',
            'iap_id'         => 'required',
            'periodo_id'     => 'required',
            'mes_id'         => 'required',
            'dia_id'         => 'required',            
            'visita_contacto'=> 'required|min:1|max:80',
            'visita_dom'     => 'required|min:1|max:100',
            'visita_tel'     => 'required|min:1|max:60',
            'visita_obj'     => 'required|min:1|max:300',
            'visita_spub'    => 'required|min:1|max:80'
            //'apor_recibe'  => 'required|min:1|max:100'
            //'accion'        => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'        => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc' => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
