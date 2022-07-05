@extends('layouts.app')

@section('page-title', __('Join Projects')) 
@section('page-heading', __('Join Projects'))

@section('breadcrumbs')

    <li class="breadcrumb-item">
        <a href="{{ route('asignproject.index') }}">Asign Project</a>
    </li>
    
@stop

@section('content')

@include('partials.messages')

<form action="{{ route('asign.folder.store') }}" method="POST"   accept-charset="UTF-8">

<div class="card">
    <div class="card-body mb-0 pb-2 pt-3"> 
        <div class="table-responsive" id="users-table-wrapper">
            <div class="tab-content" id="nav-tabContent">        
                <div class="tab-pane fade show active px-2" id="details" role="tabpanel" aria-labelledby="nav-home-tab">
                    <fieldset class="scheduler-border">
	                    <legend class="scheduler-border">Join Projects : {{ $user->username ?? '' }}</legend>
                        
                        <input type="hidden" name="user_id" value="{{ $id }}" >
                        <div class="container">
                            <div class="row row-cols-md-auto">
                            @if(count($folders) > 0)
                                @foreach($folders as $item)    
                                
                                    <?php 
                                        $check = '';
                                        foreach ($joined_folders as $join){                    
                                            if ($item->id == $join->folder_id) {
                                                $check = 'checked';
                                            }   
                                        }
                                    ?>

                                    <div class="col-4">
                                        <div class="custom-control custom-checkbox m-0">                        
                                            <div class="form-group">
                                                <input type="checkbox" {{ $check }} class="form-check-input" name="folder[]" value="{{$item->id}} {{$item->project_id}} {{$item->name}}">
                                                <label class="form-check-label">{{ $item->name }}</label>
                                            </div>
                                        </div> 
                                    </div>

                                @endforeach
                            @else
                                No project found.
                            @endif
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
@csrf
<button type="submit" class="btn btn-primary">Save</button>

</form>

@stop
