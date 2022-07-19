<?php

namespace Vanguard\Repositories\Feature;

use Vanguard\Model\Feature; 
use Carbon\Carbon;
use DB; 
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EloquentFeature implements FeatureRepository 
{
    public function paginate($perPage, $search = null, $project_id = null, $category = null)
    {   
        $parent_id = auth()->user()->parent_id == 0 ? auth()->user()->id : auth()->user()->parent_id;

        $table = DB::table('projects')->find($project_id);

        $device_list = json_decode($table->device_list);

        $key = ['project_id'=>$project_id, 'parent_id'=>$parent_id];

        if(auth()->user()->role_id == 1)
        {
            $key = ['project_id'=>$project_id];
        }

        if($category)
        {
            if (Schema::hasTable($category)) 
            {
                return DB::table($category)->where($key)->get();
            }
        }

        if(count($device_list) > 0)
        {
            if (Schema::hasTable($device_list[0])) 
            {
                return DB::table($device_list[0])->where($key)->get();
            }
        }
        return [];
    } 

    public function all() 
    {
        return Feature::all();
    }

    public function find($id)
    {
        return Feature::find($id); 
    }

    public function findByProjectId($project_id)
    {
        return Feature::where('project_id', $project_id)->first();
    }

    //Create Feature Table
    public function create($data, $jsons) 
    {   
        $project_name = $data['project_name'];
        $project_id = $data['project_id'];
        $feature_name = array();

        $feature = Feature::where('project_id', $project_id)->first();

        if($feature)
        {
            $feature->project_id = $project_id;
            $feature_name = json_decode($feature->feature);

        } else {
            $feature = new Feature();
            $feature->project_id = $project_id;
        }

        foreach($jsons as $json)
        {
            foreach($json as $key => $json)
            {
                $table_name = $key;
                $feature_name[] = $table_name;
                
                Schema::create($project_name. '_' .$table_name, function (Blueprint $table) use ($table_name, $json) {
                        
                    $table->bigIncrements('id');  
                    $table->bigInteger('project_id')->unsigned();
                    $table->foreign('project_id')->references('id')->on('projects');
                    $table->integer('parent_id')->unsigned();
                    $table->foreign('parent_id')->references('id')->on('users');
                    $table->integer('folder_id')->unsigned();
                        
                    foreach($json as $jkey => $item)
                    {    
                        $comments = '';

                        if(isset($item['values'])) 
                        {
                            $comments = $item['values'];
                        }

                        $type = $item['type'];

                        switch ($type) {

                            case 'char':
                                $table->string($jkey, 190)->default($item['default'])->comment($comments);
                                break;

                            case 'text':
                                $table->text($jkey)->default($item['default'])->comment($comments);
                                break;

                            case 'double': 
                                $default = (Double)$item['default'];
                                $table->double($jkey, 100, 2)->default($default)->comment($comments);
                                break;

                            case 'float':
                                $default = (Float)$item['default'];
                                $table->float($jkey, 100, 2)->comment($comments);
                                break;

                            case 'integer':
                                $default = (int)$item['default'];
                                $table->integer($jkey)->default($default)->comment($comments);
                                break;

                            case 'timestamp':
                                $table->timestamp($jkey)->comment($comments);
                                break;
                            
                            default: '';
                                break;
                        }
                    }
                    $table->timestamps();
                });
            }
        }
            
        $feature->feature = json_encode($feature_name);
        $feature->save();
           
        return true;        
    }

    public function update($id, array $data){

        $project = Feature::find($id);
        $project->name = $data["name"];
        $project->save();
        return $project;
    } 

    public function delete($table_name, $id)
    {
        return DB::table($table_name)->where('id',$id)->delete();
    }

    public function getDate($data){

        if(is_null($data)){
            return null;
        }

        $time = strtotime(str_replace('/', '-', $data));
        $newformat = date('Y-m-d', $time);
        return $newformat;
    }

}