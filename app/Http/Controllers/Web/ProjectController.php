<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\Project\CreateRequest;
use Illuminate\Support\Facades\Storage;
use File;
use DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Vanguard\Repositories\Project\ProjectRepository;
use Vanguard\Repositories\Device\DeviceRepository;
use Vanguard\Repositories\Folder\FolderRepository;

  
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
        return view('project.create');
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
             
            return redirect(route('project.index'))->withSuccess('Success');
        }

        return redirect(route('project.index'))->withSuccess('Fail');
    }

    public function show($id)
    {

    }
 
    public function edit($id)
    {
        $edit = $this->project->find($id);
        return view('project.edit', compact('edit'));
    }

    public function update(Request $request, $id)
    {
        $file_json = $request->file('file_json');

        $data = $request->all();

        if($file_json)
        {
            $file_name = $file_json->getClientOriginalName();
            $save_path = storage_path('json_file');
            $file_json->move($save_path, $file_name); 
            $path = storage_path('/json_file/'. $file_name);
            $json_data = json_decode(file_get_contents($path), true); 
            $data = $request->all() + $json_data;
        }
        
        $project = $this->project->update($id, $data);

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

    public function destroy_folder($id)
    {
        dd('destroy');
    }


 
}
