<?php

namespace Vanguard\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    protected $table = 'project_features';
    protected $fillable = ['project_id', 'feature'];

    function parent_project(){
        return $this->belongsTo('Vanguard\Model\Feature','project_id');
    }
}