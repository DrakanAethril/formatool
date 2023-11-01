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
});
