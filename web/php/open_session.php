<?php

if (!isset($_SESSION)) {
  session_start();
}


if (empty($_SESSION)) {
  $session = uniqid();
  $_SESSION["session_id"] = $session;
  // create a new table which holds classes table and classes_information table
  $dbc = mysqli_connect('localhost', 'admin', 'admin');
  $query = "CREATE DATABASE $session";
  if ($r = mysqli_query($dbc, $query)) {
    $dbc2 = mysqli_connect('localhost', 'admin', 'admin', "$session");
    $query = "CREATE TABLE classes (
      class varchar(255)
    )";
    mysqli_query($dbc2, $query);
    $query = "CREATE TABLE classes_information (
      class varchar(255),
      link varchar(255),
      meeting varchar(255),
      faculty varchar(255),
      capacity varchar(255)
    )";
    mysqli_query($dbc2, $query);
    mysqli_close($dbc2);
  } else {
    echo "<p>Could not create</p>";
  }
  mysqli_close($dbc);
}
 ?>
