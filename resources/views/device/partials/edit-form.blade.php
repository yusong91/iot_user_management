<style>
    
    .form-group.floating>label {
        bottom: 34px;
        left: 8px;
        position: relative;
        background-color: white;
        padding: 0px 5px 0px 5px;
        font-size: 1.1em;
        transition: 0.1s;
        pointer-events: none;
        font-weight: 500 !important;
        transform-origin: bottom left;
    }
    .form-control.floating:focus~label{
        transform: translate(1px,-85%) scale(0.80);
        opacity: .8;
        color: #005ebf;
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
	<legend class="scheduler-border">{{ str_replace('_', ' ', $table_name) }}</legend>
    <input type="hidden" name="table_name" value="{{ $table_name }}">
    <input type="hidden" name="folder_id" value="{{ $edit->folder_id}}">
    <input type="hidden" name="project_id" value="{{ $edit->project_id }}">
 
    <div class="container">
        <div class="row row-cols-md-auto mt-3">
            @foreach($table_data as $value)
                <div class="col-4">
                    <div class="form-group floating m-0">
                        @if(count($value[2]) > 0)
                            
                            <select class="form-control" name="{{$value[0]}}">
                                <option>{{ str_replace('_', ' ', $value[0]) }}</option>
                                @foreach($value[2] as $item)
                                    <?php $key = $value[0]; ?>
                                    <option value="{{ $item }}" {{ $item == $edit->$key ? 'selected' : '' }}  >{{ $item }}</option>
                                @endforeach
                            </select>
                            
                        @else
                            <?php $key = $value[0]; ?>
                            <input type="{{ getFieldType($value[1]) }}" step="any" name="{{$value[0]}}" value="{{$edit->$key}}" class="form-control floating" onfocus="myFunction('{{$value[0]}}');" id="{{ str_contains(strtolower($value[0]), 'date') ? $value[0]  : ''  }}" required >
                            <label ><span class="text-color"> {{ str_replace('_', ' ', $value[0]) }} </span></label>
                        @endif
                    </div> 
                </div>
            @endforeach
        </div>
    </div>
</fieldset>

