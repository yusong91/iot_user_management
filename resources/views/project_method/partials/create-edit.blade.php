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
    <legend class="scheduler-border"> @lang('app.'.$key) Info</legend>

    <div class="container">
        <div class="row row-cols-md-auto mt-2">

            @foreach($table_data as $key => $value)

                <div class="col-4">
                    <div class="form-group floating m-0">
                    
                        <input type="{{ getFieldType($value[1]) }}" step="any" name="{{$value[0]}}" class="form-control floating" required >
                        <label ><span class="text-color"> {{ str_replace('_', ' ', $value[0]) }} </span></label>
                         
                    </div> 
                </div>

            @endforeach

        </div>
    </div>

</fieldset>

