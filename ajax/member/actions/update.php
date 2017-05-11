<!--updates member settings based on input in members.php-->
<?php
if (isset($_POST)) {
    require '../../database.php';

    $id = $_POST['id'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $first_name = $_POST['fname'];
    $last_name = $_POST['lname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];

    $connection->updateMemberSettings($id, $email, $password, $first_name, $last_name, $phone, $address, $city, $state, $zip);
}