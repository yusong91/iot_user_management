@extends('layouts.app')


@section('page-title', __('Add Device'))
@section('page-heading', __('Create New Device'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('device.index') }}">@lang('Device')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Create')
    </li>
@stop

@section('content')
 
@include('partials.messages') 

<form action="{{ route('project.device.store') }}" method="POST" id="user-form" accept-charset="UTF-8">

    <input type="hidden" name="table_name" value="{{ $table_name }}">
    <input type="hidden" name="folder_id" value="{{ $folder_id }}">
    <input type="hidden" name="project_id" value="{{ $project_id }}">

	<div class="card">
		<div class="card-body">
			@include('device.partials.input-form')
		</div>
	</div>
    @csrf
	<div class="row mb-4">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">
                @lang('Create')
            </button>
        </div>
    </div>

</form>
 
@stop 



