var database = firebase.database();
var buildings;

firebase.database().ref('/buildings/').once('value').then(function(snapshot) {
  $.each(snapshot.val(), function(key) {
    buildings = JSON.parse(snapshot.val()[key]);
  });
});

$("#search_selection").click(function (e) {
  console.log();
  var key = $("#code_search").val();
  $("#table_content").empty();
  if ($(e.target).html() == "Class") {
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

function appendToTable(first, second) {
  row = "<tr><td>" + first + "</td><td>" + second + "</td></tr>";
  $("#table_content").append(row);
}
