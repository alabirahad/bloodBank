$('.delete').on('click', function (e) {
    e.preventDefault();
    var form = $(this).parents('form');
    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this data!",
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

 $(".js-source-states").select2();
 //$(".js-source-states-2").select2();


$(".interger-decimal-only").each(function () {
    $(this).keypress(function (e) {
        var code = e.charCode;

        if (((code >= 48) && (code <= 57)) || code == 0 || code == 46) {
            return true;
        } else {
            return false;
        }
    });
});

