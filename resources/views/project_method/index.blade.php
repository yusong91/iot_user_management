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

<div class="card">
    <div class="card-body">

        <div class="border-bottom-light">
            <div class="row my-1 flex-md-row">
                
                <div class="col-md-12">
                    <a href="{{ route('project.method.create', $key) }}" class="btn btn-primary btn-rounded float-right"> <i class="fas fa-plus mr-2"></i> @lang('app.'.$key) </a>
                </div>

            </div>
        </div>

        @include('project_method.partials.row-index')
    
    </div>
</div>

@stop

