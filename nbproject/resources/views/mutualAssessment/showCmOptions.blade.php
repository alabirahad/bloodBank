@if(!empty($cmList))
<div class="form-group">
    <label class="control-label col-md-4" for="cmId">@lang('label.CM') :<span class="text-danger"> *</span></label>
    <div class="col-md-4">
        {!! Form::select('cm_id', $cmList, null, ['class' => 'form-control js-source-states', 'id' => 'cmId']) !!}
    </div>
</div>
@endif