@extends('layouts.app')

@section('page-title', __('Feature'))
@section('page-heading', __('Feature'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        <a href="{{ route('project.index') }}"> @lang('Projects') </a>
    </li>

    <li class="breadcrumb-item active">
        @lang('Feature')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">

        <form action="" method="GET" id="users-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-6 mt-md-0 mt-2">
                    
                    <h4>{{ $project_name }}</h4>
                </div>
 
                <div class="col-md-6">
                    <a href="{{ route('project.feature.create', $id) }}" class="btn btn-primary btn-rounded float-right"> <i class="fas fa-plus mr-2"></i> @lang('Feature') </a>
                </div>

            </div>
        </form>

        @include('project_feature.partials.row-index')

    </div>
</div>

@stop

@section('scripts')
    <script>
        $("#status").change(function () {
            $("#users-form").submit();
        });
    </script>
@stop
