<?php

namespace Vanguard\Repositories\AsignFolder;

use Vanguard\Model\AsignFolder;   
use Vanguard\Model\Folder;
use Carbon\Carbon;
use DB; 
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; 

class EloquentAsignFolder implements AsignFolderRepository 
{
    public function paginate($perPage, $search = null)
    {  
        $query = AsignFolder::query()->where('user_id', auth()->user()->id);

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
        $user_id = $data['user_id'];
        $parent_id = auth()->user()->parent_id == 0 ? auth()->user()->id : auth()->user()->parent_id;
        $list_folder_asigned = array();
        $json = [];
        $folders = $data['folder'] ?? [];

        $folder = Folder::where('user_asign_id', $user_id)->get();

        foreach ($folder as $item) {
            $item->user_asign_id = 0;
            $item->save();
        }
    
        foreach($folders as $item)
        {
            $ids = explode(' ', $item);

            $folder_id = $ids[0];
            $project_id = $ids[1];
            $folder_name = $ids[2];

            $list_folder_asigned[] = $folder_id;

            $json[] = ['folder_id'=>$folder_id, 'project_id'=>$project_id, 'folder_name'=>$folder_name]; 
        }
        
        $asigned = AsignFolder::where('user_id', $user_id)->first();

        if($asigned)
        {
            foreach($list_folder_asigned as $item)
            {
                $folder = Folder::find($item);
                $folder->user_asign_id = $user_id;
                $folder->save();
            }

            $asigned->folder = json_encode($json);
            return $asigned->save();
        }

        $folder = new AsignFolder();
        $folder->user_id = $user_id;
        $folder->parent_id = $parent_id;
        $folder->folder = json_encode($json);
        $saved = $folder->save();     

        if($saved)
        {
            foreach($list_folder_asigned as $item)
            {
                $folder = Folder::find($item);
                $folder->user_asign_id = $user_id;
                $folder->save();
            }
        }

        return $saved;
    } 

    public function all()
    {
        return AsignFolder::all();
    }

    public function find($id)
    {
        return AsignFolder::find($id); 
    }

    public function update($id, array $data){

        $folder = AsignFolder::find($id);
        $folder->name = $data['name'];
        return $folder->save();
    }

    public function delete($id)
    {
        $delete = AsignFolder::find($id);
        $result = $delete->delete();
        return $result;
    }
}