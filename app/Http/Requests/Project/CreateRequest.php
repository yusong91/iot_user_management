<?php

namespace Vanguard\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize() 
    {
        return true;
    }

    public function rules()
    {

        \Validator::extend('without_spaces', function($attr, $value){ 

            return preg_match('/^\S*$/u', $value); 
        }); 

        return [

            'name' => 'required|without_spaces|unique:projects,name',
            'file_json' => 'required|mimes:json'
        ];
    }

}

//'username' => 'nullable|unique:users,username',