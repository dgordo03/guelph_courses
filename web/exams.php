<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Exam Schedules</title>
    <meta name="Description" content="Exam Schedules">
    <meta name="Author" content="Content">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
  </head>
  <body>
    <?php include("header.php"); ?>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      if (!empty($_GET['building'])) {
        $dbc = mysqli_connect('localhost', 'admin', 'admin', 'information');
        $query = "SELECT ROOM, DATE, START, END, COURSE, INSTRUCTOR FROM exams WHERE BUILDING = {$_GET['building']}";
        print "<p>$query</p>";
        // $r = mysqli_query($dbc, $query);
        if ($r = mysqli_query($dbc, $query)) {
          while ($row = mysqli_fetch_array($r)) {
            print_r($row);
          }
        } else {
          print '<p>Could not get</p>';
        }
      }
    }
    ?>
    <div class="container">
      <ul class="nav nav-pills" id="search_selection">
        <?php
        $building_active = '';
        $course_active = 'active';
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
          if (!empty($_GET['building'])) {
            $course_active = '';
            $building_active = 'active';
          }
        }
        print "<li class=\"col-xs-12 col-sm-5 $course_active\" id=\"class_id\"><a href=\"#tab_course\" data-toggle=\"pill\">Course</a></li>";
        print "<li class=\"col-xs-12 col-sm-5 $building_active\" id=\"building_id\"><a href=\"#tab_building\" data-toggle=\"pill\">Building</a></li>";
        ?>
      </ul>
      <div class="tab-content">
        <?php
        print "<div class=\"tab-pane $course_active\" id=\"tab_course\">"
        ?>
          <div class="col-sm-10 col-xs-12">
            <input type="text" class="form-control" placeholder="Search" id="course_search">
          </div>
          <div class="col-sm-2 col-xs-12">
            <button class="btn btn-default" id="course_btn">Search</button>
          </div>
          <table class="table">
            <tbody id="course_table" style="max-height:200px;overflow-y:auto;display:block;"></tbody>
          </table>
        </div>
        <?php
        print "<div class=\"tab-pane $building_active\" id=\"tab_building\">"
        ?>
          <input type="text" class="form-control" placeholder="Search" id="building_search">
          <div class="list-group" style="max-height:200px;overflow-y:auto;display:block;">
            <?php
            $dbc = mysqli_connect('localhost', 'admin', 'admin', 'information');
            $query = "SELECT * FROM buildings ORDER BY building";
            if ($r = mysqli_query($dbc, $query)) {
              while ($row = mysqli_fetch_array($r)) {
                print "<a href=\"exams.php?building={$row['ACRONYM']}\" class='list-group-item'>{$row['BUILDING']}\t{$row['ACRONYM']}</p>";
              }
            } else {
              print "<p>Could not connect to database.</p>";
            }
            mysqli_close($dbc);
            ?>
          </div>
          <div class="form-group">
            <label for="sel1">Class Room List:</label>
            <select id="room_list" class="form-control" id="sel1">
              <option>No Building Selected</option>
            </select>
          </div>
          <div id="date_group" class="form-group" style="display:none;">
            <label for="sel2">Date Selected:</label>
            <select id="date_list" class="form-control" id="sel2">
              <option>No Date Selected</option>
            </select>
          </div>
          <div class="panel panel-default" id="exam_table">
            <!-- Table -->
            <table class="table">
              <thead>
                <tr><th>Start Time</th><th>End Time</th><th>Course</th><th>Instructor</th></tr>
              </thead>
              <tbody id="exam_info"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <!-- <script type="text/javascript" src="../js/exams.js"></script> -->
  </body>
</html>
