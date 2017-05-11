<!--creates a record for watchlist from posted values, execute the query defined database.php -->
<?php
require("../../database.php");
session_start();

//ADDS MOVIE 'ID' TO MEMBER'S WATCHLIST
$connection->addToWatch($_POST['id'],$_SESSION['user_id']);
?>