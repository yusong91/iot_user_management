<?php

namespace Vanguard\Repositories\AsignProject;

use Vanguard\Model\AsignProject; 
use Vanguard\Model\AsignDevice; 
use Vanguard\User; 
use Carbon\Carbon;
use DB; 
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; 

class EloquentAsign implements AsignRepository 
{     
    //Asign Project
    public function paginateUser($perPage, $search = null)
    {
        $parent_key = auth()->user()->id;
        $role_id = auth()->user()->role_id;
        $family = auth()->user()->parent_id == 1 ? auth()->user()->id : auth()->user()->family;
        $query = null;
        
        if($role_id == 1)
        {
            $query = User::query()->with(['role', 'children_asignproject', 'children_asigndevice'])->where('role_id', 2)->where('parent_id', '==', 0);
        }
        elseif ($role_id == 2)
        {   
            $family =  auth()->user()->id;

            $query = User::query()->with(['role', 'children_asignfolder'])->where('family', $family)->where('role_id', '!=', 1);

        } elseif ($role_id == 3)
        {
            $query = User::query()->where('parent_id', $parent_key)->orWhere('id', auth()->user()->id);
        } else {
            $query = User::query()->where('id', auth()->user()->id);
        }

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
        $query = AsignProject::query();

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

    public function all()
    {
        return AsignProject::all();
    }

    public function find($id)
    {
        return AsignProject::find($id); 
    }

    public function create(array $data) 
    {   
        $json = [];

        $projects = $data['project'] ?? [];
        
        foreach($projects as $item)
        {
            $ids = explode(' ', $item);
            $json[] = ['project_id'=>$ids[0], 'project_name'=>$ids[1]]; 
        }

        $asigned = AsignProject::where('user_id', $data['user_id'])->first();

        if($asigned)
        {
            $asigned->project = json_encode($json);
            return $asigned->save();
        }
 
        $asign = new AsignProject();
        $asign->user_id = $data['user_id'];
        $asign->project = json_encode($json);
        return $asign->save();
    } 

    public function create_assigndevice(array $data) 
    {   
        
    } 

    public function update($id, array $data){

        return null;//AsignProject::find($id)->update($data);
    }

    public function delete($id)
    {
        return Project::find($id)->delete();
    }


}