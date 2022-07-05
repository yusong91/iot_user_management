@extends('layouts.app')
@section('page-title', __('Asign User'))
@section('page-heading', __('Asign User'))
@section('content')
@include('partials.messages')

<div class="card">
    <div class="card-body">
        <form action="{{ route('asignuser.update', $id) }}" method="POST" accept-charset="UTF-8">
            @method('PUT')
            <div class="row">
                <div class="col-4"> 
                    <label ><span class="text-color">Name</span></label>
                    <input type="text" disabled class="form-control floating"  value="{{ $user->username }}" >
                </div>
                <div class="col-3">
                    <label ><span class="text-color">Role</span></label>
                    <input type="text" disabled class="form-control floating" value="{{ $user->role->name }}" >
                </div>
                <div class="col-5">
                    <label ><span class="text-color">Joined Project</span></label>
                    <input type="text" disabled class="form-control floating" value="{{ count($user->children_asignproject) >= 1 ? getDisplayProjects($user->children_asignproject) : '' }}" >
                </div> 
            </div>

            <div class="row mt-4">
                <div class="col-4">
                    <label ><span class="text-color">From</span></label>
                    <input type="text" disabled class="form-control floating" value="{{$parent->username}}" >
                </div>
                <div class="col-3">
                    <label ><span class="text-color">Role</span></label>
                    <input type="text" disabled class="form-control floating" value="{{ $parent->role->name }}" >
                </div>
                <div class="col-5">
                    <label ><span class="text-color">To</span></label>
                    <select name="parent_id" class="form-control floating" required>
                        <option value="">Please Select</option>
                        @foreach($list_asign_to as $item)
                        @if($item->id == $parent->id)
                                @continue
                            @endif 
                            <option value="{{$item->id}}">{{ $item->username }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @csrf
            <button type="submit" class="btn btn-primary  mt-4">@lang('Save')</button>
            
        </form> 
    </div>
</div>



@stop
