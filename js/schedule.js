$(document).ready(function() {
  $(".section").mouseleave(function (e) {
    var hoverClass = JSON.parse(localStorage['hoverClass']) || NULL;
    for (var i = 1; i < hoverClass.length; i++) {
      // add to the schedule
      var schedule = $(".calendar > div");
      var curr_time = 8;
      $.each(schedule, function (el) {
        // add the hover attribute
        if (curr_time >= hoverClass[i].start && curr_time <= hoverClass[i].end) {
          if (hoverClass[i].mon) {
            $(this).find(".mon").css("background-color", "white");
          }
          if (hoverClass[i].tues) {
            $(this).find(".tues").css("background-color", "white");
          }
          if (hoverClass[i].wed) {
            $(this).find(".wed").css("background-color", "white");
          }
          if (hoverClass[i].thurs) {
            $(this).find(".thurs").css("background-color", "white");
          }
          if (hoverClass[i].fri) {
            $(this).find(".fri").css("background-color", "white");
          }
        }
        curr_time += 0.5;
      });
    }
  });

  $(".section").mouseenter(function (e) {
    var type = $(this).find(".course").text();
    if (type.search(/\*DE/g) > 0) {} // distance education, no class time
    else {
      var times = $(this).find(".times").text();
      var exam = times.search(/EXAM/g) > 0 ? 1 : 0;
      var timeslots = times.split(/LEC|SEM|LAB|EXAM/g);

      var hoverClass = new Array();

      for (var i = 1; i < timeslots.length-exam; i++) {
        hoverClass[i] = new Object();

        // get the time
        var split_time = timeslots[i].split(/-/);
        var patt = new RegExp("[0-9]{2}:[0-9]{2}[AP]M");
        var time_t = patt.exec(split_time[0]);
        var time_e = patt.exec(split_time[1]);

        // convert start time to 24 hr
        var hour_t = time_t[0].substring(0,2) / 1;
        var min_t = time_t[0].substring(3,5) / 60;
        var offset_t = time_t[0].search(/PM/) > 0 && hour_t != 12 ? 12 : 0;
        var clock_t  = hour_t + min_t + offset_t;
        hoverClass[i].start = clock_t;

        // convert end time to 24 hr
        var hour_e = time_e[0].substring(0,2) / 1;
        var min_e = time_e[0].substring(3,5) / 60;
        var offset_e = time_e[0].search(/PM/) > 0 && hour_e != 12 ? 12 : 0;
        var clock_e  = hour_e + min_e + offset_e;
        hoverClass[i].end = clock_e;

        // get the days which something occurs
        var mon = timeslots[i].search(/Mon/g) > 0 ? true : false;
        var tues = timeslots[i].search(/Tues/g) > 0 ? true : false;
        var wed = timeslots[i].search(/Wed/g) > 0 ? true : false;
        var thurs = timeslots[i].search(/Thur/g) > 0 ? true : false;
        var fri = timeslots[i].search(/Fri/g) > 0 ? true : false;
        hoverClass[i].mon = mon;
        hoverClass[i].tues = tues;
        hoverClass[i].wed = wed;
        hoverClass[i].thurs = thurs;
        hoverClass[i].fri = fri;

        // add to the cache
        localStorage['hoverClass'] = JSON.stringify(hoverClass);

        // add to the schedule
        var schedule = $(".calendar > div");
        var curr_time = 8;
        $.each(schedule, function (el) {
          // add the hover attribute
          if (curr_time >= clock_t && curr_time <= clock_e) {
            if (mon) {
              $(this).find(".mon").css("background-color", "blue");
            }
            if (tues) {
              $(this).find(".tues").css("background-color", "blue");
            }
            if (wed) {
              $(this).find(".wed").css("background-color", "blue");
            }
            if (thurs) {
              $(this).find(".thurs").css("background-color", "blue");
            }
            if (fri) {
              $(this).find(".fri").css("background-color", "blue");
            }
          }
          curr_time += 0.5;
        });
      }
    }
  });
});
