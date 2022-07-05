<?php

namespace Vanguard\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignDevice extends Model
{
    use HasFactory;

    protected $table = 'asign_devices';
    protected $fillable = ['user_id', 'device'];
}
