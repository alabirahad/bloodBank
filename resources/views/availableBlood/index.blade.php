@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>Available Blood
            </div>
        </div>
        <div class="portlet-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">Blood Group</th>
                            <th class="text-center vcenter">Available</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $sl = 0;
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="text-center vcenter">{{ ++$sl }}</td>
                            <td class="vcenter">{{ $target->name }}</td>
                            <td class="text-center vcenter">{{ $target->available }}(bag)</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="3" class="vcenter">@lang('label.NO_USER_GROUP_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>	
    </div>
</div>
@stop