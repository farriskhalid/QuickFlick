<!--update a movie record based on id from posted values, execute the query defined database.php -->
<?php
if (isset($_POST)) {
    require '../../database.php';

    $id = $_POST['id'];
    $title = $_POST['title'];
    $rating = $_POST['rating'];
    $genre = $_POST['genre'];
    $release_date = $_POST['release_date'];

    $connection->movieUpdate($id, $title, $rating, $genre, $release_date);
}