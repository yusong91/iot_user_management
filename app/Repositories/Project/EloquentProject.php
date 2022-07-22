<?php

namespace Vanguard\Repositories\Project;

use Vanguard\Model\Project;  
use Carbon\Carbon;
use DB; 
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Vanguard\Events\Project\DeletedProject;

class EloquentProject implements ProjectRepository 
{
   
    public function paginate($perPage, $search = null)
    {   
        $parent_id = auth()->user()->parent_id == 0 ? auth()->user()->id : auth()->user()->parent_id;
        $asigned = DB::table('asign_projects')->where('user_id', $parent_id)->first();
        $json_array = isset($asigned->project) ? json_decode($asigned->project) : [];
        $keys = [];

        foreach($json_array as $item)
        {
            $keys[] = $item->project_id;
        }
        
        $query = auth()->user()->role_id == 1 ?  Project::query() : Project::query()->whereIn('id', $keys);

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

    public function create(array $data, $json) 
    {   
        $project_method = [];
        $device_list = [];

        foreach($json['DeviceList'] as $item)
        {
            $device_list[] = $item;
        }
 
        $project = new Project();
        $project->name = $data["name"];
        $project->user_id = auth()->user()->id;
        $project->device_list = json_encode($device_list);
        $project->project_method =  isset($data['project_method']) ? json_encode($data['project_method']) : null;
        $project->save();
        return $project;       
    } 
 
    public function all()
    {
        return Project::all();
    }

    public function find($id)
    {
        return Project::find($id); 
    }

    public function update($id, array $data){

        $project = Project::find($id);
        $project->name = $data['name'];
        if(isset($data['DeviceList']))
        {
            $device_list = [];

            foreach($data['DeviceList'] as $item)
            {
                $device_list[] = $item;
            }
            $project->device_list = json_encode($device_list);
        }

        $project->project_method =  isset($data['project_method']) ? json_encode($data['project_method']) : null;
        return $project->save();
    }

    public function delete($id)
    {
        $delete = Project::find($id);
        $result = $delete->delete();
        if($result)
        {
            event(new DeletedProject($delete));
            //Schema::dropIfExists($delete->name);
        }
        return $result;
    }

    //Create Project Method
    public function create_method($table_name,  array $data)
    {
        if(isset($data['_token']))
        {
            unset($data['_token']);
            unset($data['table_name']); 
        }

        $data_insert = [];

        // foreach($data as $key => $item)
        // {
        //     if(str_contains(strtolower($key) , 'date'))
        //     {
        //         $data[$key] = $this->getDate($item); 
        //     }
        // }

        return DB::table($table_name)->insert($data); 
    }

    // public function create(array $data, $json) 
    // {   
    //     $table_name = $data["name"];

    //     if (!Schema::hasTable($table_name)) 
    //     {
    //         Schema::create($table_name, function (Blueprint $table) use ($table_name, $json) {
                
    //             $table->bigIncrements('id');  
    //             $table->bigInteger('project_id')->unsigned();
    //             $table->foreign('project_id')->references('id')->on('projects');
    //             $table->integer('parent_id')->unsigned();
    //             $table->foreign('parent_id')->references('id')->on('users');
                
    //             foreach($json as $jkey => $item)
    //             {   
    //                 foreach($item as $key => $value)
    //                 {
    //                     $table->{$value['type']}($jkey . "_" . $key);
    //                 }
    //             }
    //             $table->timestamps();
    //         });
    //         $project = new Project();
    //         $project->name = $data["name"];
    //         $project->user_id = auth()->user()->id;
    //         $project->save();
    //         return $project;
    //     }
    //     return null;        
    // } 

    // public function create(array $data, $json) 
    // {   
    //     $table_name = $data["name"];

    //     //dd(auth()->user()->parent_id);

    //     if (!Schema::hasTable($table_name)) 
    //     {
    //         Schema::create($table_name, function (Blueprint $table) use ($table_name, $json) {
                
    //             $table->bigIncrements('id');  
    //             $table->bigInteger('project_id')->unsigned();
    //             $table->foreign('project_id')->references('id')->on('projects');
    //             $table->integer('parent_id')->unsigned();
    //             $table->foreign('parent_id')->references('id')->on('users');
                
    //             foreach($json as $jkey => $item)
    //             {   
    //                 foreach($item as $key => $value)
    //                 {
    //                     $table->{$value['type']}($jkey . "_" . $key);
    //                 }
    //             }
    //             $table->timestamps();
    //         });
    //         $project = new Project();
    //         $project->name = $data["name"];
    //         $project->user_id = auth()->user()->id;
    //         $project->save();
    //         return $project;
    //     }
    //     return null;        
    // } 

    

  

}