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
	<legend class="scheduler-border">Create Folder</legend>
    <div class="container mt-2">
        <div class="row">
            <div class="col-5">
                <div class="form-group floating m-0">
                       
                    <input type="text"  name="name" class="form-control floating"  required >
                    <label ><span class="text-color">Name</span></label>
                       
                </div> 
            </div>

            <div class="col-5">
                <div class="form-group floating m-0">
                       
                    <select name="project_id" class="form-control floating" required>
                        <option value="">Select Feacture</option>
                        @foreach($list_joins as $item)
                            <option value="{{$item->project_id}}">{{ $item->project_name }}</option>
                        @endforeach
                    </select>
   
                </div> 
            </div>

        </div>
    </div>
		
</fieldset>

