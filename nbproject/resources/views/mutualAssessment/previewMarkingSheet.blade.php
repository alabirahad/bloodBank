<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="bottom" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">
            @lang('label.CLOSE')
        </button>
        <h3 class="modal-title text-center">
            @lang('label.MUTUAL_ASSESSSMENT') @lang('label.MARKING_SHEET')
        </h3>
    </div>
    {!! Form::open(array('group' => 'form', 'url' => 'mutualAssessment/generate','class' => 'form-horizontal','id' => 'submitForm')) !!}   
    <div class="modal-body">

        <div class="row">
            <div class="col-md-12 infos">
                <span>@lang('label.COURSE') : <strong>{{ $courseName->name}}</strong></span>
                <span>@lang('label.EVENT') : <strong>{{ $eventName->name }}</strong></span>
                <span>@lang('label.SYNDICATE') : <strong>{{ $syndicate->name }}</strong></span>
                @if(!empty($subSyndicate->name))
                <span>@lang('label.SUB_SYNDICATE') : <strong>{{ $subSyndicate->name }}</strong></span>
                @endif
                <span>@lang('label.MARKING_CM') : <strong>{{ $cmName->full_name }}</strong></span>
            </div>
        </div>
        @if(!$cmList->isEmpty())
        <div class="table-responsive max-height-500 webkit-scrollbar my-datatable">
            <table class="table table-bordered table-hover relation-view-2" id="cmListTable2">
                <thead>
                    <tr>
                        <th class="vcenter text-center">@lang('label.SL')</th>
                        <th class="vcenter">@lang('label.PERSONAL_NO')</th>
                        <th class="vcenter">@lang('label.RANK')</th>
                        <th class="vcenter">@lang('label.NAME')</th>
                        <th class="vcenter">@lang('label.PHOTO')</th>
                        <th class="vcenter">@lang('label.POSITION')</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sl = 1; @endphp 
                    @foreach($cmList as $cm) 
                    <tr>
                        <td class="vcenter text-center"><strong>{{ $sl++ }}</strong></td>
                        <td class="vcenter">{{ $cm->personal_no }}</td>
                        <td class="vcenter">{{ $cm->rank }}</td>
                        <td class="vcenter">{{ $cm->full_name }}</td>
                        <td class="vcenter" width="50px">
                            @if(!empty($cm->photo) && File::exists('public/uploads/cm/' . $cm->photo))
                            <img width="50" height="60" src="{{URL::to('/')}}/public/uploads/cm/{{$cm->photo}}" alt="{{$cm['full_name'] ?? ''}}"/>
                            @else
                            <img width="50" height="60" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{$cm['full_name'] ?? ''}}"/>
                            @endif
                        </td>
                        <td class="vcenter"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    {!! Form::hidden('course_id', $courseId) !!} 
    {!! Form::hidden('term_id', $termId) !!} 
    {!! Form::hidden('syn_id', $synId) !!} 
    {!! Form::hidden('sub_syn_id', $subSynId) !!} 
    {!! Form::hidden('event_id', $eventId) !!} 
    {!! Form::hidden('cm_id', $cmId) !!}   
    <div class="modal-footer">
        <button type="submit" class="btn green" id="generate">@lang('label.GENERATE')</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>

    {!! Form::close() !!}  
</div>

<style>
    .borderless td, .borderless th {
        border: none;
    }    
</style>
@if(!$cmList->isEmpty())
<script>
    $(document).ready(function () {
        $('#cmListTable2').DataTable();
    });
</script>
@endif