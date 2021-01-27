$(document).ready(function () {
    $('.delete').on('click', function (e) {
        e.preventDefault();
        var form = $(this).parents('form');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm)
                form.submit();
        });
    });

    $(".js-source-states").select2({width: '100%'});

    $(".integer-decimal-only").each(function () {
        $(this).keypress(function (e) {
            var code = e.charCode;

            if (((code >= 48) && (code <= 57)) || code == 0 || code == 46) {
                return true;
            } else {
                return false;
            }
        });
    });


    $(".integer-only").each(function () {
        $(this).keypress(function (e) {
            var code = e.charCode;

            if (((code >= 48) && (code <= 57)) || code == 0) {
                return true;
            } else {
                return false;
            }
        });
    });
    
    $('body').on('click', function (evt) {
        if ($(evt.target).is('.modal')) {
            $(".modal").modal("hide");
        }
    });

    $('button.reset-date').click(function () {
        var remove = $(this).attr('remove');
        $('#' + remove).val('');
    });


    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
    });


    $('.month-date-picker').datepicker({
        format: "yyyy-mm",
        viewMode: "months",
        minViewMode: "months",
        todayHighlight: true,
    });


    $('.current-date-picker').datepicker({
        format: 'yyyy-mm-dd',
        minDate: '0',
    });


    $('.datepicker2').datepicker({
        format: 'dd MM yyyy',
        autoclose: true,
        todayHighlight: true,
    });
});

$(function () {
    $('.marquee').marquee({
        //speed in milliseconds of the marquee
        duration: 20000,
        //gap in pixels between the tickers

        //time in milliseconds before the marquee will start animating
        delayBeforeStart: 0,
        //'left' or 'right'
        direction: 'left',
        //true or false - should the marquee be duplicated to show an effect of continues flow
        duplicated: false,
        pauseOnHover: true
    });
});