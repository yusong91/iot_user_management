<?php

namespace Vanguard\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $table = 'folders';
    protected $fillable = ['project_id', 'user_id', 'parent_id', 'name'];
    
    function parent_project(){
        return $this->belongsTo('Vanguard\Model\Project','project_id');
    }
}
