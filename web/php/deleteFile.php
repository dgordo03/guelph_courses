<?php
// $command = "del ..\\..\\files\\" . $_GET['class'];
// exec($command, $output, $ret);

// delete a class from the database
$dbc = mysqli_connect('localhost', 'admin', 'admin', 'information');
$query = "DELETE FROM classes WHERE class='" . $_GET['class'] . "'";
$r = mysqli_query($dbc, $query);
mysqli_close($dbc);

 ?>
