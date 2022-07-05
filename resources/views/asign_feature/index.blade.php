@extends('layouts.app')
@section('page-title', __('Feature'))
@section('page-heading', __('Join Projects'))
@section('breadcrumbs')

    <li class="breadcrumb-item">
        <a href="{{ route('asignproject.index') }}">Assign Feature</a>
    </li>
    
    <li class="breadcrumb-item active">
        Device's Feature
    </li>

@stop
@section('content')
@include('partials.messages')
 
<form action="{{ route('project.device.feature.store') }}" method="POST"   accept-charset="UTF-8">

    <div class="card"> 
        <div class="card-body mb-0 pb-2 pt-3"> 
            <div class="table-responsive" id="users-table-wrapper">
                <div class="tab-content" id="nav-tabContent">        
                    <div class="tab-pane fade show active px-2" id="details" role="tabpanel" aria-labelledby="nav-home-tab">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Project Feature : </legend>

                            <input type="hidden" name="user_id" value="{{ $id }}" />
                             
                            <div class="container mt-3"> 

                            @if(count($list_projects) > 0)
                                @foreach($list_projects as $item)
                                    <div class="row row-cols-md-auto mt-2">
                                        
                                        <th><h5>{{$item->name}}</h5></th>

                                        <?php $list_device = isset($item->device_list) ? json_decode($item->device_list) : []; ?> 
                                            
                                            @foreach(json_decode($item->parent_feature->feature) as $device)
                                                <?php 

                                                    $check = '';
                                                
                                                    foreach ($assigned_device_features as $join){  

                                                        $features = json_decode($join->device_feature);
                                                        
                                                        foreach($features as $f)
                                                        {
                                                            if ($item->id == $join->project_id && $device == $f) {
                                                                
                                                                $check = 'checked';
                                                            }
                                                        }
                                                    }
                                                ?>

                                                <div class="col-3">
                                                    <div class="custom-control custom-checkbox m-0"> 
                                                        <div class="form-group">
                                                            
                                                            <input type="checkbox" {{ $check }} class="form-check-input" name="device[]" value="{{$item->id}} {{$device}}" >
                                                            <label class="form-check-label"> {{ $device }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                    </div>
                                @endforeach
                            @else
                                <div class="row row-cols-md-auto mt-2">
                                    No feature found.
                                </div>
                            @endif
                                
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @csrf
    <button type="submit" class="btn btn-primary">Save</button>
</form>

@stop
