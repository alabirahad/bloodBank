 
<div class="row margin-top-10 margin-bottom-10 selected-cm-list">
    @if(!$targetArr->isEmpty())
    <div class="col-md-12">

        <div class="table-responsive max-height-250 webkit-scrollbar">
            <table class="table table-bordered table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th class="vcenter text-center">@lang('label.SL')</th>
                        <th class="vcenter text-center">@lang('label.PHOTO')</th>
                        <th class="vcenter">@lang('label.NAME')</th>
                        <th class="vcenter text-center">@lang('label.CLOSE')</th>
                    </tr>
                </thead>
                <tbody id="selected-cm-body">
                    <?php $cmSl = 0; ?>
                    @foreach($targetArr as $target)

                    <tr>
                        {{ Form::hidden('selected_cm['.$target->id.']', $target->id, array('id' =>  $target->id, 'class' => 'selected-cm')) }}
                        <td class="vcenter text-center initial-serial-cm">{!! ++$cmSl !!}</td>
                        <td class="text-center vcenter" width="50px">
                            <?php if (!empty($target->photo && File::exists('public/uploads/cm/' . $target->photo))) { ?>
                                <img width="50" height="60" src="{{URL::to('/')}}/public/uploads/cm/{{$target->photo}}" alt="{{ $target->full_name}}"/>
                            <?php } else { ?>
                                <img width="50" height="60" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $target->full_name}}"/>
                            <?php } ?>
                        </td>
                        <td class="vcenter">{{$target->cm_name}}</td>
                        <td class="text-center"> 
                            <button class="btn btn-danger remove-selected-cm tooltips" type="button" data-id="{!! $target->id !!}" title="@lang('label.REMOVE')">×</button>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>
    @else
    <div class="col-md-12">
        <div class="alert alert-danger alert-dismissable">
            <p><strong><i class="fa fa-bell-o fa-fw"></i> {!! __('label.NO_CM_IS_SELECTED_FOR_THIS_MARKING_GROUP_YET') !!}</strong></p>
        </div>
    </div>
    @endif
</div>

<!-- Modal end-->
<script type='text/javascript'></script>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>

