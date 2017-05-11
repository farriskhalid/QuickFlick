<!--initial file upon login-->
<?php
session_start();

require('ajax/database.php');

$message = '';

if(isset($_POST['email']) && isset($_POST['password'])){
    $incorrect = $connection->login($_POST['email'],$_POST['password']);

    /*
     * ALERTS FOR INCORRECT LOGIN CREDENTIALS
     */
    if($incorrect == 'Wrong password'){
        $message = '<div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>WRONG PASSWORD</strong></div>';
    }
    /*
     * ALERT FOR A USER THAT DOES NOT EXIST
     */
    else {
        $message = '<div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>Sorry, </strong>you do not exist please register.</div>';
    }

}


if(isset($_POST['r_email'])){
    if($_POST['r_password'] == $_POST['r_c_password']){
        $connection->register($_POST['r_email'], $_POST['r_password'], $_POST['r_f_name'],$_POST['r_l_name'],
                                    $_POST['r_phone'],$_POST['r_address'],$_POST['r_city'] ,$_POST['r_state'],$_POST['r_zip']);
    }
    /*
     * ALERT FOR A USER FOR PASSWORDS THAT DO NOT WATCH DURING REGISTRATION
     */
    else{ $message= '<div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>passwords do not match</strong></div>';}
}

?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width = device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>FlickPick</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link href="https://fonts.googleapis.com/css?family=Merriweather" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
	<div class="container-fluid">
		<h1 class="page-header" align="center">FlickPick</h1>
		<div class="row">
			<div class="col-md-4">
			</div>
            <div class="col-md-4">

<!--                    wrong password notification-->
                    <?php
                        echo $message;
                     ?>
                <!--            sign-in panel-->
                <div class="panel panel-default">
<!--                    sign in header-->
                    <div class="panel-heading" align="center">LOGIN</div>
                    <!--                sign in body-->
                    <div class="panel-body">
                        <form action="index.php" method="POST">
                            <div class="form-group">
                                <input class="form-control" type="email" name="email" placeholder="Enter Email">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" name="password" placeholder="Enter Password">
                            </div>
                            <div class="form-group">
                                <button class="form-control btn-default" type="submit">Sign In</button>
                            </div>
                        </form>
                    </div>
                    <!--                registration button link-->
                    <div class="panel-footer" align="center">
                        <a href="" data-toggle="modal" data-target="#myModal">Register</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
            </div>

            <!-- Modal for member registration -->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Register</h4>
                        </div>


                        <div class="modal-body">
                            <form action="index.php" method="POST">
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input class="form-control" placeholder="email" type="email" name="r_email">
                                    <label>set password:</label>
                                    <input class="form-control" placeholder="password" type="password" name="r_password">
                                    <label>confirm password:</label>
                                    <input class="form-control" placeholder="password" type="password" name="r_c_password">
                                    <label>First Name:</label>
                                    <input class="form-control" placeholder="First Name" type="text" name="r_f_name">
                                    <label>Last Name:</label>
                                    <input class="form-control" placeholder="Last Name" type="text" name="r_l_name">
                                    <label>Phone Number:</label>
                                    <input class="form-control" placeholder="Phone Number" type="text" name="r_phone">
                                    <label>Address:</label>
                                    <input class="form-control" placeholder="Address" type="text" name="r_address">
                                    <label>City:</label>
                                    <input class="form-control" placeholder="City" type="text" name="r_city">
                                    <label>State:</label>
                                    <input class="form-control" placeholder="State Abbreviation" type="text" name="r_state">
                                    <label>Zipcode:</label>
                                    <input class="form-control" placeholder="Zipcode" type="text" name="r_zip">
                                    <button type="submit" class="btn btn-success">Register</button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>




</body>
</html>