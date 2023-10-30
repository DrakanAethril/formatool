import { Calendar } from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import multiMonthPlugin from "@fullcalendar/multimonth";
import scrollGridPlugin from "@fullcalendar/scrollgrid";
import bootstrapPlugin from "@fullcalendar/bootstrap";
//import momentPlugin from "@fullcalendar/moment";
//import momentTimezonePlugin from "@fullcalendar/moment-timezone";

import frLocale from '@fullcalendar/core/locales/fr';

import "./index.css"; // this will create a calendar.css file reachable to 'encore_entry_link_tags'

document.addEventListener("DOMContentLoaded", () => {
  let calendarEl = document.getElementById("calendar-holder");

  let { eventsUrl } = calendarEl.dataset;

  let calendar = new Calendar(calendarEl, {
    schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
    timeZone: 'Europe/Paris',
    locale: frLocale,
    editable: true,
    eventSources: [
      {
        url: eventsUrl,
        method: "POST",
        extraParams: {
          filters: JSON.stringify({}) // pass your parameters to the subscriber
        },
        failure: () => {
          // alert("There was an error while fetching FullCalendar!");
        },
      },
    ],
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay"
    },
    initialView: "dayGridMonth",
    navLinks: true, // can click day/week names to navigate views
    plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin, multiMonthPlugin, scrollGridPlugin, bootstrapPlugin ],
    height: 'auto',
    weekends: false,
    weekNumbers: true,
    businessHours: [
        {
            // days of week. an array of zero-based day of week integers (0=Sunday)
            daysOfWeek: [ 1, 2, 3, 4, 5], // Monday - Friday
        
            startTime: '08:00', // a start time (10am in this example)
            endTime: '12:00', // an end time (6pm in this example)
        },
        {
            // days of week. an array of zero-based day of week integers (0=Sunday)
            daysOfWeek: [ 1, 2, 3, 4, 5], // Monday - Friday
        
            startTime: '13:30', // a start time (10am in this example)
            endTime: '17:30', // an end time (6pm in this example)
        }
    ]
  });

  calendar.render();
});