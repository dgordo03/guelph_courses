var database = firebase.database();
var buildings = new Object();
var exams = new Object();
var classes = new Object();

firebase.database().ref('/buildings/').once('value').then(function(snapshot) {
  $.each(snapshot.val(), function(key) {
    curr_building = JSON.parse(snapshot.val()[key]);
    $.each(curr_building, function(building) {
      buildings[building] = curr_building[building]
    });
  });
});

firebase.database().ref('/exams/').once('value').then(function(snapshot) {
  $.each(snapshot.val(), function(key) {
    curr_building = JSON.parse(snapshot.val()[key]);
    $.each(curr_building, function(building) {
      exams[building] = curr_building[building]
    });
  });
});

firebase.database().ref('/classes/').once('value').then(function(snapshot) {
  $.each(snapshot.val(), function(key) {
    all_classes = JSON.parse(snapshot.val()[key]);
    for (curr_class in all_classes) {
      title = all_classes[curr_class].title;
      delete all_classes[curr_class].title;
      classes[title] = all_classes[curr_class];
    }
  });
});

$(document).ready(function() {
  $("#search_selection").click(function (e) {
    $("#building_table").empty();
    $("#date_group").css('display', 'none');
    $("#exam_table").css('display', 'none');
    $("#room_list").empty();

    // $.each(buildings, function (acrnm) {
    //   var name = buildings[acrnm];
    //   var reg1 = new RegExp(key, 'i');
    //   if (acrnm.match(reg1) || name.match(reg1)) {
    //     buildingTable(acrnm, name);
    //   }
    // });
  });

  $("#building_search").keyup(function () {
    var key = $(this).val();
    $("#building_table").empty();
    $.each(buildings, function (acrnm) {
      var name = buildings[acrnm];
      var reg1 = new RegExp(key, 'i');
      if (acrnm.match(reg1) || name.match(reg1)) {
        buildingTable(acrnm, name);
      }
    });
  });

  $("#room_list").change(function () {
    room = $(this).val();
    if (room == "Select Room") {
      $("#date_group").css('display', 'none');
      $("#exam_table").css('display', 'none');
      return;
    }
    $("#exam_table").css('display', 'none');
    building = room.split(" ")[0];
    dates = exams[building][room];
    $("#date_list option").remove();
    $("#date_group").css('display', 'inline-block');
    item = "<option>No Date Selected</option>"
    $("#date_list").append(item);
    for (date in dates) {
      item = "<option>" + date + "</option>"
      $("#date_list").append(item);
    }
  });

  $("#date_list").change(function () {
    date = $(this).val();
    room = $("#room_list").val();
    building = room.split(" ")[0];
    times = exams[building][room][date];
    $("#exam_info tr").remove();
    $("#exam_table").css('display', 'block');
    times.forEach(function (time) {
      row = "<tr>"
      for (start in time) {
        row += "<td>" + start + "</td>"
        row += "<td>" + time[start] + "</td>"
      }
      row += "</tr>"
      $("#exam_info").append(row);
    });
  });

  $("#course_btn").click(function() {
    course = $("#course_search").val()
    $("#course_table").empty();
    $.each(classes, function (title) {
      var reg1 = new RegExp(course, 'i');
      if (title.match(reg1)) {
        courseTable(title);
      }
    });
  });
});

function buildingTable(first, second) {
  row = "<tr id='" + first + "'><td>" + first + "</td><td>" + second + "</td></tr>";
  $("#building_table").append(row);
  $( "#" + first ).bind( "click", function(e) {
    $("#room_list option").remove();
    emptyList = true;
    item = "<option>Select Room</option>";
    $("#room_list").append(item);
    $("#date_group").css('display', 'none');
    $("#exam_table").css('display', 'none');
    $.each(exams[first], function (room) {
      emptyList = false;
      item = "<option>" + room + "</option>";
      $("#room_list").append(item);
    });
    if (emptyList) {
      $("#room_list option").remove();
      item = "<option>No Exams in " + second + "</option>";
      $("#room_list").append(item);
    }
  });
}

function courseTable(title) {
  id = title.split(' ')[0].replace(/\*/, '');
  id = id.split('\/')[0]
  $("#tab_course pre").remove();
  row = "<tr id='" + id + "'><td>" + title + "</td>";
  $("#course_table").append(row);
  $("#" + id).bind( "click", function(e) {
    course1 = title.split(" ")[0].replace(/\*/, ' ')
    course2 = course1.replace(/ /, '');
    $("#tab_course pre").remove();
    for (b in exams) {
      for (r in exams[b]) {
        for (d in exams[b][r]) {
          for (t in exams[b][r][d]) {
            if (course1 in exams[b][r][d][t] || course2 in exams[b][r][d][t]) {
              pre = "<pre><p>" + r + "\t" + d + "</p>";
              for (e in exams[b][r][d][t]) {
                if (e.match(/^[0-9]/)) {
                  pre += "<p>" + e + " until " + exams[b][r][d][t][e] + "</p>";
                }
              }
              pre += "</pre>"
              $("#tab_course").append(pre);
            }
          }
        }
      }
    }
  });
}
