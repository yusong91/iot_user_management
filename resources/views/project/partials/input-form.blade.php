<fieldset class="scheduler-border">
	<legend class="scheduler-border">@lang('Project Info')</legend>
				
    <div class="row mt-2">
        <div class="col-md-6">
            <div class="form-floating">
                <label for="name">@lang('Project Name')</label> <span>*</span>
                <input name="name" type="text" class="form-control" id="name" placeholder="@lang('Project Name')" value="{{   $edit->name ?? '' }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-floating">
                <label for="file_json">@lang('Import JSON')</label> <span>*</span>
                <div class="custom-file">
                    <input id="file-upload" class="custom-file-input" type="file" name="file_json">
                    <label for="file-upload" class="custom-file-label">@lang('Choose File')</label>
                </div>
            </div>
        </div>             
    </div>
</fieldset>