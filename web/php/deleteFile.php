<?php
// print $_GET['class'];
$command = "del ..\\..\\files\\" . $_GET['class'];
exec($command, $output, $ret);
 ?>
