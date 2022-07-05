<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Repositories\AsignDevice\AsignDeviceRepository;
use DB;
 
class AsignDeviceController extends Controller
{
    private $asign;
    
    public function __construct(AsignDeviceRepository $asign)
    {   
		$this->asign = $asign;
	}

    public function index(Request $request)
    { 
        $users = $this->asign->paginateUser(20, $request->search);

        return view('asign_device.index');
    } 

    public function show($id)
    {    
        $data_id = explode(',', $id);
        $parent_id = $data_id[0];
        $user_id = $data_id[1];
        $joined_folder = getJoinFolders($user_id);
        $list_folders = $joined_folder ? json_decode($joined_folder->folder) : [];
       
        $folders = getFoldersByUserId($parent_id);
        $users = $this->asign->paginateUser(20, '');
        $asign_devices = getJoinDevices($user_id);
        $list_devices = $asign_devices ? json_decode($asign_devices->device) : [];
        $folders_project = [];

        foreach($folders as $item)
        {
            if(count($list_folders) == 0)
            {
                continue;
            }

            foreach($list_folders as $f)
            {
                if($item->id != $f->folder_id)
                {
                    continue 2;
                }
            }

            $devices = [];
            $project_feature = getProjectFeature($item->project_id);
            $project = findProject($item->project_id);
            $list_feature = [];
            $device_tables = isset($project_feature['feature']) ? json_decode($project_feature['feature']) : [];
            
            foreach($device_tables as $t)
            {
                $table_name = $project->name . '_' . $t; 
                $device = DB::table($table_name)->where('folder_id', $item->id)->get();
                if(count($device) > 0)
                {
                    $devices[$t] = $device;
                } 
            }     
            
            $folders_project[] = ['folder_id'=>$item->id, 'project_id'=>$item->project_id, 'folder_name'=>$item->name, 'devices'=> $devices];
        }
        //dd($assign_devices);
        return view('asign_device.index', compact('folders_project', 'user_id', 'parent_id', 'list_devices'));
    }

    //Store assign device to Worker and Client
    public function store(Request $request)
    {
        $group = array();
        $user_id = $request->user_id;
        $parent_id = $request->parent_id;
        $data = $request->device;
        
        foreach($data as $d)
        {
            $array = explode(',', $d);
            $group[] = ['folder_id'=>$array[0], 'project_id'=>$array[1], 'device_id'=>$array[2], 'device'=>$array[3]];
        }
        $create = $this->asign->create($user_id, $group);
        if($create){
            return redirect(route('asigndevice.show', $parent_id.','.$user_id))->withSuccess('Success');
        }
        return redirect(route('asigndevice.show',$parent_id.','.$user_id))->withSuccess('Fail');
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

    public function create(){}
}
