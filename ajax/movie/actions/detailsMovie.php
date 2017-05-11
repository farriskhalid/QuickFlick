<!--gets movie details, execute the query defined database.php -->
<?php
if (isset($_POST['id']) && isset($_POST['id']) != "") {
    require '../../database.php';
    $movie_id = $_POST['id'];

    $db = $connection->movieDetails($movie_id);
    echo $db;
}