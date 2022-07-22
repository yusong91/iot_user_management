<?php
 
namespace Vanguard\Http\Controllers\Web; 

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Repositories\Device\DeviceRepository;
use DB;
use Illuminate\Support\Facades\Schema;

class DeviceController extends Controller
{
    private $device;
    
    public function __construct(DeviceRepository $device)
    {  
		$this->device = $device;
	} 
 
    public function index(Request $request){}
 
    //Show Device List by folder
    public function show($id, Request $request)
    {   
        $data = explode(',', $id);
        $pro_id = $data['1'];  
        $folder_id = $data['0']; 
        $project = getProjectsById($pro_id);       
        $features = getProjectFeature($pro_id);
        $list_feature = isset($features->feature) ? json_decode($features->feature) : [];
        $device_feature = $list_feature[0] ?? [];

        $device = $request->project ?? $device_feature;

        $table_name = $project->name . '_' . $device;

        $paginate = $this->device->paginate(10, $folder_id, $table_name, $request->search);
  
        return view('device.index', compact('paginate', 'pro_id', 'folder_id', 'list_feature', 'device_feature', 'table_name', 'device'));
    }
 
    //Form Create Device
    public function create($request)
    {   
        $data = explode(',', $request);
        $folder_id = $data[0];
        $device_feature = $data[1]; 
        $folder = getFolderById($folder_id);
        $project_id = $folder->project_id;
        $table_name = $folder->parent_project->name . '_' . $device_feature;
        
        $table = DB::select('SHOW FULL COLUMNS FROM '. $table_name);
        
        $table_data = [];
        
        $group_table_data = array();
        
        foreach($table as $item)
        {  
            if($item->Field == 'id' || $item->Field == 'project_id' || $item->Field == 'parent_id' || $item->Field == 'folder_id' || $item->Field == 'created_at' || $item->Field == 'updated_at'){ continue; }
            $comments = $item->Comment ? explode(',', $item->Comment) : [];
            $fieldType = DB::getSchemaBuilder()->getColumnType($table_name, $item->Field);
            $table_data[] = [$item->Field, $fieldType, $comments];
        }

        $check_is_title = false;
        $index = 0;
        $j = 0;
        for ($i=0; $i < count($table_data); $i++) { 
            
            $title = substr($table_data[$i][0],0,5);

            if($title == 'Title')
            {
                $index = $i;
                $check_is_title = true;
                continue;
            }

                $key = $table_data[$index][0];
                $group_table_data[$key][$j] = $table_data[$i];
                $j++;

        }

        return view('device.create', compact('group_table_data', 'table_name', 'project_id', 'folder_id'));
    }

    //Store Device
    public function store(Request $request)  
    {    
        $parent_id = auth()->user()->parent_id == 0 ? auth()->user()->id : auth()->user()->parent_id;
        $data = $request->all() + ['parent_id'=>$parent_id];

        $folder_id = $data['folder_id'];

        $project_id = $data['project_id'];

        $create = $this->device->create_device($data);
        if($create){ 

            return redirect(route('project.device.show', $folder_id .','. $project_id))->withSuccess('Success');
        }
        return redirect(route('project.device.show', $folder_id .','. $project_id))->withSuccess('Fail');
    }

    public function edit($data)
    {
        $array = explode(',', $data);
        $id = $array[0];
        $table_name = $array[1];
        $edit = DB::table($table_name)->find($id);
        $table = DB::select('SHOW FULL COLUMNS FROM '. $table_name);
        $table_data = [];
 
        foreach($table as $item)
        {  
            if($item->Field == 'id' || $item->Field == 'project_id' || $item->Field == 'parent_id' || $item->Field == 'folder_id' || $item->Field == 'created_at' || $item->Field == 'updated_at'){ continue; }
            $comments = $item->Comment ? explode(',', $item->Comment) : [];
            $fieldType = DB::getSchemaBuilder()->getColumnType($table_name, $item->Field);
            $table_data[] = [$item->Field, $fieldType, $comments];
        }
       
        return view('device.edit', compact('table_data', 'table_name', 'edit')); 
    }

    public function update(Request $request, $id)
    {
       //dd($request->all());

        $data = $request->all();
        $table_name = $data['table_name'];
        $project_id = $data['project_id'];
        $folder_id = $data['folder_id'];

        if(isset($data['_token']))
        {
            unset($data['_token']);
        }

        if(isset($data['table_name']))
        {
            unset($data['table_name']); 
        }

        if(isset($data['project_id']))
        {
            unset($data['project_id']); 
        }

        $update = $this->device->update($id, $table_name, $data);
        
        if($update)
        {
            return redirect(route('project.device.show', $folder_id .','. $project_id))->withSuccess('Success');
        }
        return redirect(route('project.device.show', $folder_id .','.$project_id))->withSuccess('Fail');
    }

    public function destroy($id)
    { 
        $data = explode(",", $id);

        $folder_id =  $data[0];

        $project_id = $data[1];
        
        $table_name =  $data[2];
        
        $device = $this->device->delete($table_name, $folder_id);
                
        if($device){
            return redirect(route('project.device.show', $folder_id .','. $project_id))->withSuccess('Success');
        }

        return redirect(route('project.device.show', $project_id))->withSuccess('Fail');
    }

    public function create_category($id)
    {
        $project = DB::table('projects')->find($id);
        $device_list = json_decode($project->device_list);
        return view('device.create-category', compact('project', 'device_list'));
    }

    public function store_category(Request $request)  
    {   
        $files = $request->allFiles();

        foreach($files as $file)
        {
            $file_name = $file->getClientOriginalName();
            $save_path = storage_path('json_file');
            $file->move($save_path, $file_name); 
            $path = storage_path('/json_file/'. $file_name);
            $json_data = json_decode(file_get_contents($path), true); 
            $project = $this->device->create($json_data); 
        }
        if($project){     
            return redirect(route('project.index'))->withSuccess('Success');
        }
        return redirect(route('project.index'))->withSuccess('Fail');
    }

    //$table = DB::getSchemaBuilder()->getColumnListing($table_name);
    // public function show($pro_id, Request $request)
    // {
    //     $project = DB::table('projects')->where('id', $pro_id)->first();
    //     //$table = DB::getSchemaBuilder()->getColumnListing('GatewayInfo');
    //     //$tableColumnInfos = DB::select('SHOW FULL COLUMNS FROM GatewayInfo');
    //     //foreach ($tableColumnInfos as $tableColumnInfo) {
    //         //dd($tableColumnInfo);
    //         //. ' ' . $tableColumnInfo->Comment
    //     //}
    //     $devices = $this->device->paginate($perPage = 20, $request->search, $pro_id);
    //     $raw_paginate = json_encode($devices); 
    //     $paginate = json_decode($raw_paginate);
    //     $project = DB::table('projects')->where('id', $pro_id)->first();
    //     $table = DB::getSchemaBuilder()->getColumnListing($project->name);
    //     return view('device.index', compact('devices', 'paginate', 'project', 'table'));
    // }
     // public function create($id)
    // {
    //     $project = DB::table('projects')->find($id);
    //     $table = DB::getSchemaBuilder()->getColumnListing($project->name);
    //     $table_data = [];
    //     dd('ok');
    //     foreach($table as $item)
    //     {   
    //         $fieldType = DB::getSchemaBuilder()->getColumnType($project->name, $item);
    //         $table_data[] = [$item, $fieldType];
    //     }
    //     $groups = array(); 
    //     foreach ($table_data as $value ) {
    //         if($value[0] == 'id' || $value[0] == 'project_id' || $value[0] == 'parent_id' || $value[0] == 'created_at' || $value[0] == 'updated_at'){ continue; }
    //         $groups[substr($value[0], 0, strpos($value[0], '_'))][] = $value;
    //     } 
    //     return view('device.create', compact('groups', 'project'));
    // }

     //$project = DB::table('projects')->where('id', $pro_id)->first();
        //$table = DB::getSchemaBuilder()->getColumnListing('GatewayInfo');
        //$tableColumnInfos = DB::select('SHOW FULL COLUMNS FROM GatewayInfo');
        //foreach ($tableColumnInfos as $tableColumnInfo) {
            //dd($tableColumnInfo);
            //. ' ' . $tableColumnInfo->Comment
        //} 

        // public function store(Request $request)  
    // {
    //     $parent_id = auth()->user()->parent_id == 0 ? auth()->user()->id : auth()->user()->parent_id;
    //     $data = $request->all() + ['parent_id'=>$parent_id];
    //     $create = $this->device->create($data);
    //     if($create){ 
    //         return redirect(route('device.show', $data['project_id']))->withSuccess('Success');
    //     }
    //     return redirect(route('device.show', $data['project_id']))->withSuccess('Fail');
    // }
}
