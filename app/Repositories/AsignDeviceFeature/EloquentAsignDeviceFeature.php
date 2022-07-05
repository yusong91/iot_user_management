<?php

namespace Vanguard\Repositories\AsignDeviceFeature;

use Vanguard\Model\AsignDeviceFeature;   
use Carbon\Carbon; 
use DB; 
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; 

class EloquentAsignDeviceFeature implements AsignDeviceFeatureRepository 
{
    public function paginate($perPage, $search = null)
    {   
        $query = AsignDeviceFeature::query()->where('project_id', auth()->user()->id);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', "like", "%{$search}%");
            });
        } 
          
        $result = $query->orderBy('created_at', 'desc')->paginate($perPage);
        if ($search) {
            $result->appends(['search' => $search]);
        } 

        return $result;
    }

    //Asign Project Device Feature 
    public function create(array $data) 
    {    
        $json = [];
        $devices = $data['device'] ?? [];
        $group = array(); 

        foreach($devices as $item)
        {
            $t = substr($item, 0, 1);    
            $group[substr($item, 0, 1)][] = trim(substr($item,1)); 
        }

        foreach($group as $key => $value)
        {
            $asigned = AsignDeviceFeature::where('project_id', $key)->first();

            if($asigned)
            {
                $asigned->project_id = $key;
                $asigned->device_feature = json_encode($value);
                $asigned->save();
            } else {

                $asigned = new AsignDeviceFeature();
                $asigned->project_id = $key;
                $asigned->device_feature = json_encode($value);
                $asigned->save(); 
            }  
        }

        if(count($devices) == 0)
        {
            $asigned = AsignDeviceFeature::query();
            return $asigned->delete();
        }
        return true;   
    } 

    public function all()
    {
        return AsignDeviceFeature::all();
    }

    public function find($id)
    {
        return AsignDeviceFeature::find($id); 
    }

    public function update($id, array $data){

        $folder = AsignDeviceFeature::find($id);
        $folder->name = $data['name'];
        return $folder->save();
    }

    public function delete($id)
    {
        $delete = AsignDeviceFeature::find($id);
        $result = $delete->delete();
        return $result;
    }
}


// public function create(array $data) 
//     {    
//         $json = [];

//         $devices = $data['device'] ?? [];

//         foreach($devices as $item)
//         {
//             $ids = explode(' ', $item);
//             $json[] = ['project_id'=>$ids[0], 'device_name'=>$ids[1]]; 
//         }

//         $asigned = AsignDeviceFeature::where('user_id', $data['user_id'])->first();

//         if($asigned)
//         {
//             $asigned->device = json_encode($json);
//             return $asigned->save();
//         }

//         $asign = new AsignDeviceFeature();
//         $asign->project_id = $data['project_id'];
//         $asign->device_feature = json_encode($json);
//         return $asign->save();    
//     } 