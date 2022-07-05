<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Schema;
use Vanguard\Repositories\Feature\FeatureRepository;


class ProjectFeatureController extends Controller
{
    private $feature;
    
    public function __construct(FeatureRepository $feature)
    {  
		$this->feature = $feature;
	}
     
    public function index(){ }
 
    public function show($id)  
    {   
        $feature = $this->feature->findByProjectId($id);
        $feature_list = isset($feature->feature) ? json_decode($feature->feature) : [];
        $project = findProject($id);
        $project_name = $project->name ?? '';
        return view('project_feature.index', compact('id', 'feature_list', 'project_name'));
    } 

    public function create($id)
    {   
        $project = DB::table('projects')->find($id);
        $list_feature = json_decode($project->device_list);
        $project_feature = getProjectFeature($id);
        $features = isset($project_feature->feature) ? json_decode($project_feature->feature) : [];
       
        foreach($list_feature as $key => $value)
        {
            foreach($features as $f)
            {
                if($f == $value)
                {
                    unset($list_feature[$key]);
                }
            }
        }
        return view('project_feature.create', compact('id', 'project', 'list_feature', 'project_feature'));
    }

    public function store(Request $request)
    {
        $feature;
        $project_id = $request->project_id;
        $project_name = $request->pro_name;
        $data = ['project_id'=>$project_id, 'project_name'=>$project_name];

        $files = $request->allFiles();
        $json_data = array();

        foreach($files as $file)
        {
            $file_name = $file->getClientOriginalName();
            $save_path = storage_path('json_file');
            $file->move($save_path, $file_name); 
            $path = storage_path('/json_file/'. $file_name);
            $json_data[] = json_decode(file_get_contents($path), true); 
        }

        $feature = $this->feature->create($data, $json_data);  
        if($feature){     
            return redirect(route('project.index'))->withSuccess('Success');
        }
        return redirect(route('project.index'))->withSuccess('Fail');
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
