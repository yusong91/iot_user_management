@extends('layouts.app')

@section('page-title', __('Join Devices')) 
@section('page-heading', __('Join Devices'))

@section('breadcrumbs')

    <li class="breadcrumb-item">
        <a href="{{ route('asignproject.index') }}">Asign User</a>
    </li>
    
    <li class="breadcrumb-item active">
        Device
    </li>

@stop

@section('content')

@include('partials.messages') 
 
<form action="{{ route('asigndevice.store') }}" method="POST"   accept-charset="UTF-8">

<div class="card">
    <div class="card-body mb-0 pb-2 pt-3"> 
        <div class="table-responsive" id="users-table-wrapper">
            <div class="tab-content" id="nav-tabContent">        
                <div class="tab-pane fade show active px-2" id="details" role="tabpanel" aria-labelledby="nav-home-tab">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Asign User : </legend>

                        <input type="hidden" name="user_id" value="{{$user_id}}">
                        <input type="hidden" name="parent_id" value="{{$parent_id}}">
                        
                        <div class="container">
                            @if(count($folders_project) > 0)
                                @foreach($folders_project as $item)
                                    <th><h5>{{ $item['folder_name'] }}</h5></th>
                                    @foreach($item['devices'] as $key => $values)
                                        <div class="row row-cols-md-auto ml-4">
                                            <th><h5>{{$key}}</h5></th>
                                            @foreach($values as $value)
                                                <div class="col-3">
                                                    <div class="custom-control custom-checkbox m-0"> 
                                                        <div class="form-group">
                                                            <?php 

                                                                $check = '';

                                                                    foreach($list_devices as $d)
                                                                    {
                                                                        if ($d->device_id == $value->id && $d->folder_id == $value->folder_id) {
                                                                            
                                                                            $check = 'checked';
                                                                        }
                                                                    }
                                                            ?>              
                                                            <input type="checkbox" {{ $check }} class="form-check-input" name="device[]" value="{{$item['folder_id']}},{{$item['project_id']}},{{$value->id}},{{ $value->Name }}">
                                                            <label class="form-check-label">{{ isset($value->Name) ? $value->Name : '' }}</label>
                                                        </div> 
                                                    </div>
                                                </div> 
                                            @endforeach
                                        </div>
                                    @endforeach
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

