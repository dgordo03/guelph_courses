var element;
$(document).ready(function() {
  function calendar(classInfo, color) {
    for (var i = 1; i < classInfo.length; i++) {
      // add to the schedule
      var schedule = $(".calendar > div");
      var curr_time = 8;
      $.each(schedule, function (el) {
        // add the hover attribute
        if (curr_time >= classInfo[i].start && curr_time <= classInfo[i].end) {
          if (classInfo[i].mon) {
            $(this).find(".mon").css("background-color", color);
          }
          if (classInfo[i].tues) {
            $(this).find(".tues").css("background-color", color);
          }
          if (classInfo[i].wed) {
            $(this).find(".wed").css("background-color", color);
          }
          if (classInfo[i].thurs) {
            $(this).find(".thurs").css("background-color", color);
          }
          if (classInfo[i].fri) {
            $(this).find(".fri").css("background-color", color);
          }
        }
        curr_time += 0.5;
      });
    }
  }

  $(".section").mouseleave(function (e) {
    var hoverClass = JSON.parse(localStorage['hoverClass']) || {};
    var selectedClass = new Object;
    if (localStorage.getItem('selectedClass')) {
      selectedClass = JSON.parse(localStorage['selectedClass']);
    }
    calendar(hoverClass, "white");
    calendar(selectedClass, "orange");
  });


  $(".section").click(function (e) {
    if (localStorage.getItem('selectedClass')) {
      calendar(JSON.parse(localStorage['selectedClass']), "white");
    }
    calendar(JSON.parse(localStorage['hoverClass']), "orange");
    localStorage['selectedClass'] = localStorage['hoverClass'];
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
        hoverClass[i].start  = hour_t + min_t + offset_t;
        // hoverClass[i].start = clock_t;

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
      calendar(hoverClass, "#ccffff");
    }
  });

  $("#clearAllCourses").click(function () {
    if (localStorage.getItem('selectedClass')) {
      calendar(JSON.parse(localStorage['selectedClass']), "white");
    }
    localStorage.clear();
  });


  $(".deleteCourse").click(function () {
    // var delClass = "rm -r ../../" +  $(this).text().split(" ")[1];
    // console.log($(this).text().split(" ")[1]);
    $.ajax({
        url: './deleteFile.php',
        type: 'GET',
        data: {
            class : $(this).text().split(" ")[1]
        }
    });
    // delete the course before reloading
    window.location.href = "./schedule.php";
  });

  $("#searchClass").click(function () {
    $(".classPills > li").each(function () {
      if ($(this).attr("class") == "active") {
        if ($(this).text() != "New Class") {
          // delete the info on the current class
          $.ajax({
              url: './deleteFile.php',
              type: 'GET',
              data: {
                  class : $(this).text()
              }
          });
        }
      }
    });
  });

  if (localStorage.getItem('selectedClass')) {
    calendar(JSON.parse(localStorage['selectedClass']), "orange");
  }
});
