<?php

namespace Vanguard\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignDeviceFeature extends Model
{
    use HasFactory;
    protected $table = 'asign_device_features';
    protected $fillable = ['project_id', 'device_feature'];
}
