@extends('layouts.app')


@section('page-title', __('Add Project'))
@section('page-heading', __('Create New Project'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('project.index') }}">@lang('Project')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Create')
    </li>
@stop

@section('content')

@include('partials.messages')

<form action="{{ route('project.folder.store') }}" method="POST" id="user-form" accept-charset="UTF-8">

	<div class="card">
		<div class="card-body">
			@include('folder.partials.input-form')
		</div>
	</div>
    @csrf
	<div class="row mb-4">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">@lang('Create')</button>
        </div>
    </div>

</form>
 
@stop 



