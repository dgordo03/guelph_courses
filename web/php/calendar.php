<link rel="stylesheet" type="text/css" href="../css/calendar.css">
<div class="row">
  <div class="col-xs-1"></div>
  <div class="col-xs-2 border-bottom">
    <span>Monday</span>
  </div>
  <div class="col-xs-2 border-bottom">
    <span>Tuesday</span>
  </div>
  <div class="col-xs-2 border-bottom">
    <span>Wednesday</span>
  </div>
  <div class="col-xs-2 border-bottom">
    <span>Thursday</span>
  </div>
  <div class="col-xs-2 border-bottom">
    <span>Friday</span>
  </div>
</div>
<?php
for ($time = 8.5; $time < 22.5; $time += 0.5) {
  print "<div class=\"row\"><div class=\"col-xs-1 border-right\"><span>$time</span></div>";
  print "<div class=\"col-xs-2 mon rectangle\"></div>";
  print "<div class=\"col-xs-2 tues rectangle\"><span></span></div>";
  print "<div class=\"col-xs-2 wed rectangle\"><span></span></div>";
  print "<div class=\"col-xs-2 thurs rectangle\"><span></span></div>";
  print "<div class=\"col-xs-2 fri rectangle\"><span></span></div></div>";
}
?>
