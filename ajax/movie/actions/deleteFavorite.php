<!--currently non implemented and non functional. Cannot delete dynamically -->

<?php
if (isset($_POST['movie_id']) && isset($_POST['member_id'])) {
    require '../../database.php';

    $movie_id = $_POST['movie_id'];
    $member_id = $_POST['member_id'];

    $connection->deleteFavorite($movie_id, $member_id);
}
?>