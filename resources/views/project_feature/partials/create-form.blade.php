<style>
    
    .form-group.floating>label {
        bottom: 34px;
        left: 8px;
        position: relative;
        background-color: white;
        padding: 0 0 0 5px;
        font-size: 1.1em;
        transition: 0.1s;
        pointer-events: none;
        font-weight: 400 !important;
        transform-origin: bottom left;
    } 

    .form-control.floating:focus~label{
        transform: translate(1px,-85%) scale(0.80);
        opacity: .8;
        /* color: #005ebf; */
    }

    .form-control.floating:valid~label{
        transform-origin: bottom left;
        transform: translate(1px,-85%) scale(0.80);
        opacity: .8;
    }

    .text-color {
        color: #6C6C6C; 
    }

</style>

<fieldset class="scheduler-border">
	<legend class="scheduler-border">Project Feature</legend>

    <input type="hidden" name="pro_name" value="{{ $project->name }}">
    <input type="hidden" name="project_id" value="{{ $project->id }}">

    <div class="container">

        <div class="row row-cols-md-auto">

            @if(count($list_feature) > 0)

                @foreach($list_feature as $item)
                
                    <div class="col-md-6 mb-4">
                        <div class="form-floating">
                            <label for="{{$item}}">{{ str_replace('_', ' ', $item) }}</label> <span>*</span>
                            <div class="custom-file">
                                <input id="file-upload" class="custom-file-input" type="file" name="{{$item}}">
                                <label for="file-upload" class="custom-file-label">@lang('Choose File')</label>
                            </div>
                        </div>
                    </div>

                @endforeach

            @else

                No more feature

            @endif

        </div>

    </div>
		
</fieldset>

