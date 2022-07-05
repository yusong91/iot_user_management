<?php

namespace Vanguard\Repositories\AsignDevice;

use Vanguard\Model\AsignDevice; 
use Vanguard\User; 
use Carbon\Carbon;
use DB; 
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;  

class EloquentAsignDevice implements AsignDeviceRepository 
{
    public function paginateUser($perPage, $search = null)
    {
        $query = User::query()->with(['role', 'children_asigndevice'])->where('parent_id', '==', 0);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('username', "like", "%{$search}%");
              
            }); 
        } 
            
        $result = $query->orderBy('created_at', 'desc')->paginate($perPage);
        if ($search) {
            $result->appends(['search' => $search]);
        } 

        return $result;
    }

    public function paginate($perPage, $search = null)
    {   
        $query = AsignDevice::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('user_id', "like", "%{$search}%");
              
            });
        } 
            
        $result = $query->orderBy('created_at', 'desc')->paginate($perPage);
        if ($search) {
            $result->appends(['search' => $search]);
        } 

        return $result;
    }

    public function all()
    {
        return AsignDevice::all();
    }

    public function find($id)
    {
        return AsignDevice::find($id); 
    }

    public function create($user_id, array $data) 
    {   
        $asigned = AsignDevice::where('user_id', $user_id)->first();

        if($asigned)
        {
            $asigned->device = json_encode($data);
            return $asigned->save();
        }

        $asigned = new AsignDevice();
        $asigned->user_id = $user_id;
        $asigned->device = json_encode($data);
        return $asigned->save();
    } 

    public function update($id, array $data){

        return null;//AsignProject::find($id)->update($data);
    }

    public function delete($id)
    {
        return Project::find($id)->delete();
    }


}