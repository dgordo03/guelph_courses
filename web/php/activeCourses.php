<?php
if (!isset($_SESSION)) {
  session_start();
}

print "<ul class=\"nav nav-pills classPills\">";

$dbc = mysqli_connect('localhost', 'admin', 'admin', $_SESSION["session_id"]);
$query = "SELECT * FROM classes";
if ($r = mysqli_query($dbc, $query)) {
  while ($row = mysqli_fetch_array($r)) {
    print "<li><a data-toggle=\"pill\" href=\"#" . $row['class'] . "\">" . $row['class'] . "</a></li>";
  }
}
mysqli_close($dbc);

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
      if (sizeof($output) > 0) {
        $id = $args['subject'] . "_" . $args['number'];
        // print "<ul class=\"nav nav-pills classPills\">";
        print "<li class=\"active\"><a data-toggle=\"pill\" href=\"#$id\">$id</a></li>";
        print "<li><a data-toggle=\"pill\" href=\"#new_class\">New Class</a></li>";
        print "</ul>";
      } else {
        // print "<ul class=\"nav nav-pills classPills\">";
        print "<li class=\"active\"><a data-toggle=\"pill\" href=\"#new_class\">New Class</a></li>";
        print "</ul>";
      }
    } else {
      // print "<ul class=\"nav nav-pills classPills\">";
      print "<li class=\"active\"><a data-toggle=\"pill\" href=\"#new_class\">New Class</a></li>";
      print "</ul>";
    }
  } else {
    // print "<ul class=\"nav nav-pills classPills\">";
    print "<li class=\"active\"><a data-toggle=\"pill\" href=\"#new_class\">New Class</a></li>";
    print "</ul>";
  }
}
?>
