<div class="row">
    <div class="col-md-12 mb-10">
        <span class="label label-sm label-green-seagreen">
            @lang('label.TOTAL_NUMBER_OF_CM') : <strong>{{ !$cmList->isEmpty() ? $cmList->count() : 0 }}</strong>
        </span>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="vcenter text-center">@lang('label.SL')</th>
                <th class="vcenter">@lang('label.PERSONAL_NO')</th>
                <th class="vcenter">@lang('label.RANK')</th>
                <th class="vcenter">@lang('label.NAME')</th>
                <th class="vcenter">@lang('label.PHOTO')</th>
                <th class="vcenter" width="100">@lang('label.POSITION')</th>
            </tr>
        </thead>
        <tbody>
            @php $sl = 1; @endphp 
            @if(!$cmList->isEmpty())
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
                <td class="vcenter"><input name="position" value="{{ !empty($cm->position)? $cm->position : '' }}" type="text" class="form-control" readonly /></td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="6" class="vcenter"><strong>@lang('label.CM_NOT_AVAILABLE')</strong></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>


<style>
    .borderless td, .borderless th {
        border: none;
    } 
    .custom-padding-3-10 td{
        padding:3px 10px !important;
    }
</style>