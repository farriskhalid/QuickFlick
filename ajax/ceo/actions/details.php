<!--grabs employee details from $_POST['id'] in CEO.php-->

<?php
if (isset($_POST['id']) && isset($_POST['id']) != "") {
    require '../../database.php';
    $employee_id = $_POST['id'];

    $db = $connection->Details($employee_id);
    echo $db;
}