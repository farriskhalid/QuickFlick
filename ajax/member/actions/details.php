<!--gets member details for members.php-->
<?php
if (isset($_POST['id']) && isset($_POST['id']) != "") {
    require '../../database.php';
    $member_id = $_POST['id'];

    $db = $connection->memberDetails($member_id);
    echo $db;
}