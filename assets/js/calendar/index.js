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
    let initialDate = $(calendarEl).attr("data-initial-date");
    let monthsDuration = $(calendarEl).attr("data-months-duration");

    let calendar = new Calendar(calendarEl, {
      initialDate: initialDate,
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
          duration: { months: monthsDuration }
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
      initialDate: $(calendarTtWeekly).attr("data-focus"),
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
      eventContent: function(arg) {
        return { html: '<b>'+arg.event.title+'</b><br/>'+
                        '<i>'+arg.event.extendedProps.teacher+' ('+arg.event.extendedProps.classRoom+')</i>'
                };
      },
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
      initialDate: $(calendarTtWeeklyParams).attr("data-focus"),
      schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
      timeZone: 'Europe/Paris',
      slotMinTime: "08:00",
      slotMaxTime: "19:00",
      //editable: true, // disabled here to prevent resizing for length management.
      locale: frLocale,
      nowIndicator: true,
      eventSources: [
        {
          url: eventFeed,
          method: 'POST',
        }
      ],
      eventContent: function(arg) {
        return { html: '<b>'+arg.event.title+'</b><br/>'+
                        '<i>'+arg.event.extendedProps.options+'('+arg.event.extendedProps.topic+')</i><br/>'+
                        '<i>('+arg.event.extendedProps.lessonType+
                        ', salle '+arg.event.extendedProps.classRoom+')</i>' 
                };
      },
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
      allDaySlot: false,
      eventStartEditable : true, 
      eventOverlap: false,
      // Event handling

      eventClick: function(info) {
        //id= calEvent.id;
        //console.log(info.event.id);
      },
      

      selectable: true,
      select: function(info) {
        //alert('selected ' + info.startStr + ' to ' + info.endStr);
        let newEvent = $(calendarTtWeeklyParams).attr("data-new-event");
        //alert(newEvent);
        window.location.href= ''+newEvent+'?start='+info.startStr+'&end='+info.endStr;
      },

      eventDrop: function(info) {
        $.ajax({
          url: (info.event.extendedProps.updateUrl),
          data: ({
              startDate: info.event.start.toISOString(),
              endDate: info.event.end.toISOString()
          }),
          type: "GET",
          success: function (data) {
              alert('ok');
          },
          error: function (xhr, status, error) {
              alert("nok");
          }
        });
        //alert(info.event.title + " was dropped on " + info.event.start.toISOString());
        //alert(info.event.title + " was dropped on " + info.event.end.toISOString());
      }
    });
  
    calendar.render();
  }
  
});