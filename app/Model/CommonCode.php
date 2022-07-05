<?php

namespace Vanguard\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable; 
use Spatie\EloquentSortable\SortableTrait;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class CommonCode extends Model
{
  
    protected $table = 'commond_codes';

    public $sortable = [
        'order_column_name' => 'ordering',
        'sort_when_creating' => true,
    ];

    protected $fillable = [
        'user_id', 'key', 'value', 'parent_id', 'link', 'image',
        'active', 'ordering', 'created_by', 'updated_by',
    ];

    function children(){
        return $this->hasMany(CommonCode::class, 'parent_id');
    }

    function parent(){
        return $this->belongsTo(CommonCode::class,'parent_id','id');
    }

    public function scopeCommonCode($query, $value)
    {
        return $query->where('key', $value)->with('children');
    }
}
