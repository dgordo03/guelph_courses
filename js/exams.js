$(document).ready(function() {
  $("#building_search").keyup(function () {
    var reg = new RegExp($(this).val(), "i");
    $("#buildings-list a").each(function(e) {
      var building = $(this).text();
      if ($(this).text().match(reg)) {
        $(this).css("display", "block");
      } else {
        $(this).css("display", "none");
      }
    });
  });
  $("#course_search").keyup(function () {
    var reg = new RegExp($(this).val(), "i");
    $("#course-list a").each(function(e) {
      var course = $(this).text();
      if ($(this).text().match(reg)) {
        $(this).css("display", "block");
      } else {
        $(this).css("display", "none");
      }
    });
  });
});
