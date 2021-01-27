<div class="row">
    <div class="col-md-12 mb-10">
        @php 
        $colorClass = ['label-green-seagreen', 'label-blue-steel', 'label-purple', 'label-red-soft', 'label-yellow-mint','label-purple-sharp','label-blue-soft'];
        
        $i = 0;
        @endphp
        @if(!empty($markingSheetInfo))
        <?php
        if(sizeof($markingSheetInfo) < 7 ){
           $colorClass[5] = 'label-blue-soft'; 
        }
        ?>
        @foreach($markingSheetInfo as $info)
        
        <span class="label label-sm {{ $colorClass[$i] }}">
            {!! !empty($info) ? $info : '' !!} 
        </span> &nbsp;
        @php $i++ @endphp
        @endforeach
        @endif
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
                <th class="vcenter" width="50">@lang('label.PHOTO')</th>
                <th class="vcenter" width="100">@lang('label.POSITION')</th>
            </tr>
        </thead>
        <tbody>
            @php $sl = 1; @endphp 
            @forelse($cmListData as $cm) 
            <tr>
                <td class="vcenter text-center">{{ $sl++ }}</td>
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
                <td class="vcenter"><input name="position" type="text" value="{{ $cmIdAndPositonArr[$cm->cm_id] }}" class="form-control" readonly /></td>
            </tr>
            @empty

            @endforelse
        </tbody>
    </table>
    {{ Form::hidden('cm_id_and_position_arr', json_encode($cmIdAndPositonArr)) }}
    {{ Form::hidden('save_status', null, ['class' => 'save-status']) }}

</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-12 text-center buttonHide">
            <button type="button" data-id='1' class="submit-form btn btn-circle blue-steel">
                <i class="fa fa-file-text-o"></i> @lang('label.SUBMIT')
            </button>

<!--            <button type="button" data-id='2' class="submit-form btn btn-circle green">
                <i class="fa fa-lock"></i> @lang('label.SAVE_AND_LOCK')
            </button>-->
        </div>
    </div>
</div>

<style>
    .borderless td, .borderless th {
        border: none;
    } 
    .custom-padding-3-10 td{
        padding:3px 10px !important;
    }
</style>