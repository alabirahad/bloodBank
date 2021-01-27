<div class="page-bar">
    <ul class="page-breadcrumb margin-top-10">
        <li>
            <a href="{{url('dashboard')}}">@lang('label.HOME')</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>@lang('label.DASHBOARD')</span>
        </li>
    </ul>
    <div class="page-toolbar margin-top-15">
        <h5 class="dashboard-date font-green-sharp"><span class="icon-calendar"></span> @lang('label.TODAY_IS') <span class="font-green-sharp">{!! date('d F Y') !!}</span> </h5>   
    </div>
</div>
