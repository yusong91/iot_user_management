<?php

namespace Vanguard\Repositories\Device;

use Vanguard\Model\Device; 
use Carbon\Carbon;
use DB; 
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EloquentDevice implements DeviceRepository 
{
    public function paginate($perPage, $folder_id, $table_name, $search = null)
    {   
        // $parent_id = auth()->user()->parent_id == 0 ? auth()->user()->id : auth()->user()->parent_id;
        // $table = DB::table('projects')->find($project_id);
        // $device_list = json_decode($table->device_list);
        // $key = ['project_id'=>$project_id, 'parent_id'=>$parent_id];
        // if(auth()->user()->role_id == 1)
        // {
        //     $key = ['project_id'=>$project_id];
        // }
        // if(count($device_list) > 0)
        // {
        //     if (Schema::hasTable($device_list[0])) 
        //     {
        //         return DB::table($device_list[0])->where($key)->get();
        //     }
        // }
        // return [];
                
        $query = DB::table($table_name)->where('folder_id', $folder_id);//Project::query()->whereIn('id', $keys);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', "like", "%{$search}%");
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
        return Device::all();
    }

    public function find($id)
    {
        return Device::find($id); 
    }

    public function create_device($data)
    {
        $table = $data['table_name'];

        if(isset($data['_token']))
        {
            unset($data['_token']);
            unset($data['table_name']); 
        }

        $data_insert = [];

        foreach($data as $key => $item)
        {
            if(str_contains(strtolower($key) , 'date'))
            {
                $data[$key] = $this->getDate($item); 
            }
        }

        return DB::table($table)->insert($data); 
    }

    //Create Category Table
    public function create($jsons) 
    {   
        foreach($jsons as $key => $json)
        {
            $table_name = $key;

            Schema::create($table_name, function (Blueprint $table) use ($table_name, $json) {
                    
                $table->bigIncrements('id');  
                $table->bigInteger('project_id')->unsigned();
                $table->foreign('project_id')->references('id')->on('projects');
                $table->integer('parent_id')->unsigned();
                $table->foreign('parent_id')->references('id')->on('users');
                     
                foreach($json as $jkey => $item)
                {    
                    //dd($item['type']);
                    //$table->{$item['type']}($jkey);
                    //double fload date int

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
                    //->default('');
                    //$table->date('birthday')->nullable();
                    //$table->$type($jkey, $lenght)->comment('Some comment.');
                }
                $table->timestamps();
            });

        }
           
        return true;        
    }

    public function update($id, $table_name, array $data){

        $update = DB::table($table_name)->where('id', $id);
        return $update->update($data); 
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