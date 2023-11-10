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
  if(calendarEl) {
    let eventFeed = $(calendarEl).attr("data-feed");

    let calendar = new Calendar(calendarEl, {
      initialDate: '2023-09-01',
      schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
      timeZone: 'Europe/Paris',
      locale: frLocale,
      eventSources: [
        {
          url: eventFeed,
          method: 'POST',
        }
      ],
      headerToolbar: {
        left : "",//left: "prev,next today",
        center: "title",
        right: ""//right: "dayGridMonth,timeGridWeek,timeGridDay,Formation"
      },
      initialView: 'Formation',
      views: {
        Formation: {
          type: 'multiMonth',
          duration: { months: 12 }
        } 
      },
      //navLinks: true, // can click day/week names to navigate views
      plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin, multiMonthPlugin, scrollGridPlugin, bootstrapPlugin ],
      height: 'auto',
      weekends: false,
      weekNumbers: true,
    });
  
    calendar.render();
  }
  
  let calendarTtWeekly = document.getElementById("calendar-timetable-weekly");
  if(calendarTtWeekly) {
    let eventFeed = $(calendarTtWeekly).attr("data-feed");

    let calendar = new Calendar(calendarTtWeekly, {
      //initialDate: '2023-09-01',
      schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
      timeZone: 'Europe/Paris',
      slotMinTime: "08:00",
      slotMaxTime: "19:00",
      editable: false,
      locale: frLocale,
      nowIndicator: true,
      eventSources: [
        {
          url: eventFeed,
          method: 'POST',
        }
      ],
      headerToolbar: {
        left : "prev,next today",//left: "prev,next today",
        center: "title",
        right: ""//right: "dayGridMonth,timeGridWeek,timeGridDay,Formation"
      },
      initialView: 'timeGridWeek',
      navLinks: true, // can click day/week names to navigate views
      plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin, multiMonthPlugin, scrollGridPlugin, bootstrapPlugin ],
      height: 'auto',
      weekends: false,
      weekNumbers: true,
      allDaySlot: false
    });
  
    calendar.render();
  }

  let calendarTtWeeklyParams = document.getElementById("calendar-timetable-weekly-params");
  if(calendarTtWeeklyParams) {
    let eventFeed = $(calendarTtWeeklyParams).attr("data-feed");

    let calendar = new Calendar(calendarTtWeeklyParams, {
      //initialDate: '2023-09-01',
      schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
      timeZone: 'Europe/Paris',
      slotMinTime: "08:00",
      slotMaxTime: "19:00",
      editable: true,
      locale: frLocale,
      nowIndicator: true,
      eventSources: [
        {
          url: eventFeed,
          method: 'POST',
        }
      ],
      headerToolbar: {
        left : "prev,next today",//left: "prev,next today",
        center: "title",
        right: ""//right: "dayGridMonth,timeGridWeek,timeGridDay,Formation"
      },
      initialView: 'timeGridWeek',
      navLinks: true, // can click day/week names to navigate views
      plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin, multiMonthPlugin, scrollGridPlugin, bootstrapPlugin ],
      height: 'auto',
      weekends: false,
      weekNumbers: true,
      allDaySlot: false
    });
  
    calendar.render();
  }
  
});