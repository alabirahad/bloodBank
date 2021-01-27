   
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <tbody>
            <tr>
                <td colspan="6" style="text-align:center; font-size: 24px"><strong>@lang('label.MUTUAL_ASSESSSMENT')</strong></td>
            </tr>
            <tr style="background-color:#000000">
                <td>@lang('label.TOTAL_NUMBER_OF_CM') :  <strong>{{ !$cmList->isEmpty() ? sizeof($cmList) : 0 }}</strong></td>
                <td>@lang('label.COURSE') : <strong>{{ $courseName }}</strong></td>
                <td>@lang('label.TERM') : <strong>{{ $term->name }}</strong></td>
                <td> @lang('label.EVENT') : <strong>{{ $eventName->name }}</strong></td>
                <td>@lang('label.SYNDICATE') : <strong>{{ $syndicate->name }}</strong></td>
                @if(!empty($subSyndicate->name))
                <td width="50">@lang('label.SUB_SYNDICATE') : <strong>{{ !empty($subSyndicate->name) ? $subSyndicate->name : '' }}</strong></td>
                @endif
                <td>@lang('label.MARKING_CM') : <strong>{{ $cmName->full_name }}</strong></td>
            </tr>
            
            <tr></tr>
            <tr>
                <td ><strong>@lang('label.SL')</strong></td>
                <td ><strong>@lang('label.PERSONAL_NO')</strong></td>
                <td ><strong>@lang('label.RANK')</strong></td>
                <td ><strong>@lang('label.NAME')</strong></td>
                <td ><strong>@lang('label.PHOTO')</strong></td>
                <td ><strong>@lang('label.POSITION')</strong></td>
            </tr>
           @php $sl = 1; @endphp 
           @foreach($cmList as $cm) 
            <tr>
                <td ><strong>{{ $sl++ }}</strong></td>
                <td >{{ $cm->personal_no }}</td>
                <td >{{ $cm->rank }}</td>
                <td >{{ $cm->full_name }}</td>
                <td class="vcenter">
                    @if(!empty($cm->photo) && File::exists('public/uploads/cm/' . $cm->photo))
                    <img width="50" height="60" src="public/uploads/cm/{{$cm->photo}}" alt="{{$cm['full_name'] ?? ''}}"/>
                    @else
                    <img width="50" height="60" src="public/img/unknown.png" alt="{{$cm['full_name'] ?? ''}}"/>
                    @endif
                </td>
                <td ></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
.borderless td, .borderless th {
    border: none;
}    
</style>