<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Repositories\AsignProject\AsignRepository;
use Vanguard\Repositories\AsignFolder\AsignFolderRepository;
use Vanguard\Repositories\AsignDeviceFeature\AsignDeviceFeatureRepository;

class AsignProjectController extends Controller
{
    private $asign;
    private $asignfolder;
    private $asigndevicefeature; 
    
    public function __construct(AsignRepository $asign, AsignFolderRepository $asignfolder, AsignDeviceFeatureRepository $asigndevicefeature)
    {  
		$this->asign = $asign;
        $this->asignfolder = $asignfolder;
        $this->asigndevicefeature = $asigndevicefeature;
	}

    //Index asign Project
    public function index(Request $request)
    {   
        $asigns = $this->asign->paginateUser($perPage = 50, $request->search);
        $raw_paginate = json_encode($asigns); 
        $paginate = json_decode($raw_paginate);  
        //dd($asigns);
        return view('asign_project.index', compact('asigns', 'paginate'));
    }   

    public function create(){}

    //Show assign project to AD or folder to Worker and Client
    public function show($id)
    {   
        $user = getUser($id); 
        $user_name = $user->username ?? '';
        $user_role = auth()->user()->role_id;
        $user_id = auth()->user()->id;
        $projects;
        $joined;
        $joined_project;
        
    
        switch ($user_role) { 
            case '1': 
                
                $projects = getProjects();
                $joined = getJoinProjects($id);
                $joined_project =  isset($joined->project) ? json_decode($joined->project) : [];
                return view('asign_project.asign', compact('projects', 'joined_project', 'id', 'user_name'));
                break; 
              
            default:
                
                $user = getUsernameById($id);
                $folders_project = getFolders($user_id, $id);
                $joined = getJoinFolders($id);
                $join_project = getJoinProjects($user_id);
                $folders = [];
                
                foreach($folders_project as $item)
                {
                    $project = json_decode($join_project->project) ?? [];

                    foreach($project as $p)
                    {
                       if($p->project_id == $item['project_id'])
                       {
                            $folders[] = $item;
                       }
                    }
                } 

                $joined_folders =  isset($joined->folder) ? json_decode($joined->folder) : [];
                return view('asign_folder.asign', compact('user','folders', 'id', 'joined_folders'));
                break;
        }
    }  

    //Store AD assign folder project to worker or client
    public function store_assignfolder(Request $request)
    {  
        $user_id = $request->user_id;
        $create = $this->asignfolder->create($request->all());
        if($create){
            return redirect(route('asignproject.show', $user_id))->withSuccess('Success');
        }
        return redirect(route('asignproject.show', $user_id))->withErrors('Fail');
    }

    //Asign project to user
    public function store(Request $request)
    {    
        $projects = $this->asign->create($request->all());
        if($projects){
            return redirect(route('asignproject.show', $request->user_id))->withSuccess('Success');
        }
        return redirect(route('asignproject.show', $request->user_id))->withErrors('Fail');
    }

    public function device_feature($id) 
    {

    }
 
    //Show assign device to Admin
    public function asign_device_feature($id) 
    {   
        $joined = getJoinProjects($id);
        $joined_project = isset($joined->project) ? json_decode($joined->project) : [];
        $project_keys = array(); 
        foreach($joined_project as $key)
        { 
            $project_keys[] = $key->project_id; 
        }
        
        $list_projects = getProjectById($project_keys);
        $assigned_device_features = getAsignDeviceFeatures($project_keys);
        return view('asign_feature.index', compact('assigned_device_features' ,'list_projects', 'id'));
    }

    //Asign Project's Device Feature
    public function store_device_feature(Request $request)
    {   
        $user_id = $request->user_id;
        $devices = $this->asigndevicefeature->create($request->all());
        if($devices){
            return redirect(route('project.device.feature.asign', $user_id))->withSuccess('Success');
        }
        return redirect(route('project.device.feature.asign', $user_id))->withErrors('Fail'); 
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}


// public function asign_device_feature($id) 
//     { 
        
//         $joined = getJoinProjects($id);
//         $joined_project = isset($joined->project) ? json_decode($joined->project) : [];
//         $project_keys = array(); 
//         foreach($joined_project as $key)
//         { 
//             $project_keys[] = $key->project_id; 
//         }
//         $list_projects = getProjectById($project_keys);
//         $join_device = getJoinDevices($id);
//         $list_join_device = isset($join_device->device) ? json_decode($join_device->device) : [];
        
//         return view('asign_feature.index', compact('list_projects', 'list_join_device', 'id'));
//     }
