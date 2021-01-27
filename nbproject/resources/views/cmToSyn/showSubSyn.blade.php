<div class="form-group">
    <label class="control-label col-md-4" for="subSynId">@lang('label.SUB_SYN') :<span class="text-danger"> *</span></label>
    <div class="col-md-4">
        {!! Form::select('sub_syn_id', $subSynList, null,  ['class' => 'form-control js-source-states', 'id' => 'subSynId']) !!}
    </div>
</div>