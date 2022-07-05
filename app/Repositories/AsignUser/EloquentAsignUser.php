<?php

namespace Vanguard\Repositories\AsignUser;

use Vanguard\User; 
use Carbon\Carbon;
use DB; 
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; 

class EloquentAsignUser implements AsignUserRepository 
{    

    public function all() 
    {
        return User::all();
    }

    public function find($id)
    {
        return User::with(['role', 'children_asignproject'])->find($id); 
    }

    public function get_list_role($user_id, $role_id, $family_id) 
    {   
        $auth_role_id = auth()->user()->role_id;

        if($auth_role_id == 2 && $role_id == 3)
        {
            return User::where('family', $family_id)->where('role_id', 3)->where('id','!=',$user_id)->get();
        
        } else //if($auth_role_id == 2 && $role_id == 4)
        {
        //     return User::where('family', $family_id)->where('role_id', 3)->where('id','!=',$user_id)->get();
        
        // } elseif($auth_role_id == 3 && $role_id == 4)
        // {
            return User::where('family', $family_id)->where('role_id', 3)->where('id','!=',$user_id)->get();
        }

        return [];
    
    }

    public function find_parent($parent_id)
    {
        return User::with(['role'])->find($parent_id); 
    }

    public function update($id, array $data){
        
        $parent = User::find($data['parent_id']);
        $user = User::find($id);
        $user->parent_id = $data['parent_id'];
        $user->family = $parent->family == 0 ? $parent->id : $parent->family;
        $user->save();
        return $user;
    }

    public function create(array $data) 
    {   
       
    }

    public function delete($id)
    {
        
    }




}