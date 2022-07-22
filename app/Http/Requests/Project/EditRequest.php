<?php

namespace Vanguard\Http\Requests\Project;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    public function authorize() 
    {
        return true;
    }

    public function rules()
    {
        \Validator::extend('special_charaters', function($attr, $value){ 

            return preg_match('/^\S*$/u', $value); 
        }); 
        

        return [ 

            'name'=> 'required|special_charaters|max:190|regex:/^[a-zA-ZÑñ\s]+$/', Rule::unique('projects', 'name')->ignore($this->project),  //|unique:projects,name  ['required', 'max:190','alpha',"regex:/^([^\"!'\*\\]*)$/"], //
            'file_json' => 'nullable|mimes:json'
        ];
    }

}
