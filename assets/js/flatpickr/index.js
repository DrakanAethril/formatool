import flatpickr from "flatpickr";
import { French } from "flatpickr/dist/l10n/fr.js"
require("flatpickr/dist/themes/dark.css");


flatpickr.localize(French);

import "./index.css";

document.addEventListener("DOMContentLoaded", () => {
    flatpickr(".timeslot-flatpickr", {
        enableTime: true,
        dateFormat: "d-m-Y H:i",
        time_24hr : true
    });

    flatpickr(".startTrainingDate-flatpickr", {
        enableTime: true,
        dateFormat: "d-m-Y H:i",
        time_24hr : true
    });

    flatpickr(".endTrainingDate-flatpickr", {
        enableTime: true,
        dateFormat: "d-m-Y H:i",
        time_24hr : true
    });

    flatpickr(".lessonsession-day-flatpickr", {
        enableTime: true,
        dateFormat: "d-m-Y",
        time_24hr : true,
        enableTime: false 
    });

    flatpickr(".lessonsession-hour-flatpickr", {
        enableTime: true,
        dateFormat: "H:i",
        time_24hr : true,
        minuteIncrement: 30,
        defaultHour: 8,
        defaultMinute: 30,
        //minTime: 8,
        //maxTime: 16,
        noCalendar: true
    });

    flatpickr(".startSignatureExportDate-flatpickr", {
        enableTime: true,
        dateFormat: "d-m-Y",
        enableTime: false
        //time_24hr : true
    });
    flatpickr(".endSignatureExportDate-flatpickr", {
        enableTime: true,
        dateFormat: "d-m-Y",
        enableTime: false
        //time_24hr : false
    });
    flatpickr(".startInvoicingExportDate-flatpickr", {
        enableTime: true,
        dateFormat: "d-m-Y",
        enableTime: false
        //time_24hr : true
    });
    flatpickr(".endInvoicingExportDate-flatpickr", {
        enableTime: true,
        dateFormat: "d-m-Y",
        enableTime: false
        //time_24hr : false
    });
    flatpickr(".exportReportDay-flatpickr", {
        enableTime: true,
        dateFormat: "d-m-Y",
        enableTime: false
        //time_24hr : false
    });
});
