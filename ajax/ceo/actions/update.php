<!--updates employee settings based on input in CEO.php-->
<?php
if (isset($_POST)) {
    require '../../database.php';

    $id = $_POST['id'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $first_name = $_POST['fname'];
    $last_name = $_POST['lname'];
    $job_location = $_POST['job_location'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];

    $connection->Update($id, $email, $password, $first_name, $last_name, $job_location, $position, $salary);
}