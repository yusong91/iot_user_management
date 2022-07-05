@extends('layouts.app')

@section('page-title', __('Asign User'))
@section('page-heading', __('Asign User'))

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">

        <form action="" method="GET" id="users-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-6 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control input-solid" name="search" value="{{ Request::get('search') }}" placeholder="@lang('Search for projects...')">

                            <span class="input-group-append">
                                @if (Request::has('search') && Request::get('search') != '')
                                    <a href="{{ route('asignproject.index') }}" class="btn btn-light d-flex align-items-center text-muted"role="button"><i class="fas fa-times"></i></a>
                                @endif
                                <button class="btn btn-light" type="submit" id="search-users-btn">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </span>
                    </div>
                </div>

            </div>
        </form>

        @include('asign_user.partials.row-index')
    </div>
</div>



@stop
