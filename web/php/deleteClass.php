<?php
// delete a class from the database
if (!isset($_SESSION)) {
  session_start();
}

$dbc = mysqli_connect('localhost', 'admin', 'admin', $_SESSION["session_id"]);
$query = "DELETE FROM classes WHERE class='" . $_GET['class'] . "'";
$r = mysqli_query($dbc, $query);
$query = "DELETE FROM classes_information WHERE class='" . $_GET['class'] . "'";

if ($r = mysqli_query($dbc, $query)) {
  echo "<h1>deleted </h1>";
} else {
  echo "<h1>did not delete</h1>";
}
mysqli_close($dbc);
 ?>
