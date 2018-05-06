<div class="course_information">
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
      }
    }
  }
   ?>
<div class="col-xs-12">
  <!-- need to add way to keep the current courses and replace as necessary -->
  <?php include("activeCourses.php") ?>
</div>
<div class="col-xs-12">
  <?php include("courseRequestTable.php") ?>
</div>
</div>
