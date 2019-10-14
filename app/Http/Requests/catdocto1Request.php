<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class catdocto1Request extends FormRequest
{
    public function messages()
    {
        return [
            'doc_file.required' => 'Archivo del documento es obligatorio'
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
            //'iap_desc.'  => 'required|min:1|max:100',
            'doc_file'     => 'mimes:pdf,xls,xlsx,doc,docx,ppt,jpg,jpeg,png'
            //'iap_foto2'  => 'required|image'
            //'accion'     => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'     => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc' => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
