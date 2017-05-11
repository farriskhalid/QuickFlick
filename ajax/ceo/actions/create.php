<!--creates a new employee based on input in CEO.php-->

<?php
if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['fname'])
    && isset($_POST['lname']) && isset($_POST['job_location'])
    && isset($_POST['position']) && isset($_POST['salary'])) {

    require("../../database.php");

    $email = $_POST['email'];
    $password = $_POST['password'];
    $first_name = $_POST['fname'];
    $last_name = $_POST['lname'];
    $job_location = $_POST['job_location'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];


    $connection->Create($email, $password, $first_name, $last_name, $job_location, $position, $salary);

}
