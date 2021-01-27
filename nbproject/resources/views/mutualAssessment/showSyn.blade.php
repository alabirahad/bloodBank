@if(sizeof($syndicateList)>1)
<div class="form-group">
    <label class="control-label col-md-4" for="synId">@lang('label.SYNDICATE') :<span class="text-danger"> *</span></label>
    <div class="col-md-4">
        {!! Form::select('syn_id', $syndicateList, null, ['class' => 'form-control js-source-states', 'id' => 'synId']) !!}
    </div>
</div>
@else
<div class="col-md-12">
    <div class="alert alert-danger alert-dismissable">
        <p><strong><i class="fa fa-bell-o fa-fw"></i> {!! __('label.NO_SYN_IS_ASSIGNED_TO_THIS_COURSE') !!}</strong></p>
    </div>
</div>
@endif