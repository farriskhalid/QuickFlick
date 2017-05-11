<!--CEO VIEW-->
<?php
session_start();
require('database.php');

/*
 * LOG OUT USER IF LOG OUT BUTTON HAS BEEN PRESSED
 */
if (isset($_POST['logout'])){
    session_start();
    session_unset();
    session_destroy();
    header("location: ../index.php");
}

?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<header>
    <meta name="viewport" content="width = device-width, initial-scale=1"/>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css"/>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/main.js"></script>
</header>

<body>
<!--    entire navigation is in this block-->
<div class="container-fluid">
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand " href="#">
                    FlickPick
                </a>
            </div>
            <!--            settings, history, and favorites-->
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li onclick="readMovieRecords()"><a href="#">My Movies</a></li>
                    <li id="myEmployees" onclick="readEmployeeRecords()"><a href="#">My Employees</a></li>
                </ul>
                <!--            signed in as specific user-->
                <ul class="nav navbar-nav navbar-right">

                    <li>
                        <p class="navbar-text">Welcome<a href="#" class="navbar-link">
                                <?= $_SESSION['f_name'] ?>
                            </a></p>
                    </li>
                    <li>
                        <!--            logout button-->
                        <form class="navbar-form" action="ceo.php" method="post">
                            <button type="submit" name="logout" class="btn btn-default">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<!-- Dynamic Records Section -->
<div class="container-fluid">
    <div class="row col-lg-offset-2" style="max-width: 970px;">
        <div class="records_content"></div>
    </div>
</div>
<!-- /Dynamic Records Section Section -->
<!-- Bootstrap Modals -->
<!-- Modal - Add New Employee -->
<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="Email" data-error="Email address is invalid" class="form-control" required/>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="Password" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" placeholder="First Name" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" placeholder="Last Name" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="job_location">Job Location</label>
                    <input type="text" id="job_location" placeholder="Job Location" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="position">Position</label>
                    <input type="text" id="position" placeholder="Position" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="salary">Salary</label>
                    <input type="text" id="salary" placeholder="Salary" class="form-control"/>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addEmployee()">Add New Employee</button>
            </div>
        </div>
    </div>
</div>
<!-- // Modal -->
<!-- Modal - Update Employee Details -->
<div class="modal fade" id="update_employee_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Update</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="update_id">Employee ID</label>
                    <input type="text" id="update_id" placeholder="Employee ID" class="form-control" disabled/>
                </div>

                <div class="form-group">
                    <label for="update_email">Email</label>
                    <input type="email" id="update_email" placeholder="Email" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="update_password">Password</label>
                    <input type="password" id="update_password" placeholder="Password" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="update_first_name">First Name</label>
                    <input type="text" id="update_first_name" placeholder="First Name" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="update_last_name">Last Name</label>
                    <input type="text" id="update_last_name" placeholder="Last Name" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="update_job_location">Job Location</label>
                    <input type="text" id="update_job_location" placeholder="Job Location" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="update_position">Position</label>
                    <input type="text" id="update_position" placeholder="Position" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="update_salary">Salary</label>
                    <input type="text" id="update_salary" placeholder="Salary" class="form-control"/>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="UpdateEmployeeDetails()" >Save Changes</button>
                <input type="hidden" id="hidden_employee_id">
            </div>
        </div>
    </div>
</div>
<!-- // Modal -->
<!-- Modal - Add New Movies -->
<div class="modal fade" id="add_new_movie_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add New Movie</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="movie_id">Movie ID</label>
                    <input type="text" id="movie_id" placeholder="Movie ID" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" placeholder="Title" class="form-control" required/>
                </div>

                <div class="form-group">
                    <label for="rating">Rating</label>
                    <input type="text" id="rating" placeholder="Rating" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="genre">Genre</label>
                    <input type="text" id="genre" placeholder="Genre" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="release_date">Release Date</label>
                    <input type="text" id="release_date" placeholder="Release Date" class="form-control"/>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addMovie()">Add New Movie</button>
            </div>
        </div>
    </div>
</div>
<!-- // Modal -->
<!-- Modal - Update Movie Details -->
<div class="modal fade" id="update_movies_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Movie</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="update_title">Title</label>
                    <input type="text" id="update_title" placeholder="Title" class="form-control" required/>
                </div>

                <div class="form-group">
                    <label for="update_rating">Rating</label>
                    <input type="text" id="update_rating" placeholder="Rating" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="update_genre">Genre</label>
                    <input type="text" id="update_genre" placeholder="Genre" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="update_release_date">Release Date</label>
                    <input type="text" id="update_release_date" placeholder="Release Date" class="form-control"/>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="UpdateMovieDetails()" >Save Changes</button>
                <input type="hidden" id="hidden_movie_id">
            </div>
        </div>
    </div>
</div>
<!-- // Modal -->
</body>
</html>