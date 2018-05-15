<?php
// delete a class from the database
$dbc = mysqli_connect('localhost', 'admin', 'admin', 'information');
$query = "DELETE FROM classes WHERE class='" . $_GET['class'] . "'";
$r = mysqli_query($dbc, $query);
$query = "DELETE FROM classes_information WHERE class='" . $_GET['class'] . "'";
$r = mysqli_query($dbc, $query);
mysqli_close($dbc);
 ?>
