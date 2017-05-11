<!--Employee View-->

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
                    <li onclick="readMovieRecords()"><a href="#">Add/Delete Movies</a></li>
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
                        <form class="navbar-form" action="employee.php" method="post">
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