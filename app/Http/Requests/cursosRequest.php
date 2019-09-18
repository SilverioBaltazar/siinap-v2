<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class cursosRequest extends FormRequest
{
    public function messages()
    {
        return [
            //'curso_id.required'   => 'Seleccionar id del curso.',
            'iap_id.required'       => 'Seleccionar la IAP',
            'periodo_id.required'   => 'Seleccionar el periodo',
            'mes_id.required'       => 'Seleccionar mes de inicio del curso', 
            'curso_desc.min'        => 'El nombre del curso es de mínimo 1 caracteres.',
            'curso_desc.max'        => 'El nombre del curso es de máximo 100 caracteres.',
            'curso_desc.required'   => 'El nombre del curso es obligatorio.',
            'curso_obj.min'         => 'El objetivo del curso es de mínimo 1 caracteres.',
            'curso_obj.max'         => 'El objetivo del curso es de máximo 100 caracteres.',
            'curso_obj.required'    => 'El objetivo del curso es obligatorio.',
            'curso_costo.required'  => 'El costo del curso es obligatorio.',
            'curso_thoras.required' => 'El total de horas del curso es obligatorio.',
            'curso_finicio.required'=> 'Fecha de inicio del curso',
            'curso_ffin.required'   => 'Fecha de termino del curso',
            'curso_obs.required'    => 'Las observaciones del curso es obligatorio.'
            //'curso_status.required' => 'El estado del curso es obligatorio'
            //'trx_desc.regex' => 'El nombre de la función contiene caracteres inválidos.'
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
            //'trx_desc' => 'required|min:1|max:100'
            'iap_id'       => 'required',
            'periodo_id'   => 'required',
            'mes_id'       => 'required', 
            'curso_desc'   => 'required|min:1|max:150',
            'curso_obj'    => 'required|min:1|max:150',
            'curso_costo'  => 'required',
            'curso_thoras' => 'required',
            'curso_finicio'=> 'required',
            'curso_ffin'   => 'required',
            'curso_obs'    => 'required|min:1|max:300'
            //'curso_status' => 'required'
            //'trx_desc' => 'min:1|max:100|required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i'
        ];
    }
}
