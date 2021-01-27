@if(!empty($cmList))
<div class="cm-list">
    {!! Form::open(array('group' => 'form', 'url' => 'mutualAssessment/generate','class' => 'form-horizontal','id' => 'submitForm')) !!}   

    <div class="row">
        <div class="col-md-12 mb-10">
            <span class="label label-sm label-green-seagreen">
                @lang('label.TOTAL_NUMBER_OF_CM') : <strong>{{ !empty($cmList) ? sizeof($cmList) : 0 }}</strong> 
            </span> &nbsp;
            <span class="label label-sm label-blue-steel">
                @lang('label.TOTAL_EXPORTED_MARK_SHEET') : <strong>{{ !empty($exportCmIdArr) ? sizeof($exportCmIdArr) : 0 }}</strong> 
            </span> &nbsp;
            <span class="label label-sm label-purple">
                @lang('label.TOTAL_DELIVERED_MARK_SHEET') : <strong>{{ !empty($deliverStatusArr) ? sizeof($deliverStatusArr) : 0 }}</strong>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive max-height-500 webkit-scrollbar my-datatable">
                <table class="table table-bordered table-hover relation-view-2" id="cmListTable">
                    <thead>
                        <tr>
                            <th class="vcenter text-center">@lang('label.SL')</th>
                            <th class="vcenter">@lang('label.PERSONAL_NO')</th>
                            <th class="vcenter">@lang('label.RANK')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
                            <th class="vcenter">@lang('label.PHOTO')</th>
                            <th class="vcenter text-center" width="85">@lang('label.DELIVERED_TO_CM')</th>
                            <th class="vcenter text-center" width="50">@lang('label.EXPORT_STATUS')</th>
                            <th class="vcenter text-center" width="60">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sl = 1; @endphp 
                        @foreach($cmList as $cm) 
                        <tr>
                            <td class="vcenter text-center"><strong>{{ $sl++ }}</strong></td>
                            <td class="vcenter">{{ $cm['personal_no'] }}</td>
                            <td class="vcenter">{{ $cm['rank'] }}</td>
                            <td class="vcenter">{{ $cm['full_name'] }}</td>
                            <td class="vcenter" width="50px">
                                @if(!empty($cm['photo']) && File::exists('public/uploads/cm/' . $cm['photo']))
                                <img width="50" height="60" src="{{URL::to('/')}}/public/uploads/cm/{{$cm['photo']}}" alt="{{$cm['full_name'] ?? ''}}"/>
                                @else
                                <img width="50" height="60" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{$cm['full_name'] ?? ''}}"/>
                                @endif
                            </td>
                            <td class="vcenter text-center">
                                @if (in_array($cm['cm_id'],$exportCmIdArr))   
                                <div class="md-checkbox has-success  text-center">
                                    <input type="checkbox" data-id="{{ $cm['cm_id'] }}" id="checkbox{{ $cm['cm_id'] }}" class="md-check deliver-status" {!! in_array($cm['cm_id'], $deliverStatusArr) ? 'checked' : '' !!}>
                                           <label for="checkbox{{ $cm['cm_id'] }}">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                </div>
                                @endif
                            </td>
                            <td class="vcenter text-center" width="50">
                                @if (in_array($cm['cm_id'],$exportCmIdArr))
                                <i class="fa fa-check font-green-jungle" aria-hidden="true"></i>
                                @else
                                <i class="fa fa-times font-red-thunderbird" aria-hidden="true"></i>
                                @endif
                            </td>
                            <td class="vcenter text-center">
                                <button data-id="{{ $cm['cm_id'] }}" type="button"  class=" btn btn-success btn-xs previewMarkingSheet">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {!! Form::hidden('course_id', $courseId) !!} 
    {!! Form::hidden('term_id', $termId) !!} 
    {!! Form::hidden('syn_id', $synId) !!} 
    {!! Form::hidden('sub_syn_id', $subSynId) !!} 
    {!! Form::hidden('event_id', $eventId) !!} 
    {!! Form::hidden('cm_id', null, ['class' => 'cm-id']) !!}   

    {!! Form::close() !!}  
</div>
@else
<div class="col-md-12">
    <div class="alert alert-danger alert-dismissable">
        <?php $noCMMessage = !empty($subSynId) ? __('label.NO_CM_IS_ASSIGNED_TO_THIS_SUB_SYNDICATE') : __('label.NO_CM_IS_ASSIGNED_TO_THIS_SYNDICATE');?>
        <p><strong><i class="fa fa-bell-o fa-fw"></i> {!! $noCMMessage !!}</strong></p>
    </div>
</div>
@endif

<style>
    .mb-10{
        margin-bottom: 10px;
    }
    .p-5{padding:5px;}
    .infos span{
        margin-right: 10px;
    }
</style>
<script>
    $(document).ready(function () {
        $('.relation-view-2').tableHeadFixer();
        $('#cmListTable').DataTable();
    });
</script>



