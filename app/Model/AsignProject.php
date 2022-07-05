<?php

namespace Vanguard\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignProject extends Model
{
    use HasFactory;

    protected $table = 'asign_projects';
    protected $fillable = ['user_id', 'project'];
}
