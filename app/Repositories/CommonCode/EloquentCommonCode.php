<?php

namespace Vanguard\Repositories\CommonCode;

use Vanguard\Model\CommonCode; 
use Carbon\Carbon;  
use DB;  
use Illuminate\Database\SQLiteConnection;

class EloquentCommonCode implements CommonCodeRepository 
{ 
    public function getEquipmentReport($key) 
    {
        return CommonCode::where('parent_id', $key)->with('children_equipment')->with('children_equipment.child_revenue')->with('children_equipment.child_maintenance')->get();
    }

    public function getEquipmentMovement($key)
    {
        return CommonCode::where('parent_id', $key)->with('children_equipment')->with('children_equipment.child_movement')->get();
    }

    public function getEquipmentOutstanding($key)
    {
        return CommonCode::where('parent_id', $key)->with('children_equipment')->get();
    }

    public function all()
    {
        return CommonCode::all();
    }

    public function create(array $data)  
    {
        return CommonCode::create($data);
    } 

    public function update($id ,array $data){

        return CommonCode::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        $commonCode = CommonCode::find($id);

        return $commonCode->delete();
    }

}