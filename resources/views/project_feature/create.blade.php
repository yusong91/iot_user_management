@extends('layouts.app')

@section('page-title', __('Add Project Feature'))
@section('page-heading', __('Add Project Feature'))

@section('breadcrumbs') 
    <li class="breadcrumb-item">
        <a href="{{ route('project.feature.show', $id) }}">@lang('Feature')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Create')
    </li>
@stop

@section('content')

@include('partials.messages')

<form action="{{ route('project.feature.store') }}" method="POST" enctype="multipart/form-data" id="user-form" accept-charset="UTF-8">

	<div class="card">
		<div class="card-body">
			@include('project_feature.partials.create-form')
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

