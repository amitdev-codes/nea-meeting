import "laravel-datatables-vite";
import { AjaxRequestHandler } from "./ajaxRequestHandler.js";
window.AjaxRequestHandler = AjaxRequestHandler;
import toastr from "toastr";
import { initLocationDropdowns } from "@/utils/locationDropdowns.js";
import { initFansepDropdowns } from "@/utils/fansepDropdowns.js";
import { initWardDropdowns } from "@/utils/wardDropdowns.js";
import { initFormDropdowns } from "@/utils/formDropdowns.js";
import { initFormEntriesDropdowns } from "@/utils/formEntriesDropdowns.js";
import { initGroupByFormDropdowns } from "@/utils/groupByFormDropdown.js";

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


window.initLocationDropdowns = initLocationDropdowns;
window.initFansepDropdowns = initFansepDropdowns;
window.initWardDropdowns = initWardDropdowns;
window.initFormDropdowns = initFormDropdowns;
window.initFormEntriesDropdowns = initFormEntriesDropdowns;
window.initGroupByFormDropdowns = initGroupByFormDropdowns;
window.Chart = Chart;