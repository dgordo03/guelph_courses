var activeColours = new Object;
var overlapping_classes;

function getActiveClass() {
  var className;
  $(".classPills > li").each(function () {
    if ($(this).hasClass('active')) {
      className = $(this).text();
    }
  });
  return className;
}

function decToHex(decimal) {
  var remainder;
  var remaining = decimal;
  var hex = "";
  do {
    remainder = remaining % 16;
    remaining = Math.floor(remaining / 16);
    // > 9 ? change to A - F
    var current;
    switch (remainder) {
      case 10:
        current = "A";
        break;
      case 11:
        current = "B";
        break;
      case 12:
        current = "C";
        break;
      case 13:
        current = "D";
        break;
      case 14:
        current = "E";
        break;
      case 15:
        current = "F";
        break;
      default:
        current = remainder;
        break;
    }
    hex = current + hex;
  } while (remaining > 0);
  hex = "#" + hex;
  return hex;
}

function randomColour() {
  var colour;
  colour = Math.floor(Math.random()*16777215);
  colour = decToHex(colour);
  return colour;
}

function selectNewColour() {
  do {
    var curr_colour = randomColour();
    var new_colour = true;
    $.each(activeColours, function (key, value) {
      if (value == curr_colour) {
        new_colour = false;
      }
    });
  } while (!new_colour);
  return curr_colour;
}

function calendar(classInfo, color, className) {
  for (var i = 1; i < classInfo.length; i++) {
    // add to the schedule
    var schedule = $(".calendar > div");
    var curr_time = 8;
    var overlap = false;
    $.each(schedule, function (el) {
      // add the hover attribute
      if (curr_time >= classInfo[i].start && curr_time <= classInfo[i].end) {
        if (classInfo[i].mon) {
          if ($(this).find(".mon").text().length > 0 && $(this).find(".mon").text() != className) {
            overlap = true;
          }
          $(this).find(".mon").css("background-color", color);
          $(this).find(".mon").text(className);
        }
        if (classInfo[i].tues) {
          if ($(this).find(".tues").text().length > 0 && $(this).find(".tues").text() != className) {
            overlap = true;
          }
          $(this).find(".tues").css("background-color", color);
          $(this).find(".tues").text(className);
        }
        if (classInfo[i].wed) {
          if ($(this).find(".wed").text().length > 0 && $(this).find(".wed").text() != className) {
            overlap = true;
          }
          $(this).find(".wed").css("background-color", color);
          $(this).find(".wed").text(className);
        }
        if (classInfo[i].thurs) {
          if ($(this).find(".thurs").text().length > 0 && $(this).find(".thurs").text() != className) {
            overlap = true;
          }
          $(this).find(".thurs").css("background-color", color);
          $(this).find(".thurs").text(className);
        }
        if (classInfo[i].fri) {
          if ($(this).find(".fri").text().length > 0 && $(this).find(".fri").text() != className) {
            overlap = true;
          }
          $(this).find(".fri").css("background-color", color);
          $(this).find(".fri").text(className);
        }
      }
      curr_time += 0.5;
    });
  }
  return overlap;
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

$(document).ready(function() {

  function updateTable() {
    $(".classPills > li").each(function () {
      var curr_class = $(this).text();
      if (sessionStorage.getItem(curr_class)) {
        var colour;
        curr_class in activeColours ? colour = activeColours[curr_class] : colour = selectNewColour();
        activeColours[curr_class] = colour;
        calendar(JSON.parse(sessionStorage[curr_class]), colour, curr_class);
      }
    });
  }

  $(".section").mouseleave(function (e) {
    var className = getActiveClass();
    var hoverClass = JSON.parse(sessionStorage['hoverClass']) || {};
    var selectedClass = new Object;
    if (sessionStorage.getItem(className)) {
      selectedClass = JSON.parse(sessionStorage[className]);
    }
    calendar(hoverClass, "white", "");
    var colour;
    className in activeColours ? colour = activeColours[className] : colour = selectNewColour();
    calendar(selectedClass, colour, className);
    updateTable();
  });

  $(".section").click(function (e) {
    if (overlapping_classes) {
      return;
    }
    var className = getActiveClass();
    if (sessionStorage.getItem(className)) {
      calendar(JSON.parse(sessionStorage[className]), "white", "");
    }
    sessionStorage[className] = sessionStorage['hoverClass'];
    var colour;
    className in activeColours ? colour = activeColours[className] : colour = selectNewColour();
    activeColours[className] = colour;
    calendar(JSON.parse(sessionStorage[className]), colour, className);
    updateTable();
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
        sessionStorage['hoverClass'] = JSON.stringify(hoverClass);
      }
      // add to the calendar
      updateTable();
      overlapping_classes = calendar(hoverClass, "#ccffff", className);
      if (overlapping_classes) {
        $(this).addClass("overlap_class");
        $(this).removeClass("new_class");
      } else {
        $(this).removeClass("overlap_class");
        $(this).addClass("new_class");
      }
    }
  });

  $("#clearAllCourses").click(function () {
    if (sessionStorage.getItem('selectedClass')) {
      calendar(JSON.parse(sessionStorage['selectedClass']), "white", "");
    }

    $(".classPills > li").each(function () {
      deleteClass($(this).text());
    });

    sessionStorage.clear();
    window.location = window.location.pathname;
  });


  $(".deleteCourse").click(function () {
    var className = $(this).text().split(" ")[1];
    deleteClass(className);
    if (sessionStorage.getItem(className)) {
      sessionStorage.removeItem(className);
    }
    window.location = window.location.pathname;
  });

  $(".removeCourse").click(function () {
    var className = $(this).text().split(" ")[1];
    if (sessionStorage.getItem(className)) {
      calendar(JSON.parse(sessionStorage[className]), "white", "");
      sessionStorage.removeItem(className);
    }
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

  updateTable();
});
