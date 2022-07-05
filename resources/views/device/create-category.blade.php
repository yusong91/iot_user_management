@extends('layouts.app')

@section('page-title', __('Add Device Category'))
@section('page-heading', __('Create New Device Category'))

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

<form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data" id="user-form" accept-charset="UTF-8">

	<div class="card">
		<div class="card-body">
			@include('device.partials.form-category')
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

