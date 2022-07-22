@extends('layouts.app')

@section('page-title', __('Project Method'))
@section('page-heading', __('Project Method'))

@section('breadcrumbs')
    
    <li class="breadcrumb-item active">
        @lang('app.'.$key)
    </li>
@stop

@section('content')
@include('partials.messages') 

<form action="{{ route('project.method.store') }}" method="POST" id="user-form" accept-charset="UTF-8">

    <input type="hidden" name="table_name" value="{{ $table_name }}">
    <input type="hidden" name="user_id" value="29">
    <input type="hidden" name="project_id" value="6">

	<div class="card">
		<div class="card-body">
			@include('project_method.partials.create-edit')
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



