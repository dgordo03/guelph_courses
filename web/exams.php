<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Exam Schedules</title>
    <meta name="Description" content="Exam Schedules">
    <meta name="Author" content="Content">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
      .scroll-section {
        max-height : 200px;
        overflow-y : auto;
        /* display : block; */
      }
    </style>
  </head>
  <body>
    <?php include("header.php"); ?>
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
          <div class="list-group scroll-section" style="display:block;">
            <?php
            $dbc = mysqli_connect('localhost', 'admin', 'admin', 'information');
            $query = "SELECT * FROM buildings ORDER BY building";
            if ($r = mysqli_query($dbc, $query)) {
              while ($row = mysqli_fetch_array($r)) {
                print "<a href=\"exams.php?building={$row['ACRONYM']}\" class='list-group-item'>{$row['BUILDING']}\t{$row['ACRONYM']}</a>";
              }
            } else {
              print "<p>Could not connect to database.</p>";
            }
            mysqli_close($dbc);
            ?>
          </div>
          <div class="btn-group">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
              if (!empty($_GET['building'])) {
                $dbc = mysqli_connect('localhost', 'admin', 'admin', 'information');
                $query = "SELECT ROOM FROM exams WHERE BUILDING = '{$_GET['building']}'";
                if ($r = mysqli_query($dbc, $query)) {
                  $rooms = [];
                  while ($row = mysqli_fetch_array($r)) {
                    if (empty($rooms)) {
                      echo '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                      if (empty($_GET['room'])) {
                        echo 'Select a Class <span class="caret"></span></button>';
                      } else {
                        echo "{$_GET['room']} <span class=\"caret\"></span></button>";
                      }
                      echo "<ul class='dropdown-menu scroll-section'>";
                    }
                    if (!array_key_exists($row['ROOM'], $rooms)) {
                      $rooms[$row['ROOM']] = $row['ROOM'];
                      $href = "exams.php?building={$_GET['building']}&room={$row['ROOM']}";
                      echo "<li><a href=\"$href\">{$row['ROOM']}</a></li>";
                    }
                  }
                  if (empty($rooms)) {
                    echo '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    echo "No Exams in {$_GET['building']}</button>";
                  } else {
                    echo "</ul>";
                  }
                }
              }
            } else {
              echo '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
              echo "No Building Selected</button>";
            }
            ?>
          </div>
          <div class="btn-group">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
              if (!empty($_GET['room'])) {
                $dbc = mysqli_connect('localhost', 'admin', 'admin', 'information');
                $query = "SELECT DATE FROM exams WHERE ROOM = '{$_GET['room']}' ORDER BY DATE";
                if ($r = mysqli_query($dbc, $query)) {
                  $dates = [];
                  while ($row = mysqli_fetch_array($r)) {
                    if (empty($dates)) {
                      echo '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                      if (empty($_GET['date'])) {
                        echo 'Select a Date <span class="caret"></span></button>';
                      } else {
                        echo "{$_GET['date']} <span class=\"caret\"></span></button>";
                      }
                      echo "<ul class='dropdown-menu scroll-section'>";
                    }
                    if (!array_key_exists($row['DATE'], $dates)) {
                      $href = "exams.php?building={$_GET['building']}&room={$_GET['room']}&date={$row['DATE']}";
                      $dates[$row['DATE']] = $row['DATE'];
                      echo "<li><a href=\"$href\">{$row['DATE']}</a></li>";
                    }
                  }
                }
              }
            }
            ?>
          </div>
          <div id="date_group" class="form-group" style="display:none;">
            <label for="sel2">Date Selected:</label>
            <select id="date_list" class="form-control" id="sel2">
              <option>No Date Selected</option>
            </select>
          </div>
          <?php
          if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (!empty($_GET['date'])) {
              $dbc = mysqli_connect('localhost', 'admin', 'admin', 'information');
              $query = "SELECT START, END, INSTRUCTOR, COURSE FROM exams WHERE ROOM = '{$_GET['room']}' AND DATE = '{$_GET['date']}'";
              if ($r = mysqli_query($dbc, $query)) {
                echo "<div class=\"panel panel-default\">";
                echo "<table class=\"table\">";
                echo "<thead><tr><th>Start Time</th><th>End Time</th><th>Course</th><th>Instructor</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_array($r)) {
                  echo "<tr><td>{$row['START']}</td><td>{$row['END']}</td><td>{$row['COURSE']}</td><td>{$row['INSTRUCTOR']}</td></tr>";
                }
                echo "</tbody></table>";
              }
            }
          }
          ?>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <!-- <script type="text/javascript" src="../js/exams.js"></script> -->
  </body>
</html>
