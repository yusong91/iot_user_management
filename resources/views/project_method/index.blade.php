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

        <form action="" method="GET" id="users-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-6 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control input-solid" name="search" value="{{ Request::get('search') }}" placeholder="@lang('Search...')">

                            <span class="input-group-append">
                                @if (Request::has('search') && Request::get('search') != '')
                                    <a href="" class="btn btn-light d-flex align-items-center text-muted" role="button"> <i class="fas fa-times"></i> </a>
                                @endif
                                <button class="btn btn-light" type="submit" id="search-users-btn"> <i class="fas fa-search text-muted"></i> </button>
                            </span>
                    </div>
                </div>

                    <div class="col-md-6">
                        <a href="{{ route('project.method.create', $key) }}" class="btn btn-primary btn-rounded float-right"> <i class="fas fa-plus mr-2"></i> @lang('app.'.$key) </a>
                    </div>

            </div>
        </form>

        @include('project_method.partials.row-index')
    
    </div>
</div>

@stop

