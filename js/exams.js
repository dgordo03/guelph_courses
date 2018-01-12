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
