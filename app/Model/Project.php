<?php

namespace Vanguard\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
    protected $fillable = ['name', 'user_id'];

    function parent_feature(){
        return $this->hasOne('Vanguard\Model\Feature','project_id');
    }
}
