<?php

namespace Vanguard\Repositories\Folder;

use Vanguard\Model\Folder;
use Vanguard\Model\AsignFolder;   
use Carbon\Carbon;
use DB; 
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;  
 
class EloquentFolder implements FolderRepository 
{
    public function paginate($perPage, $search = null)
    {   
        $query = [];
        $keys = array();
        $asign_folder_keys = array();
        $user_id = auth()->user()->role_id == 2 ? auth()->user()->id : auth()->user()->parent_id;
        $joined_project = getJoinProjects($user_id);
        $projects = isset($joined_project->project) ? json_decode($joined_project->project) : [];
        //----------------------------

        if(auth()->user()->role_id == 2){

            foreach($projects as $item)
            {
                $keys[] = $item->project_id;
            }

            $query = Folder::query()->where('user_id', $user_id)->whereIn('project_id', $keys);

        } else {
            $keys = array();
            $joined_project = getJoinProjects(auth()->user()->parent_id);
            $projects = isset($joined_project->project) ? json_decode($joined_project->project) : [];

            foreach($projects as $item)
            {
                $keys[] = $item->project_id;
            }
            
            $asign_folder = AsignFolder::where('user_id', auth()->user()->id)->first();

            $folders = isset($asign_folder->folder) ? json_decode($asign_folder->folder) : [];
            foreach($folders as $item)
            {
                $asign_folder_keys[] = $item->folder_id;
            }
            $query = Folder::query()->where('user_id', $user_id)->whereIn('id', $asign_folder_keys)->whereIn('project_id', $keys);
        }

        //----------------------------
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

    public function create(array $data) 
    {   
        $folder = new Folder();
        $folder->name = $data['name'];
        $folder->user_id = $data['user_id'];
        $folder->project_id = $data['project_id'];
        $folder->parent_id = $data['parent_id'];
        $folder->save();
        return $folder;       
    } 

    public function all()
    {
        return Folder::all();
    }

    public function find($id)
    {
        return Folder::find($id); 
    }

    public function update($id, array $data){

        $folder = Folder::find($id);
        $folder->name = $data['name'];
        return $folder->save();
    }

    public function delete($id)
    {
        $delete = Folder::find($id);
        $result = $delete->delete();
        return $result;
    }
}