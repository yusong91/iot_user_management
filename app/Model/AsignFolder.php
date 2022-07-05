<?php

namespace Vanguard\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignFolder extends Model
{
    use HasFactory;

    protected $table = 'asign_folders';
    protected $fillable = ['user_id', 'parent_id', 'folder'];
}
