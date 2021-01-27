@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-calendar"></i>@lang('label.COURSE_ID_LIST')
            </div>
            <div class="actions">
                <a class="btn btn-default btn-sm create-new"
                    href="{{ URL::to('courseId/create'.Helper::queryPageStr($qpArr)) }}">
                    @lang('label.CREATE_NEW_COURSE_ID')
                    <i class="fa fa-plus create-new"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'courseId/filter','class' => 'form-horizontal'))
                !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="filSearch">@lang('label.SEARCH')</label>
                            <div class="col-md-8">
                                {!! Form::text('fil_search', Request::get('fil_search'), ['class' => 'form-control
                                tooltips', 'id' => 'filSearch', 'title' => 'Name', 'placeholder' => 'Name',
                                'list' => 'courseIdName', 'autocomplete' => 'off']) !!}
                                <datalist id="courseIdName">
                                    @if (!$nameArr->isEmpty())
                                    @foreach($nameArr as $item)
                                    <option value="{{$item->name}}" />
                                    @endforeach
                                    @endif
                                </datalist>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                <i class="fa fa-search"></i> @lang('label.FILTER')
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
                <!-- End Filter -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.TRAINING_YEAR')</th>
                            <th class="vcenter">@lang('label.COURSE_ID')</th>
                            <th class="text-center vcenter">@lang('label.TENURE')</th>
                            <th class="text-center vcenter">@lang('label.NO_OF_WEEKS')</th>
                            <th class="text-center vcenter">@lang('label.SHORT_INFO')</th>
                            <th class="text-center vcenter">@lang('label.TOTAL_COURSE_WT')</th>
                            <th class="text-center vcenter">@lang('label.EVENT_MKS_LIMIT')</th>
                            <th class="text-center vcenter">@lang('label.HIGHEST_MKS_LIMIT')</th>
                            <th class="text-center vcenter">@lang('label.LOWEST_MKS_LIMIT')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class="td-actions text-center vcenter">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="text-center vcenter">{{ ++$sl }}</td>
                            <td class="vcenter">{{ $target->tranining_year_name }}</td>
                            <td class="vcenter">{{ $target->name }}</td>
                            <td class="text-center vcenter">
                                {{ Helper::printDate($target->initial_date) .' '. __('label.TO') .' '.  Helper::printDate($target->termination_date) }}
                            </td>
                            <td class="text-center vcenter">{{ $target->no_of_weeks }}</td>
                            <td class="text-center vcenter">{{ $target->short_info }}</td>
                            <td class="text-center vcenter">{{ $target->total_course_wt }}</td>
                            <td class="text-center vcenter">{{ $target->event_mks_limit }}</td>
                            <td class="text-center vcenter">{{ $target->highest_mks_limit }}</td>
                            <td class="text-center vcenter">{{ $target->lowest_mks_limit }}</td>
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @elseif($target->status == '0')
                                <span class="label label-sm label-info">@lang('label.INACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.CLOSED')</span>
                                @endif
                            </td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if ($target->status !='2' )
                                    {{ Form::open(array('url' => 'courseId/' . $target->id.'/'.Helper::queryPageStr($qpArr))) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <a class="btn btn-xs btn-primary tooltips" title="Edit"
                                        href="{{ URL::to('courseId/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-xs btn-danger delete tooltips" title="Delete"
                                        type="submit" data-placement="top" data-rel="tooltip"
                                        data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                    @if ($target->status =='1')

<!--                                    <button class="btn btn-xs btn-warning close-btn tooltips"
                                        title="@lang('label.CLOSE_THIS_COURSE')" type=" button" data-placement="top"
                                        data-rel="tooltip" data-id="{!! $target->id !!}"
                                        data-original-title="@lang('label.CLOSE_THIS_COURSE')">
                                        <i class="fa fa-close"></i>
                                    </button>-->
                                    @endif
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="vcenter">@lang('label.NO_COURSE_ID_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
    @include('layouts.paginator')
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function () {
        $(document).on('click', '.close-btn', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            swal({
                title: 'Are you sure,You want to Close?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Close',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{URL::to('courseId/close')}}",
                        type: "POST",
                        datatype: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: id,
                        },

                        success: function (res) {
                            toastr.success(res.message, 'Success', options);
                            //setTimeout(location.reload.bind(location), 1000);
                        },

                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                               //toastr.error(jqXhr.responseJSON.message, '', options);
                                var errors = jqXhr.responseJSON.message;
                                var errorsHtml = 'SI Impr Mks have not been Locked for following Wing :';
                                if (typeof (errors) === 'object') {
                                    $.each(errors, function (key, value) {
                                        errorsHtml += '<li>' + value + '</li>';
                                    });
                                    toastr.error(errorsHtml, '', options);
                                } else {
                                    toastr.error(jqXhr.responseJSON.message, '', options);
                                }
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            App.unblockUI();
                        }
                    });
                }
            });
        });
    });
</script>









































































@stop