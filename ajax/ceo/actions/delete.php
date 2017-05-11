<!--deletes employee based on button in CEO.php-->
<?php
if (isset($_POST['id']) && isset($_POST['id']) != "") {
    require '../../database.php';
    $user_id = $_POST['id'];

    $connection->Delete($user_id);
}
?>