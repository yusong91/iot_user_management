<?php

namespace Vanguard\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
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

            'name' => 'required|special_charaters|max:190|unique:projects,name|regex:/^[a-zA-ZÑñ\s]+$/', //Rule::unique('projects', 'name')->ignore($this->project) //alpha
            'file_json' => 'required|mimes:json'
        ];
    }

}

//'username' => 'nullable|unique:users,username',