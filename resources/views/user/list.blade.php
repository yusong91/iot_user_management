@extends('layouts.app')

@section('page-title', __('Users'))
@section('page-heading', __('Users'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Users')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">

        <form action="" method="GET" id="users-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-4 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text"class="form-control input-solid"name="search"value="{{ Request::get('search') }}"placeholder="@lang('Search for users...')">

                            <span class="input-group-append">
                                @if (Request::has('search') && Request::get('search') != '')
                                    <a href="{{ route('users.index') }}"class="btn btn-light d-flex align-items-center text-muted"role="button"><i class="fas fa-times"></i></a>
                                @endif
                                <button class="btn btn-light" type="submit" id="search-users-btn">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </span>
                    </div>
                </div>

                <div class="col-md-2 mt-2 mt-md-0">
                    @if(auth()->user()->role_id == 1)
                        {!!
                            Form::select('status',$statuses,Request::get('status'),['id' => 'status', 'class' => 'form-control input-solid'])
                        !!}
                    @endif
                </div>
                
                <div class="col-md-6">
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-rounded float-right">
                        <i class="fas fa-plus mr-2"></i>
                        @lang('Add User')
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-borderless table-striped" style="width: 1500px;">
                <thead>
                <tr>
                    <th></th>
                    <th class="min-width-80">@lang('Username')</th>
                    <th class="min-width-100">@lang('Full Name')</th>
                    <th class="min-width-70">@lang('Role')</th>
                    <th class="min-width-80">@lang('Email')</th>
                    <!-- <th class="min-width-80">@lang('Registration Date')</th> -->
                    <!-- <th class="min-width-80">@lang('Status')</th> -->
                    <th class="text-center min-width-150">@lang('Action')</th>
                </tr>
                </thead>
                <tbody> 
                    @if (count($users))
                        @foreach ($users as $user)
                            @include('user.partials.row')
                        @endforeach
                    @else 
                        <tr>
                            <td colspan="7"><em>@lang('No Record')</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<nav aria-label="Page navigation example">
    <ul class="pagination"> 
        <?php $page = $paginate->current_page; ?>
        @foreach ($paginate->links as $item)
            <?php 
                $active = $item->label == $page ? 'active' : '';
            ?> 
            <li class="page-item {{$active}}"><a class="page-link" href="{{ $item->url }}"><?php echo $item->label; ?></a></li>
        @endforeach 
    </ul>
</nav>

@stop

@section('scripts')
    <script>
        $("#status").change(function () {
            $("#users-form").submit();
        });
    </script>
@stop