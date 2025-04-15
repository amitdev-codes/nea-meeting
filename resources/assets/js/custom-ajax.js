$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    // Toastr configuration
    // toastr.options = {
    //     beforeHide: function () {
    //         console.log("Toastr is being hidden"); // Check if it's being hidden early
    //     },
    //     closeButton: true,
    //     progressBar: true,
    //     positionClass: "toast-top-right",
    //     timeOut: 50000,
    //     extendedTimeOut: 2000,
    //     showEasing: "swing",
    //     hideEasing: "linear",
    //     showMethod: "fadeIn",
    //     hideMethod: "fadeOut",
    //     showDuration: 500000,
    //     hideDuration: 500000,
    // };
    // toastr.success('Test toastr success message');

    // // Global AJAX form handler
    // $('form').on('submit', function(e) {
    //     e.preventDefault();
    //     let form = $(this);

    //     $.ajax({
    //         url: form.attr('action'),
    //         method: form.attr('method'),
    //         data: form.serialize(),
    //         success: function(response) {

    //             if (response.success) {
    //                 toastr.success(response.message);
    //                 // alert(response.message);

    //                 if (response.redirect) {
    //                     setTimeout(() => {
    //                         window.location.href = response.redirect;
    //                     }, 10000);
    //                 }
    //             }
    //         },
    //         error: function(xhr) {
    //             toastr.error(xhr.responseJSON?.message || 'An error occurred');
    //         }
    //     });
    // });
});
