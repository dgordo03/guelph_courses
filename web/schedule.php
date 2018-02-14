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
      }
    </style>
  </head>
  <body>
    <?php include("header.php"); ?>

    <div class="container">
      <form>
        <div class="form-group col-xs-12">
          <label for="terms">Term</label>
          <select class="form-control" id="terms" name="course_term">
            <?php
            $dbc = mysqli_connect('localhost', 'admin', 'admin', 'information');
            $query = "SELECT TERM FROM terms ORDER BY TERM";
            if ($r = mysqli_query($dbc, $query)) {
              while ($row = mysqli_fetch_array($r)) {
                $selected = "";
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                  if (!empty($_GET['course_term']) && $_GET['course_term'] == $row['TERM']) {
                    $selected = "selected";
                  }
                }
                echo "<option $selected>{$row['TERM']}</option>";
              }
            } else {
              echo '<option>No Available Subjects</option>';
            }
            mysqli_close($dbc);
            ?>
          </select>
        </div>
        <div class="form-group col-xs-12 col-sm-4">
          <label for="subject">Subject</label>
          <select id="subject_list" class="form-control" id="subject" name="course_subject">
            <?php
            $dbc = mysqli_connect('localhost', 'admin', 'admin', 'information');
            $query = "SELECT SUBJECT FROM subjects ORDER BY SUBJECT";
            if ($r = mysqli_query($dbc, $query)) {
              while ($row = mysqli_fetch_array($r)) {
                $selected = "";
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                  if (!empty($_GET['course_subject']) && $_GET['course_subject'] == $row['SUBJECT']) {
                    $selected = "selected";
                  }
                }
                echo "<option $selected>{$row['SUBJECT']}</option>";
              }
            } else {
              echo '<option>No Available Subjects</option>';
            }
            mysqli_close($dbc);
            ?>
          </select>
        </div>
        <div class="form-group col-xs-12 col-sm-4">
          <label for="level">Course Level</label>
          <select id="level_list" class="form-control" id="level" name="course_level">
            <?php
            $dbc = mysqli_connect('localhost', 'admin', 'admin', 'information');
            $query = "SELECT COURSE_LEVEL FROM course_levels ORDER BY COURSE_LEVEL";
            if ($r = mysqli_query($dbc, $query)) {
              while ($row = mysqli_fetch_array($r)) {
                $selected = "";
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                  if (!empty($_GET['course_level']) && $_GET['course_level'] == $row['COURSE_LEVEL']) {
                    $selected = "selected";
                  }
                }
                echo "<option $selected>{$row['COURSE_LEVEL']}</option>";
              }
            } else {
              echo '<option>No Available Levels</option>';
            }
            mysqli_close($dbc);
            ?>
          </select>
        </div>
        <div class="form-group col-xs-12 col-sm-4">
          <label for="exampleInputName2">Course Number</label>
          <?php
          $c_level = "";
          if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (!empty($_GET['course_number'])) {
              $c_level = $_GET['course_number'];
            }
          }
          echo "<input type=\"text\" class=\"form-control\" id=\"course_number\" name=\"course_number\" placeholder=\"1000\" value=\"{$c_level}\">";
          ?>
        </div>
        <div class="col-sm-4 col-xs-12">
          <button type="submit" class="btn btn-default col-xs-12">Search</button>
        </div>
      </form>
    </div>
    <div class="container course_information">
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
          $pyscript = 'C:\\xampp\\htdocs\\GitHub\\guelph_courses\\src\\getCourse.py';
          $python = 'C:\\Python27\\python.exe';
          $args_str = "";
          foreach ($args as $key => $value) {
              $args_str .= " {$key}={$value}";
          }
          $cmd = "$python $pyscript $args_str";
          exec($cmd, $output, $ret);

          if (sizeof($output) > 0) {
            print "<div class=\"list-group\">";
            print "<a href=\"#\" class=\"list-group-item col-xs-12\">";
            print "<h5 class=\"list-group-item-heading col-xs-3\">Course</h5>";
            print "<h5 class=\"list-group-item-heading col-xs-3\">Times</h5>";
            print "<h5 class=\"list-group-item-heading col-xs-3\">Faculty</h5>";
            print "<h5 class=\"list-group-item-heading col-xs-3\">Capacity</h5>";
            print "</a>";
          } else {
            print "<div class=\"list-group\">";
            print "<a href=\"#\" class=\"list-group-item col-xs-12\">";
            print "<h5 class=\"list-group-item-heading col-xs-3\">No Available Sections</h5>";
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

            print "<a href=\"#\" class=\"list-group-item col-xs-12\">";
            $link_t = "";
            foreach ($link as $value) {
              $link_t .= $value;
            }
            print "<p class=\"list-group-item-heading col-xs-3\">$link_t</p>";
            $meeting_t = "";
            foreach ($meeting as $value) {
              $meeting_t .= $value;
            }
            print "<p class=\"list-group-item-heading col-xs-3\">$meeting_t</p>";
            $faculty_t = "";
            foreach ($faculty as $value) {
              $faculty_t .= $value;
            }
            print "<p class=\"list-group-item-heading col-xs-3\">$faculty_t</p>";
            $capacity_t = "";
            foreach ($capacity as $value) {
              $capacity_t .= $value;
            }
            print "<p class=\"list-group-item-heading col-xs-3\">$capacity_t</p>";
            print "</a>";
          }
        }
      }
      }
    ?>
    </div>
  </div>
  </body>
</html>
