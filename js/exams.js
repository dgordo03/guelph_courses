var database = firebase.database();
var buildings = new Object();
var exams = new Object();

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

$( document ).ready(function() {
  $("#search_selection").click(function (e) {
    var key = $("#code_search").val();
    $("#table_content").empty();
    if ($(e.target).html() == "Course") {
      //get firebase working first
    } else {
      $.each(buildings, function (acrnm) {
        var name = buildings[acrnm];
        var reg1 = new RegExp(key, 'i');
        if (acrnm.match(reg1) || name.match(reg1)) {
          appendToTable(acrnm, name);
        }
      });
    }});

  $("#code_search").keyup(function () {
    var key = $(this).val();
    $("#table_content").empty();
    if ($("#class_id").hasClass("active")) {
      //get firebase working first
    } else {
      $.each(buildings, function (acrnm) {
        var name = buildings[acrnm];
        var reg1 = new RegExp(key, 'i');
        if (acrnm.match(reg1) || name.match(reg1)) {
          appendToTable(acrnm, name);
        }
      });
    }
  });

  $("#room_list").change(function () {
    room = $(this).val();
    if (room == "Select Room") {
      //get rid of everything when added
      $("#exam_times").css('display', 'none');
      $("#date_group").css('display', 'none');
      return;
    }
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
    $("#exam_times li").remove();
    $("#exam_times").css('display', 'block');
    times.forEach(function (time) {
      for (start in time) {
        item = "<li class='list-group-item'>" + start + " to " + time[start] + "</li>";
        $("#exam_times").append(item);
      }
    });
  });
});

function appendToTable(first, second) {
  row = "<tr id='" + first + "'><td>" + first + "</td><td>" + second + "</td></tr>";
  $("#table_content").append(row);
  $( "#" + first ).bind( "click", function(e) {
    $("#room_list option").remove();
    emptyList = true;
    item = "<option>Select Room</option>";
    $("#room_list").append(item);
    $("#date_group").css('display', 'none');
    $("#exam_times").css('display', 'none');
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
