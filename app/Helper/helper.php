<?php

if(!function_exists('getProjects')){
    function getUser($id){
        $data = \Vanguard\User::find($id);
        return $data;
    }
}

if(!function_exists('findProject')){
    function findProject($id){
        $data = \Vanguard\Model\Project::find($id);
        return $data;
    }
}

if(!function_exists('getProjects')){
    function getProjects(){
        $projects = \Vanguard\Model\Project::all();
        return $projects;
    }
}

if(!function_exists('getProjectsById')){
    function getProjectsById($id){
        $projects = \Vanguard\Model\Project::find($id);
        return $projects;
    }
}

if(!function_exists('getJoinProjects')){
    function getJoinProjects($user_id){
        $joined = \Vanguard\Model\AsignProject::where('user_id', $user_id)->first();
        return $joined;
    }
}

if(!function_exists('getJoinDevices')){
    function getJoinDevices($user_id){
        $joined = \Vanguard\Model\AsignDevice::where('user_id', $user_id)->first();
        return $joined;
    }
}

if(!function_exists('getProjectById')){
    function getProjectById($id){
        $projects = \Vanguard\Model\Project::with('parent_feature')->whereIn('id',$id)->get();
        return $projects;
    }
}

if(!function_exists('getDisplayProjects')){
    function getDisplayProjects($datas){
        $projects = [];

        if(count($datas) >= 1)
        {
            foreach($datas as $data)
            {
                $project_array = $data->toArray();
                $json_array = json_decode($project_array['project']);
                foreach($json_array as $json)
                {
                    $projects[] = $json->project_name;
                }
            }
        }
        return str_replace( array('\'', '"',';', '[', ']'), ' ', json_encode($projects));
    }
}

if(!function_exists('getDisplayMethods')){
    function getDisplayMethods($str){

        return str_replace( array('\'', '"',';', '[', ']'), ' ', $str);
    }
}

if(!function_exists('getDisplayFolders')){
    function getDisplayFolders($datas){
        $projects = [];
    
        $project_array = isset($datas['folder']) ? json_decode($datas['folder']) : []; 
        
            foreach($project_array as $data)
            {
                $projects[] = $data->folder_name;
            }
        
        return str_replace( array('\'', '"',';', '[', ']'), ' ', json_encode($projects));
    }
}

// if(!function_exists('getDisplayFolders')){
//     function getDisplayFolders($datas){
//         $projects = [];

//         foreach($datas as $data)
//         {
//             $json_array = json_decode($project_array['folder_name']);
//             foreach($json_array as $json)
//             {
//                 $projects[] = $json->project_name;
//             }
//         }
//         return str_replace( array('\'', '"',';', '[', ']'), ' ', json_encode($projects));
//     }
// }

if(!function_exists('getValueWithKey')){
    function getValueWithKey($value){
        
        $array = get_object_vars($value);
        $properties = array_keys($array);
        $key = $properties[3];
        return $value->$key;
    }
}

if(!function_exists('getFieldType')){
    function getFieldType($value){
        
        $type = '';

        switch ($value) {
            case 'datetime': $type = 'text'; break;
            case 'string': $type = 'text'; break;
            case 'integer': $type = 'number'; break;
            case 'float': $type = 'number'; break;
            case 'double': $type = 'number'; break;
            default: $type = 'text'; break;
        }
        return $type;
    }
}

if(!function_exists('getFolders')){
    function getFolders($account_id, $user_id){
        
        $data = \Vanguard\Model\Folder::where(['user_id'=>$account_id, 'user_asign_id'=>0])->orWhere('user_asign_id', $user_id) ->get();

        return $data;
    }
}

if(!function_exists('getJoinFolders')){
    function getJoinFolders($user_id){
        $joined = \Vanguard\Model\AsignFolder::where('user_id', $user_id)->first();
        return $joined;
    }
}

if(!function_exists('getProjectFeature')){
    function getProjectFeature($project_id){
        $data = \Vanguard\Model\Feature::where('project_id', $project_id)->first();
        return $data;
    }
}

if(!function_exists('getFolderById')){
    function getFolderById($id){
        $data = \Vanguard\Model\Folder::with('parent_project')->find($id);
        return $data;
    } 
}

if(!function_exists('getProjectFeatures')){
    function getProjectFeatures($keys){
        $data = \Vanguard\Model\Feature::whereIn('project_id', $keys)->get();
        return $data;
    }
}

if(!function_exists('getAsignDeviceFeatures')){
    function getAsignDeviceFeatures($keys){
        $data = \Vanguard\Model\AsignDeviceFeature::whereIn('project_id', $keys)->get();
        return $data;
    }
}
  
//Admin get Projects and Devices
if(!function_exists('getFoldersByUserId')){
    function getFoldersByUserId($user_id){
        $data = \Vanguard\Model\Folder::where('user_id', $user_id)->get();
        return $data;
    }
}

if(!function_exists('getAssignProjectByUserId')){
    function getAssignProjectByUserId($user_id){
        $data = \Vanguard\Model\Folder::where('user_id', $user_id)->get();
        return $data;
    }
}

if(!function_exists('getUsernameById')){
    function getUsernameById($user_id){
        $data = \Vanguard\user::find($user_id);
        return $data;
    }
}

if(!function_exists('getTextColor')){
    function getTextColor($status){
        if($status==1){
            return "badge-dark";
        }elseif($status==2){
            return "badge-info";
        }elseif($status==3){
            return "badge-warning";
        }elseif($status==4){
            return "badge-primary";
        }elseif($status==5){
            return "badge-danger";
        }
    }
}

if(!function_exists('getTextRole')){
    function getTextRole($role_id){
        if($role_id==1){
            return "Super Admin";
        }elseif($role_id==2){
            return "Admin";
        }elseif($role_id==3){
            return "Technical";
        }elseif($role_id==4){
            return "Client";
        }
    }
}

if(!function_exists('checkIsSuperAdmin')){
    function checkIsSuperAdmin($role_id, $family){

        $auth_role_id = auth()->user()->role_id;

        $auth_parent_id = auth()->user()->family;

        if($role_id==1){
            return "";
        } elseif($role_id == 2){ // && $parent_id==1
            return "disabled";
        } 
        // elseif($role_id==3){
        //     return "disabled";
        // } 
        else {
            return "";
        }
    }
}

if(!function_exists('checkNoAsignProject')){
    function checkNoAsignProject($role_id){

        $auth_role_id = auth()->user()->role_id;

        if($auth_role_id == 1)
        {
            if($role_id==4){
                return "disabled";
            } else {
                return "";
            } 
        
        } else {

            if($role_id==4 || $role_id==2){
                return "disabled";
            } else {
                return "";
            } 
        }

    }
}

if(!function_exists('checkCanRemoveAsign')){
    function checkCanRemoveAsign($role_id){

        $auth_role_id = auth()->user()->role_id;

        if($role_id==3 || $role_id==4){
            return "";
        } else {
            return "disabled";
        }
    }
}

if(!function_exists('checkIsNotAdmin')){
    function checkIsNotAdmin($role_id){
        if($role_id==2){
            return "";
        } else {
            return "disabled";
        }
    }
}

if(!function_exists('deleteAsign')){
    function deleteAsign($user_id){
        $user = \Vanguard\User::find($user_id);
        $user->parent_id = 1;
        $user->family = 1;
        $user->save(); 
        return $user;
    }
}

if(!function_exists('checkUserDelete')){
    function checkUserDelete($user_id, $id){
        if($user_id==$id){
            return "disabled";
        } else {
            return "";
        }
    }
}

if(!function_exists('getTitle')){
    function getTitle($title){
        $str = str_replace('Title_', '', $title);
        return $str;
    }
}

if(!function_exists('getTableName')){
    function getTableName($name){
        $str = str_replace('Title_', '', $name);
        return $str;
    }
}

if(!function_exists('checkIsNotSpecialCharacter')){
    function checkIsNotSpecialCharacter($str){
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $str))
        {
            return false;
        }else{
            return true;
        }
    }
}

if(!function_exists('getListMethod')){
    function getListMethod($str){

        $method = $str->project_method ? json_decode($str->project_method) : [];
        return $method;
    }
}

if(!function_exists('getTableHead')){
    function getTableHead($str){

        $header = str_replace('_', ' ', $str);
        return ucwords($header);
    }
}








