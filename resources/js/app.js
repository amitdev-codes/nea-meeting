import "laravel-datatables-vite";
import { AjaxRequestHandler } from "./ajaxRequestHandler.js";
window.AjaxRequestHandler = AjaxRequestHandler;
import toastr from "toastr";
import './nepalidatePicker.js';
import Chart from 'chart.js/auto';

window.toastr = toastr;
window.toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    preventDuplicates: false,
    showDuration: "300",
    hideDuration: "1000",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
};

window.Chart = Chart;

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.notify-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            if (confirm('Are you sure you want to send notifications for this meeting?')) {
                window.location.href = this.href;
            }
        });
    });
});