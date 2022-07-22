<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\Project\CreateRequest;
use Vanguard\Http\Requests\Project\EditRequest;
use Illuminate\Support\Facades\Storage;
use File;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Vanguard\Repositories\Project\ProjectRepository;
use Vanguard\Repositories\Device\DeviceRepository;
use Vanguard\Repositories\Folder\FolderRepository;
use Vanguard\Events\Project\CreatedProject;

  
class ProjectController extends Controller
{
    private $project;
    private $device;
    private $folder;

    public function __construct(ProjectRepository $project, DeviceRepository $device, FolderRepository $folder)
    {  
		$this->project = $project; 
        $this->device = $device;
        $this->folder = $folder;
	}
 
    public function index(Request $request)
    {   
        $view = '';
        $projects;
        $paginate;
        switch (auth()->user()->role_id) {
            case 1: 
                $projects = $this->project->paginate($perPage = 20, $request->search);
                $raw_paginate = json_encode($projects); 
                $paginate = json_decode($raw_paginate);
                $view = 'project.index'; break;
                
            default:  
                $projects = $this->folder->paginate($perPage = 20, $request->search);
                $raw_paginate = json_encode($projects); 
                $paginate = json_decode($raw_paginate);
                $view = 'project.index-company'; break;
        } 
 
        return view($view, compact('projects', 'paginate')); 
    } 
  
    public function create() 
    {   
        $list_method = [];
        $project_methods = array(['tcp_server'=>'TCP Server', 'mqtt_broker'=>'MQTT Broker', 'http_https'=>'HTTP/HTTPS'], ['ota'=>'OTA', 'telegram_bot'=>'Telegram Bot', 'database'=>'Database']);
        return view('project.create', compact('project_methods', 'list_method'));
    } 

    public function store(CreateRequest $createRequest)
    {
        $file_json = $createRequest->file('file_json');

        $file_name = $file_json->getClientOriginalName();

        $save_path = storage_path('json_file');

        $file_json->move($save_path, $file_name); 

        $path = storage_path('/json_file/'. $file_name);

        $json_data = json_decode(file_get_contents($path), true); 

        $project = $this->project->create($createRequest->all(),  $json_data); 

        if($project){  
            
            event(new CreatedProject($project));
            return redirect(route('project.index'))->withSuccess('Success');
        }

        return redirect(route('project.index'))->withSuccess('Fail');
    }

    public function show($id){}
 
    public function edit($id)
    {
        $edit = $this->project->find($id);
        $list_method = $edit->project_method ? json_decode($edit->project_method) : [];

        $project_methods = array(['tcp_server'=>'TCP Server', 'mqtt_broker'=>'MQTT Broker', 'http_https'=>'HTTP/HTTPS'], ['ota'=>'OTA', 'telegram_bot'=>'Telegram Bot', 'database'=>'Database']);
        return view('project.edit', compact('edit', 'project_methods', 'list_method'));
    }

    public function update(EditRequest $request, $id)
    {
        // $file_json = $request->file('file_json');
        // if($file_json)
        // {
        //     $file_name = $file_json->getClientOriginalName();
        //     $save_path = storage_path('json_file');
        //     $file_json->move($save_path, $file_name); 
        //     $path = storage_path('/json_file/'. $file_name);
        //     $json_data = json_decode(file_get_contents($path), true); 
        //     $data = $request->all() + $json_data;
        // }
        
        $project = $this->project->update($id, $request->all());

        if($project){
            
            return redirect(route('project.index'))->withSuccess('Success');
        }

        return redirect(route('project.index'))->withSuccess('False');
    }
 
    public function destroy($id)
    {
        $project = $this->project->delete($id);
        if($project){

            return redirect(route('project.index'))->withSuccess('Success');
        }
    }

    //Project Folder
    public function create_folder()
    {
        $user_id = auth()->user()->id;
        $project_joins = getJoinProjects($user_id);
        $list_joins = isset($project_joins->project) ? json_decode($project_joins->project) : [];
        return view('folder.create', compact('list_joins')); 
    }
 
    public function store_folder(Request $request)
    {
        
        $user_id = auth()->user()->id;
        $parent_id = auth()->user()->parent_id == 0 ? $user_id : auth()->user()->parent_id;
        $data = $request->all() + ['user_id'=>$user_id, 'parent_id'=> $parent_id];

        $create = $this->folder->create($data);
        if($create)
        {
            return redirect(route('project.index'))->withSuccess('Success');
        }
        return redirect(route('project.index'))->withSuccess('Fail');   
    }
    //End Project Folder

    //Project Method
    public function show_method($key)
    {
        $table_thead = array();
        $user_id = auth()->user()->id;

        $table_name = $key . "_" . $user_id;

        $table = DB::select('SHOW FULL COLUMNS FROM '. $table_name);

        $i=0;
        foreach($table as $item)
        {  
            if($item->Field == 'id' || $item->Field == 'project_id' || $item->Field == 'user_id' || $item->Field == 'created_at' || $item->Field == 'updated_at'){ continue; }
            
            $table_thead[0][$i] = [$item->Field]; 
            $i++;
        }

        $data = DB::table($table_name)->where('user_id', $user_id)->get();

        return view('project_method.index', compact('table_thead', 'data', 'key')); 
    }

    public function create_method($key)
    {
        $user_id = auth()->user()->id;

        $table_name = $key . "_" . $user_id;

        $table = DB::select('SHOW FULL COLUMNS FROM '. $table_name);
        
        $table_data = [];
        
        $group_table_data = array();
        
        foreach($table as $item)
        {  
            if($item->Field == 'id' || $item->Field == 'project_id' || $item->Field == 'user_id' || $item->Field == 'created_at' || $item->Field == 'updated_at'){ continue; }
            //$comments = $item->Comment ? explode(',', $item->Comment) : [];
            $fieldType = DB::getSchemaBuilder()->getColumnType($table_name, $item->Field);
            $table_data[] = [$item->Field, $fieldType]; //$comments
        }

        return view('project_method.create', compact('table_data', 'key', 'table_name'));
    }

    public function store_method(Request $request)
    {
        $table_name = $request->table_name;

        $now = Carbon::now();

        $data = $request->all() + ['created_at'=>$now, 'updated_at'=>$now];

        $create = $this->project->create_method($table_name, $data);

        if($create)
        {
            return redirect(route('project.index'))->withSuccess('Success');
        }
        return redirect(route('project.index'))->withSuccess('Fail');  
    }

    //End Project Method


    public function destroy_folder($id)
    {
        dd('destroy');
    }


 
}
