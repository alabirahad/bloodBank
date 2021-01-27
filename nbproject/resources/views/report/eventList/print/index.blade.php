<?php
$basePath = URL::to('/');
?>
@if (Request::get('view') == 'pdf' || Request::get('view') == 'print') 
<?php
if (Request::get('view') == 'pdf') {
    $basePath = base_path();
}
?>
<html>
    <head>
        <title>@lang('label.SINT_AMS_TITLE')</title>
        <link rel="shortcut icon" href="{{$basePath}}/public/img/favicon_sint.png" />
        <meta charset="UTF-8">
        <link href="{{asset('public/fonts/css.css?family=Open Sans')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/jqvmap/jqvmap/jqvmap.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />


        <!--BEGIN THEME LAYOUT STYLES--> 
        <!--<link href="{{asset('public/assets/layouts/layout/css/layout.min.css')}}" rel="stylesheet" type="text/css" />-->
        <link href="{{asset('public/assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 

        <style type="text/css" media="print">
            /*@page { size: landscape; }*/
            * {
                -webkit-print-color-adjust: exact !important; 
                color-adjust: exact !important; 
            }
        </style>

        <script src="{{asset('public/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
    </head>
    <body>
        <div class="portlet-body">
            <div class="row text-center">
                <div class="col-md-12 text-center">
                    <img width="500" height="auto" src="{{$basePath}}/public/img/sint_ams_logo.jpg" alt=""/>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <div class="text-center bold uppercase">
                        <span class="header">@lang('label.EVENT_MKS_WT')</span>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.TRAINING_YEAR')}} : <strong>{{ !empty($activeTrainingYearInfo[Request::get('training_year_id')]) && Request::get('training_year_id') != 0 ? $activeTrainingYearInfo[Request::get('training_year_id')] : __('label.N_A') }} |</strong>
                            {{__('label.COURSE')}} : <strong>{{ !empty($courseList[Request::get('course_id')]) && Request::get('course_id') != 0 ? $courseList[Request::get('course_id')] : __('label.N_A') }} |</strong>
                            {{__('label.TERM')}} : <strong>{{ !empty($termList[Request::get('term_id')]) && Request::get('term_id') != 0 ? $termList[Request::get('term_id')] : __('label.N_A') }} </strong>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-head-fixer-color" id="dataTable">
                        <thead>
                            <tr>
                                <th class="vcenter text-center">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.EVENT')</th>
                                <th class="vcenter">@lang('label.SUB_EVENT')</th>
                                <th class="vcenter">@lang('label.SUB_SUB_EVENT')</th>
                                <th class="vcenter">@lang('label.SUB_SUB_SUB_EVENT')</th>
                                <th class="vcenter text-center">@lang('label.MKS_LIMIT')</th>
                                <th class="vcenter text-center">@lang('label.HIGHEST_MKS_LIMIT')</th>
                                <th class="vcenter text-center">@lang('label.LOWEST_MKS_LIMIT')</th>
                                <th class="vcenter text-center">@lang('label.WT')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($eventMksWtArr['mks_wt']))
                            <?php $sl = 0; ?>
                            @foreach($eventMksWtArr['mks_wt'] as $eventId => $evInfo)
                            <tr>
                                <td class="vcenter text-center" rowspan="{!! !empty($rowSpanArr['event'][$eventId]) ? $rowSpanArr['event'][$eventId] : 1 !!}">{!! ++$sl !!}</td>
                                <td class="vcenter"  rowspan="{!! !empty($rowSpanArr['event'][$eventId]) ? $rowSpanArr['event'][$eventId] : 1 !!}">
                                    {!! !empty($eventMksWtArr['event'][$eventId]['name']) ? $eventMksWtArr['event'][$eventId]['name'] : '' !!}
                                </td>

                                @if(!empty($evInfo))
                                <?php $i = 0; ?>
                                @foreach($evInfo as $subEventId => $subEvInfo)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter"  rowspan="{!! !empty($rowSpanArr['sub_event'][$eventId][$subEventId]) ? $rowSpanArr['sub_event'][$eventId][$subEventId] : 1 !!}">
                                    {!! !empty($eventMksWtArr['event'][$eventId][$subEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId]['name'] : '' !!}
                                </td>

                                @if(!empty($subEvInfo))
                                <?php $j = 0; ?>
                                @foreach($subEvInfo as $subSubEventId => $subSubEvInfo)
                                <?php
                                if ($j > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter"  rowspan="{!! !empty($rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId]) ? $rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] : 1 !!}">
                                    {!! !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]['name'] : '' !!}
                                </td>

                                @if(!empty($subSubEvInfo))
                                <?php $k = 0; ?>
                                @foreach($subSubEvInfo as $subSubSubEventId => $subSubSubEvInfo)
                                <?php
                                if ($k > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter">
                                    {!! !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['name'] : '' !!}
                                </td>
                                <?php
                                $eventMkslimit = !empty($subSubSubEvInfo['mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['mks_limit']) : '--';
                                $eventHighestMkslimit = !empty($subSubSubEvInfo['highest_mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['highest_mks_limit']) : '--';
                                $eventLowestMkslimit = !empty($subSubSubEvInfo['lowest_mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['lowest_mks_limit']) : '--';
                                $eventWt = !empty($subSubSubEvInfo['wt']) ? Helper::numberFormat2Digit($subSubSubEvInfo['wt']) : '--';

                                $eventMkslimitTextAlign = !empty($subSubSubEvInfo['mks_limit']) ? 'right' : 'center';
                                $eventHighestMkslimitTextAlign = !empty($subSubSubEvInfo['highest_mks_limit']) ? 'right' : 'center';
                                $eventLowestMkslimitTextAlign = !empty($subSubSubEvInfo['lowest_mks_limit']) ? 'right' : 'center';
                                $eventWtTextAlign = !empty($subSubSubEvInfo['wt']) ? 'right' : 'center';
                                ?>
                                <td class="text-{{$eventMkslimitTextAlign}} width-80">
                                    <span class="width-inherit bold">{!! $eventMkslimit !!}</span>
                                </td>
                                <td class="text-{{$eventHighestMkslimitTextAlign}} width-80">
                                    <span class="width-inherit bold">{!! $eventHighestMkslimit !!}</span>
                                </td>
                                <td class="text-{{$eventLowestMkslimitTextAlign}} width-80">
                                    <span class="width-inherit bold">{!! $eventLowestMkslimit !!}</span>
                                </td>
                                <td class="text-{{$eventWtTextAlign}} width-80">
                                    <span class="width-inherit bold">{!! $eventWt !!}</span>
                                </td>

                                <?php
                                if ($i < ($rowSpanArr['event'][$eventId] - 1)) {
                                    if ($j < ($rowSpanArr['sub_event'][$eventId][$subEventId] - 1)) {
                                        if ($k < ($rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] - 1)) {
                                            echo '</tr>';
                                        }
                                    }
                                }
                                $k++;
                                ?>
                                @endforeach
                                @endif

                                <?php
                                $j++;
                                ?>
                                @endforeach
                                @endif

                                <?php
                                $i++;
                                ?>
                                @endforeach
                                @endif
                            </tr>
                            @endforeach
                            <tr>
                                <td class="text-right bold" colspan="5"> @lang('label.TOTAL') </td>
                                <td class="text-right width-80">
                                    <span class="width-inherit bold">{!! !empty($eventMksWtArr['total_mks_limit']) ? Helper::numberFormat2Digit($eventMksWtArr['total_mks_limit']) : '0.00' !!}</span>
                                </td>
                                <td colspan="2"></td>
                                <td class="text-right width-80">
                                    <span class="width-inherit bold">{!! !empty($eventMksWtArr['total_wt']) ? Helper::numberFormat2Digit($eventMksWtArr['total_wt']) : '0.00' !!}</span>
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="9">@lang('label.NO_EVENT_IS_ASSIGNED_TO_THIS_TERM')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--footer-->
        <table class="no-border margin-top-10">
            <tr>
                <td class="no-border text-left">
                    @lang('label.GENERATED_ON') {!! '<strong>'.Helper::formatDate(date('Y-m-d H:i:s')).'</strong> by <strong>'.Auth::user()->full_name.'</strong>' !!}.
                </td>
                <td class="no-border text-right">
                    <strong>@lang('label.GENERATED_FROM_SINT')</strong>
                </td>
            </tr>
        </table>


        <!--//end of footer-->
        <script src="{{asset('public/assets/global/plugins/bootstrap/js/bootstrap.min.js')}}"  type="text/javascript"></script>
        <script src="{{asset('public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->


        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{asset('public/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->

        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!--<script src="{{asset('public/assets/layouts/layout/scripts/layout.min.js')}}" type="text/javascript"></script>-->



        <!--<script src="{{asset('public/js/apexcharts.min.js')}}" type="text/javascript"></script>-->


        <script>
document.addEventListener("DOMContentLoaded", function (event) {
    window.print();
});
        </script>

    </body>
</html>
@else
<html>
    <head>
        <link href="{{asset('public/fonts/css.css?family=Open Sans')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/jqvmap/jqvmap/jqvmap.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
        <!--BEGIN THEME LAYOUT STYLES--> 
        <!--<link href="{{asset('public/assets/layouts/layout/css/layout.min.css')}}" rel="stylesheet" type="text/css" />-->
        <link href="{{asset('public/assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 
    </head>
    <body>
        <table class="no-border margin-top-10">
            <tr>
                <td class="" colspan="9">
                    <img width="500" height="auto" src="public/img/sint_ams_logo.jpg" alt=""/>
                </td>
            </tr>
            <tr>
                <td class="no-border text-center" colspan="9">
                    <strong>{!!__('label.EVENT_MKS_WT')!!}</strong>
                </td>
            </tr>
        </table>
        <table class="no-border margin-top-10">
            <tr>
                <td class="" colspan="5">
                    <h5 style="padding: 10px;">
                        {{__('label.TRAINING_YEAR')}} : <strong>{{ !empty($activeTrainingYearInfo[Request::get('training_year_id')]) && Request::get('training_year_id') != 0 ? $activeTrainingYearInfo[Request::get('training_year_id')] : __('label.N_A') }} |</strong>
                        {{__('label.COURSE')}} : <strong>{{ !empty($courseList[Request::get('course_id')]) && Request::get('course_id') != 0 ? $courseList[Request::get('course_id')] : __('label.N_A') }} |</strong>
                        {{__('label.TERM')}} : <strong>{{ !empty($termList[Request::get('term_id')]) && Request::get('term_id') != 0 ? $termList[Request::get('term_id')] : __('label.N_A') }} </strong>
                    </h5>
                </td>
            </tr>
        </table>
        <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th class="vcenter text-center">@lang('label.SL_NO')</th>
                    <th class="vcenter">@lang('label.EVENT')</th>
                    <th class="vcenter">@lang('label.SUB_EVENT')</th>
                    <th class="vcenter">@lang('label.SUB_SUB_EVENT')</th>
                    <th class="vcenter">@lang('label.SUB_SUB_SUB_EVENT')</th>
                    <th class="vcenter text-center">@lang('label.MKS_LIMIT')</th>
                    <th class="vcenter text-center">@lang('label.HIGHEST_MKS_LIMIT')</th>
                    <th class="vcenter text-center">@lang('label.LOWEST_MKS_LIMIT')</th>
                    <th class="vcenter text-center">@lang('label.WT')</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($eventMksWtArr['mks_wt']))
                <?php $sl = 0; ?>
                @foreach($eventMksWtArr['mks_wt'] as $eventId => $evInfo)
                <tr>
                    <td class="vcenter text-center" rowspan="{!! !empty($rowSpanArr['event'][$eventId]) ? $rowSpanArr['event'][$eventId] : 1 !!}">{!! ++$sl !!}</td>
                    <td class="vcenter"  rowspan="{!! !empty($rowSpanArr['event'][$eventId]) ? $rowSpanArr['event'][$eventId] : 1 !!}">
                        {!! !empty($eventMksWtArr['event'][$eventId]['name']) ? $eventMksWtArr['event'][$eventId]['name'] : '' !!}
                    </td>

                    @if(!empty($evInfo))
                    <?php $i = 0; ?>
                    @foreach($evInfo as $subEventId => $subEvInfo)
                    <?php
                    if ($i > 0) {
                        echo '<tr>';
                    }
                    ?>
                    <td class="vcenter"  rowspan="{!! !empty($rowSpanArr['sub_event'][$eventId][$subEventId]) ? $rowSpanArr['sub_event'][$eventId][$subEventId] : 1 !!}">
                        {!! !empty($eventMksWtArr['event'][$eventId][$subEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId]['name'] : '' !!}
                    </td>

                    @if(!empty($subEvInfo))
                    <?php $j = 0; ?>
                    @foreach($subEvInfo as $subSubEventId => $subSubEvInfo)
                    <?php
                    if ($j > 0) {
                        echo '<tr>';
                    }
                    ?>
                    <td class="vcenter"  rowspan="{!! !empty($rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId]) ? $rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] : 1 !!}">
                        {!! !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]['name'] : '' !!}
                    </td>

                    @if(!empty($subSubEvInfo))
                    <?php $k = 0; ?>
                    @foreach($subSubEvInfo as $subSubSubEventId => $subSubSubEvInfo)
                    <?php
                    if ($k > 0) {
                        echo '<tr>';
                    }
                    ?>
                    <td class="vcenter">
                        {!! !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['name'] : '' !!}
                    </td>
                    <?php
                    $eventMkslimit = !empty($subSubSubEvInfo['mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['mks_limit']) : '--';
                    $eventHighestMkslimit = !empty($subSubSubEvInfo['highest_mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['highest_mks_limit']) : '--';
                    $eventLowestMkslimit = !empty($subSubSubEvInfo['lowest_mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['lowest_mks_limit']) : '--';
                    $eventWt = !empty($subSubSubEvInfo['wt']) ? Helper::numberFormat2Digit($subSubSubEvInfo['wt']) : '--';

                    $eventMkslimitTextAlign = !empty($subSubSubEvInfo['mks_limit']) ? 'right' : 'center';
                    $eventHighestMkslimitTextAlign = !empty($subSubSubEvInfo['highest_mks_limit']) ? 'right' : 'center';
                    $eventLowestMkslimitTextAlign = !empty($subSubSubEvInfo['lowest_mks_limit']) ? 'right' : 'center';
                    $eventWtTextAlign = !empty($subSubSubEvInfo['wt']) ? 'right' : 'center';
                    ?>
                    <td class="text-{{$eventMkslimitTextAlign}} width-80">
                        <span class="width-inherit bold">{!! $eventMkslimit !!}</span>
                    </td>
                    <td class="text-{{$eventHighestMkslimitTextAlign}} width-80">
                        <span class="width-inherit bold">{!! $eventHighestMkslimit !!}</span>
                    </td>
                    <td class="text-{{$eventLowestMkslimitTextAlign}} width-80">
                        <span class="width-inherit bold">{!! $eventLowestMkslimit !!}</span>
                    </td>
                    <td class="text-{{$eventWtTextAlign}} width-80">
                        <span class="width-inherit bold">{!! $eventWt !!}</span>
                    </td>

                    <?php
                    if ($i < ($rowSpanArr['event'][$eventId] - 1)) {
                        if ($j < ($rowSpanArr['sub_event'][$eventId][$subEventId] - 1)) {
                            if ($k < ($rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] - 1)) {
                                echo '</tr>';
                            }
                        }
                    }
                    $k++;
                    ?>
                    @endforeach
                    @endif

                    <?php
                    $j++;
                    ?>
                    @endforeach
                    @endif

                    <?php
                    $i++;
                    ?>
                    @endforeach
                    @endif
                </tr>
                @endforeach
                <tr>
                    <td class="text-right bold" colspan="5"> @lang('label.TOTAL') </td>
                    <td class="text-right width-80">
                        <span class="width-inherit bold">{!! !empty($eventMksWtArr['total_mks_limit']) ? Helper::numberFormat2Digit($eventMksWtArr['total_mks_limit']) : '0.00' !!}</span>
                    </td>
                    <td colspan="2"></td>
                    <td class="text-right width-80">
                        <span class="width-inherit bold">{!! !empty($eventMksWtArr['total_wt']) ? Helper::numberFormat2Digit($eventMksWtArr['total_wt']) : '0.00' !!}</span>
                    </td>
                </tr>
                @else
                <tr>
                    <td colspan="9">@lang('label.NO_EVENT_IS_ASSIGNED_TO_THIS_TERM')</td>
                </tr>
                @endif
            </tbody>
        </table>
        <!--footer-->
        <table class="no-border margin-top-10">
            <tr>
                <td class="no-border text-left" colspan="5">
                    @lang('label.GENERATED_ON') {!! '<strong>'.Helper::formatDate(date('Y-m-d H:i:s')).'</strong> by <strong>'.Auth::user()->full_name.'</strong>' !!}.
                </td>
                <td class="no-border text-right">
                    <strong>@lang('label.GENERATED_FROM_SINT')</strong>
                </td>
            </tr>
        </table>
    </body>
</html>
@endif