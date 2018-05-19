<?php
// create tabke for all previously searched courses
$dbc = mysqli_connect('localhost', 'admin', 'admin', 'information');
$query = "SELECT * FROM classes";
if ($r = mysqli_query($dbc, $query)) {
  while ($row = mysqli_fetch_array($r)) {
    $q = "SELECT * FROM classes_information WHERE class='" . $row['class'] . "'";
    if ($c_r = mysqli_query($dbc, $q)) {
      print "<div class=\"tab-pane fade\" id=\"" . $row['class'] . "\">";
        print "<button type=\"button\" class=\"btn btn-danger col-xs-12 deleteCourse\">Delete " . $row['class'] . "</button>";
        print "<button type=\"button\" class=\"btn btn-warning col-xs-12 removeCourse\">Remove " . $row['class'] . "</button>";
        print "<div class=\"list-group\">";
            print "<a class=\"list-group-item col-xs-12\">";
            print "<h5 class=\"list-group-item-heading col-xs-3\">Course</h5>";
            print "<h5 class=\"list-group-item-heading col-xs-3\">Times</h5>";
            print "<h5 class=\"list-group-item-heading col-xs-3\">Faculty</h5>";
            print "<h5 class=\"list-group-item-heading col-xs-3\">Capacity</h5>";
          print "</a>";
        print "</div>";
      while ($c_row = mysqli_fetch_array($c_r)) {
        // here is the information
        print "<a class=\"list-group-item col-xs-12 section\">";
        print "<p class=\"list-group-item-heading col-xs-3 course\">" . $c_row['link'] . "</p>";
        print "<p class=\"list-group-item-heading col-xs-3 times\">" . $c_row['meeting'] . "</p>";
        print "<p class=\"list-group-item-heading col-xs-3 faculty\">" . $c_row['faculty'] . "</p>";
        print "<p class=\"list-group-item-heading col-xs-3 capacity\">" . $c_row['capacity'] . "</p>";
        print "</a>";
      }
      print "</div>";
    }
  }
}
mysqli_close($dbc);

 ?>
