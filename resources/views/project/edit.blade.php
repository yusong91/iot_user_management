@extends('layouts.app')

<style>

    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
                box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
    }

</style>

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

<form action="{{ route('project.update', $edit->id) }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">

	<div class="card"> 
		<div class="card-body">
			@include('project.partials.input-form')
		</div>
	</div>
    @csrf
	<div class="row"> 
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">
                @lang('Edit Project')
            </button>
        </div>
    </div>

</form>
 
@stop

