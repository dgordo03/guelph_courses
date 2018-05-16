$(document).ready(function() {
  function getActiveClass() {
    var className;
    $(".classPills > li").each(function () {
      if ($(this).hasClass('active')) {
        className = $(this).text();
      }
    });
    return className;
  }

  function calendar(classInfo, color, className) {
    for (var i = 1; i < classInfo.length; i++) {
      // add to the schedule
      var schedule = $(".calendar > div");
      var curr_time = 8;
      $.each(schedule, function (el) {
        // add the hover attribute
        if (curr_time >= classInfo[i].start && curr_time <= classInfo[i].end) {
          if (classInfo[i].mon) {
            $(this).find(".mon").css("background-color", color);
            $(this).find(".mon").text(className);
          }
          if (classInfo[i].tues) {
            $(this).find(".tues").css("background-color", color);
            $(this).find(".tues").text(className);
          }
          if (classInfo[i].wed) {
            $(this).find(".wed").css("background-color", color);
            $(this).find(".wed").text(className);
          }
          if (classInfo[i].thurs) {
            $(this).find(".thurs").css("background-color", color);
            $(this).find(".thurs").text(className);
          }
          if (classInfo[i].fri) {
            $(this).find(".fri").css("background-color", color);
            $(this).find(".fri").text(className);
          }
        }
        curr_time += 0.5;
      });
    }
  }

  function deleteClass(className) {
    $.ajax({
        url: './deleteClass.php',
        type: 'GET',
        data: {
            class : className
        }
    });
  }

  $(".section").mouseleave(function (e) {
    var className = getActiveClass();
    var hoverClass = JSON.parse(localStorage['hoverClass']) || {};
    var selectedClass = new Object;
    if (localStorage.getItem(className)) {
      selectedClass = JSON.parse(localStorage[className]);
    }
    calendar(hoverClass, "white", "");
    calendar(selectedClass, "orange", className);
  });

  $(".section").click(function (e) {
    var className = getActiveClass();
    if (localStorage.getItem(className)) {
      calendar(JSON.parse(localStorage[className]), "white", "");
    }
    localStorage[className] = localStorage['hoverClass'];
    calendar(JSON.parse(localStorage[className]), "orange", className);
    // localStorage['selectedClass'] = localStorage['hoverClass'];
  });

  $(".section").mouseenter(function (e) {
    var type = $(this).find(".course").text();
    var className = getActiveClass();
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
        hoverClass[i].start  = hour_t + min_t + offset_t;

        // convert end time to 24 hr
        var hour_e = time_e[0].substring(0,2) / 1;
        var min_e = time_e[0].substring(3,5) / 60;
        var offset_e = time_e[0].search(/PM/) > 0 && hour_e != 12 ? 12 : 0;
        hoverClass[i].end  = hour_e + min_e + offset_e;
        // hoverClass[i].end = clock_e;

        // get the days which something occurs
        hoverClass[i].mon = timeslots[i].search(/Mon/g) > 0 ? true : false;
        hoverClass[i].tues = timeslots[i].search(/Tues/g) > 0 ? true : false;
        hoverClass[i].wed = timeslots[i].search(/Wed/g) > 0 ? true : false;
        hoverClass[i].thurs = timeslots[i].search(/Thur/g) > 0 ? true : false;
        hoverClass[i].fri = timeslots[i].search(/Fri/g) > 0 ? true : false;

        // add to the cache
        localStorage['hoverClass'] = JSON.stringify(hoverClass);
      }
      // add to the calendar
      calendar(hoverClass, "#ccffff", className);
    }
  });

  $("#clearAllCourses").click(function () {
    if (localStorage.getItem('selectedClass')) {
      calendar(JSON.parse(localStorage['selectedClass']), "white", "");
    }

    $(".classPills > li").each(function () {
      deleteClass($(this).text());
    });

    localStorage.clear();
    window.location = window.location.pathname;
  });


  $(".deleteCourse").click(function () {
    var className = $(this).text().split(" ")[1];
    deleteClass(className);
    if (localStorage.getItem(className)) {
      localStorage.removeItem(className);
    }
    window.location = window.location.pathname;
  });

  $("#searchClass").click(function () {
    $(".classPills > li").each(function () {
      if ($(this).attr("class") == "active") {
        if ($(this).text() != "New Class") {
          // delete the info on the current class
          deleteClass($(this).text());
        }
      }
    });
  });

  $(".classPills > li").each(function () {
    var curr_class = $(this).text();
    if (localStorage.getItem(curr_class)) {
      calendar(JSON.parse(localStorage[curr_class]), "orange", curr_class);
    }
  });
});
