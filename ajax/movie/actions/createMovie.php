<!--create movie from posted values, execute the query -->
<?php
if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['rating']) && isset($_POST['genre'])
    && isset($_POST['release_date'])) {

    require("../../database.php");

    $id = $_POST['id'];
    $title = $_POST['title'];
    $rating = $_POST['rating'];
    $genre = $_POST['genre'];
    $release_date = $_POST['release_date'];

    $connection->createMovie($id, $title, $rating, $genre, $release_date);

}
