@if(!empty($termArr))
<div class="row margin-top-10">
    <div class="col-md-12">
        @foreach($termArr as $courseId => $termData)
        @if(!empty($termData))
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 course-block">
            <h1 class="page-title text-center">@lang('label.COURSE'): {{!empty($courseArr[$courseId]) ? $courseArr[$courseId] : ''}}</h1>
            <div class="row margin-top-60">
                @foreach($termData as $termId => $info)
                <?php
                $class = 'default';
                $label = __('label.NOT_INITIATED');
                $percent = $info['percent'];
                if ($info['status'] == '0') {
                    $class = 'gray-mint';
                    $label = __('label.NOT_INITIATED');
                } else if ($info['status'] == '1') {
                    if ($info['active'] == '0') {
                        $class = 'blue-hoki';
                        $label = __('label.INITIATED');
                    } else if ($info['active'] == '1') {
                        $class = 'green-sharp';
                        $label = __('label.ACTIVE');
                    }
                } else if ($info['status'] == '2') {
                    $class = 'red-haze';
                    $label = __('label.CLOSED');
                }
                ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 tooltips" data-html=true>
                    <div class="dashboard-stat2 term-block term-block-{{$class}}">
                        <div class="display">
                            <div class="number">
                                <h4 class="font-{{$class}} bold">
                                    <span>{{$info['name']}}</span>
                                </h4>
                                <span class="font-blue-oleo bold font-size-11">
                                    {{Helper::formatDate($info['initial_date'])}} - {{Helper::formatDate($info['termination_date'])}}
                                </span>
                            </div>
                        </div>
                        <div class="progress-info">
                            <div class="icon  bold text-right">
                                <i class="icon-pie-chart font-{{$class}} font-size-25"></i>
                            </div>
                            <div class="progress" style="background-color:white;" >
                                <span style="width: {{$percent}}%;" class="progress-bar progress-bar-success {{$class}}  bg-font-blue-oleo">
                                    <span class="sr-only">{{$percent}}% progress</span>
                                </span>
                            </div>
                            <div class="status">
                                <div class="status-title font-{{$class}}">{{$label}}</div>
                                <div class="status-number font-{{$class}}">{{($percent > 100) ? 100 : $percent}}%</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
        
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="portlet light course-participant-block bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.PARTICIPANT_SUMMARY') ({!! !empty($activeTrainingYearInfo) ? $activeTrainingYearInfo->name : '' !!})
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        @if(Auth::user()->group_id == 2)
                        <a class="btn btn-circle btn-default" href="{{ URL::to('studentToSyn') }}"> See All </a>
                        @endif
                        <!--<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>-->
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="activeTrainingYearParticipantSummary" class="min-height-200"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<script type="text/javascript" src="{{asset('public/js/apexcharts.min.js')}}"></script>
<script>
$(function () {
    var activeTrainingYearParticipantSummaryOptions = {
        chart: {
            type: 'bar',
            height: 200,
            toolbar: {
                show: false
            }
        },
        series: [{
                name: "@lang('label.NO_OF_PARTICIPANTS')",
                data: [
<?php
if (!empty($courseArr)) {
    foreach ($courseArr as $courseId => $courseName) {
        $noOfStudent = !empty($courseWiseTotalStudent[$courseId]) ? $courseWiseTotalStudent[$courseId] : 0;
        echo "'$noOfStudent', ";
    }
}
?>
                ]
            }],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '15%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                    position: 'top', // top, center, bottom
                },
            },
        },
        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555'],
        dataLabels: {
            enabled: false,
            enabledOnSeries: undefined,
            formatter: function (val) {
                return val;
            },
            textAnchor: 'middle',
            distributed: true,
            offsetX: 0,
            offsetY: -10,
            style: {
                fontSize: '12px',
                fontFamily: 'Helvetica, Arial, sans-serif',
                fontWeight: 'bold',
                colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
            },
            background: {
                enabled: true,
                foreColor: '#fff',
                padding: 4,
                borderRadius: 2,
                borderWidth: 1,
                borderColor: '#fff',
                opacity: 0.9,
                dropShadow: {
                    enabled: false,
                    top: 1,
                    left: 1,
                    blur: 1,
                    color: '#000',
                    opacity: 0.45
                }
            },
            dropShadow: {
                enabled: false,
                top: 1,
                left: 1,
                blur: 1,
                color: '#000',
                opacity: 0.45
            }
        },
        legend: {
            show: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            labels: {
                show: true,
                rotate: 0,
                rotateAlways: true,
                hideOverlappingLabels: true,
                showDuplicates: false,
                trim: false,
                minHeight: undefined,
                maxHeight: 80,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                    return val;
                },
                format: undefined,
            },
            categories: [
<?php
if (!empty($courseArr)) {
    foreach ($courseArr as $courseId => $courseName) {
        echo "'$courseName', ";
    }
}
?>
            ],
            title: {
                text: "@lang('label.COURSES')",
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: undefined,
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 700,
                    cssClass: 'apexcharts-xaxis-title',
                },
            },
            axisBorder: {
                show: true,
                color: '#78909C',
                height: 1,
                width: '100%',
                offsetX: 0,
                offsetY: 0
            },
            axisTicks: {
                show: true,
                borderType: 'solid',
                color: '#78909C',
                height: 6,
                offsetX: 0,
                offsetY: 0
            },
            tickAmount: undefined,
            tickPlacement: 'between',
            min: undefined,
            max: undefined,
            range: undefined,
            floating: false,
            position: 'bottom',
        },
        yaxis: {
            title: {
                text: "@lang('label.NO_OF_PARTICIPANTS')",
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: undefined,
                    fontSize: '10px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 700,
                    cssClass: 'apexcharts-xaxis-title',
                },
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "horizontal",
                shadeIntensity: 0.20,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 0.85,
                opacityTo: 1.85,
                stops: [85, 50, 100]
            },
        },
        tooltip: {
            y: {
                title: {
                    formatter: function (val) {
                        return  "@lang('label.NO_OF_PARTICIPANTS')";
                    }
                },
                formatter: function (val) {
                    return  val;
                }
            }
        }

    };
    var activeTrainingYearParticipantSummary = new ApexCharts(document.querySelector("#activeTrainingYearParticipantSummary"), activeTrainingYearParticipantSummaryOptions);
    activeTrainingYearParticipantSummary.render();
});

</script>