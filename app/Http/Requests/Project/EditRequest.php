<?php

namespace Vanguard\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    public function authorize() 
    {
        return true;
    }

    public function rules()
    {
        return [

            'name' => 'required|unique:projects,name',
            'file_json' => 'nullable|mimes:json'
        ];
    }

}
