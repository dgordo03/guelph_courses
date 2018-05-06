<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['course_term'])) {
    $args['term'] = $_GET['course_term'];
    if (!empty($_GET['course_number'])) {
      $args['number'] = $_GET['course_number'];
    }
    if (!empty($_GET['course_subject'])) {
      $args['subject'] = $_GET['course_subject'];
    }
    if (!empty($_GET['course_level'])) {
      $args['level'] = $_GET['course_level'];
    }
    if (sizeof($args) > 3) {
      print "<div class=\"tab-content\">";
      if (sizeof($output) > 0) {
        $id = $args['subject'] . "_" . $args['number'];
        print "<div class=\"tab-pane fade in active\" id=\"$id\">";
        print "<div class=\"list-group\">";
        print "<a href=\"#\" class=\"list-group-item col-xs-12\">";
        print "<h5 class=\"list-group-item-heading col-xs-3\">Course</h5>";
        print "<h5 class=\"list-group-item-heading col-xs-3\">Times</h5>";
        print "<h5 class=\"list-group-item-heading col-xs-3\">Faculty</h5>";
        print "<h5 class=\"list-group-item-heading col-xs-3\">Capacity</h5>";
        print "</a>";
      } else {
        print "<div class=\"tab-pane fade in active\" id=\"new_class\">";
        print "<div class=\"list-group\">";
        print "<a href=\"#\" class=\"list-group-item col-xs-12\">";
        print "<h5 class=\"list-group-item-heading col-xs-12\">No Available Sections</h5>";
        print "</a>";
      }

      foreach ($output as $iter) {
        // split up the information from one another
        $temp = explode("meeting=", $iter);
        $temp = explode("faculty=", $temp[1]);
        $meeting = $temp[0];
        $temp = explode("capacity=", $temp[1]);
        $faculty = $temp[0];
        $temp = explode("link=", $temp[1]);
        $capacity = $temp[0];
        $link = $temp[1];
        // split up the info from entries
        $meeting = explode("NEXT", $meeting);
        $faculty = explode("NEXT", $faculty);
        $capacity = explode("NEXT", $capacity);
        $link = explode("NEXT", $link);

        print "<a href=\"#\" class=\"list-group-item col-xs-12 section\">";
        $link_t = "";
        foreach ($link as $value) {
          $link_t .= $value;
        }
        print "<p class=\"list-group-item-heading col-xs-3 course\">$link_t</p>";
        $meeting_t = "";
        foreach ($meeting as $value) {
          $meeting_t .= $value;
        }
        print "<p class=\"list-group-item-heading col-xs-3 times\">$meeting_t</p>";
        $faculty_t = "";
        foreach ($faculty as $value) {
          $faculty_t .= $value;
        }
        print "<p class=\"list-group-item-heading col-xs-3 faculty\">$faculty_t</p>";
        $capacity_t = "";
        foreach ($capacity as $value) {
          $capacity_t .= $value;
        }
        print "<p class=\"list-group-item-heading col-xs-3 capacity\">$capacity_t</p>";
        print "</a>";
      }
      print "</div></div>";
      if (sizeof($output) > 0) {
        print "<div class=\"tab-pane fade\" id=\"new_class\">";
        print "<div class=\"list-group\">";
        print "<a href=\"#\" class=\"list-group-item col-xs-12\">";
        print "<h5 class=\"list-group-item-heading col-xs-12\">Search for a Class to Begin</h5>";
        print "</a></div></div>";
      }
      print "</div>";
    } else {
      // tab-content must be around the entirety of the tabs
      print "<div class=\"tab-content\">";
      print "<div class=\"tab-pane fade in active\" id=\"new_class\">";
      print "<div class=\"list-group\">";
      print "<a href=\"#\" class=\"list-group-item col-xs-12\">";
      print "<h5 class=\"list-group-item-heading col-xs-12\">All Fields Must be Filled Out</h5>";
      print "</a></div></div></div>";
    }
  } else {
    print "<div class=\"tab-content\">";
    print "<div class=\"tab-pane fade in active\" id=\"new_class\">";
    print "<div class=\"list-group\">";
    print "<a href=\"#\" class=\"list-group-item col-xs-12\">";
    print "<h5 class=\"list-group-item-heading col-xs-12\">Search for a Class to Begin</h5>";
    print "</a></div></div></div>";
  }
}
?>
