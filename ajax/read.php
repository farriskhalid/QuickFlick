<!--dynamically loads employee content for CEO.php -->
<?php
session_start();
require 'database.php';


$data = '';

$employees = $connection->Read();

if (count($employees) > 0) {

    $data .= '<div class="page-header" align="center">
                    <h3>Employees
                        <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Hire!</button>
                    </h3>
              </div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                            <th>Employee ID</th>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Job Location</th>
                            <th>Position</th>
                            <th>Salary</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            </tr>
                            </thead>';

    foreach ($employees as $employee) {

        $data .= '<tr>
                <td>' . $employee['EMPLOYEE_ID'] . '</td>
                <td>' . $employee['EMAIL'] . '</td>
                <td>' . $employee['FNAME'] . '</td>
                <td>' . $employee['LNAME'] . '</td>
                <td>' . $employee['JOB_LOCATION'] . '</td>
                <td>' . $employee['POSITION'] . '</td>             
                <td>' . $employee['SALARY'] . '</td>
                <td>
                    <button onclick="GetEmployeeDetails(' . $employee['EMPLOYEE_ID'] . ')" class="btn btn-default">
                    <span class="glyphicon glyphicon-trash"></span>
                    </button>
                </td>
                <td>
                    <button onclick="DeleteEmployee(' . $employee['EMPLOYEE_ID'] . ')" class="btn btn-danger">
                    <span class="glyphicon glyphicon-trash"></span>
                    </button>
                </td>
            </tr>';
    }
} else {
    // records not found
    $data .= '<tr><td colspan="6">Records not found!</td></tr>';

}
$data .= '</table>';

echo $data;

?>