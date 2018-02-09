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
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      if (!empty($_GET['course_number'])) {
        $pyscript = 'C:\\xampp\\htdocs\\GitHub\\guelph_courses\\test.py';
        $python = 'C:\\Python27\\python.exe';
        $cmd = "$python $pyscript";
        exec($cmd, $output);
        print_r($output);
      }
    }
    ?>
  </body>
</html>
