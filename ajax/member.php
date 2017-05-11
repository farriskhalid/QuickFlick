<!--Member View-->
<?php
session_start();
require('database.php');

$favorites = $connection->favorites();

//a member's list of watched movies/history
$all_history = $connection->history();

//a member's list of recommendations based on favorites table
$all_recommendations = $connection->recommendations();

/*
 * LOG OUT USER IF LOG OUT BUTTON HAS BEEN PRESSED
 */
if (isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("location: ../index.php");
}

?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width = device-width, initial-scale=1"/>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css"/>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/main.js"></script>



</head>

    <body>
        <!--    entire navigation is in this block-->
        <div class="container-fluid">
            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
<!--                   this div is  specifically for mobile-->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar"
                                aria-expanded="false" aria-controls="navbar">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" onclick="reset()">
                            FlickPick
                        </a>
                    </div>
                    <!--            settings, history, and favorites-->
                    <div id="navbar" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li><a onclick="showToWatch(<?php echo $_SESSION['user_id'] ?> )" href="#" >To Watch List</a></li>
                            <li><a href="#"  data-toggle="modal" data-target="#favoritesModal">Favorites</a></li>
                            <li><a href="#"  data-toggle="modal" data-target="#myModal">History</a></li>
                            <li><a onclick="GetMemberDetails(<?php echo $_SESSION['user_id'] ?> )" href="#"  data-toggle="modal" data-target="#settingsModal">Settings</a></li>
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
                                <form class="navbar-form" action="member.php" method="post">
                                    <button type="submit" name="logout" class="btn btn-default">Logout</button>
                                </form>
                            </li>
                        </ul>

                    </div>
                </div>
            </nav>
        </div>

    <!--BODY CONTENT FOR SEARCH AND RECOMMENDED MOVIES DISPLAYED-->
        <div class="container-fluid">
            <div class="row col-lg-offset-2" style="max-width: 970px;">
                <div class="input-group">
                    <span class="input-group-addon">Search</span>
                    <input id="search-box" onclick="search()" type="text" class="form-control" placeholder="Search for...">
                </div>
            </div>

                        <!--    DYNAMIC CONTENT LOAD-->
            <div id="result" class="row col-lg-offset-2" style="max-width: 970px;">

            </div>

            <div id="recommended" class="row col-lg-offset-2" style="max-width: 970px;">
                <h2 align="center">Recommended Movies</h2>

                <?php
                foreach($all_recommendations as $result) {
                    echo '<div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <img src="http://www.placehold.it/800x450/ddd/bbb&text=Image" alt="...">
                        <div class="caption">';

                    echo '<h4>';
                    //rating system
                    if ($result['RATING'] >= 7.5){
                        echo '<span class="label label-success">';
                        echo $result['RATING'];
                        echo '</span>  ';
                    }
                    else if($result['RATING'] < 5){
                        echo '<span class="label label-danger">';
                        echo $result['RATING'];
                        echo '</span>  ';
                    }
                    else{
                        echo '<span class="label label-warning">';
                        echo $result['RATING'];
                        echo '</span>  ';
                    }
                    echo $result['TITLE'];
                    echo '</h4>';

                    echo '<h5>';
                    echo '<strong>' . $result['GENRE'] . '</strong>';
                    echo '</h5>';


                    echo '<p><a href="#" class="btn btn-primary" role="button">Watch Trailer</a> 
                          <a href="#" class="btn btn-default" role="button">Watch Movie</a>
                          <a href="#" class="btn btn-default"';
                    echo ' onclick=addToWatchList(' . $result['MOVIE_ID'] . ') ';
                    echo 'role="button">Add to watch</a></p>';

                    echo '</div></div></div>';
                }
                ?>
            </div>



        <!----------------------------------DIALOGS/POPUPS------------------------------------------------------------------->


            <!--        MODEL 1: HISTORY-->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">History</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Rating</th>
                                    <th>Genre</th>
                                    <th>Release Date</th>
                                </tr>
                                </thead>
                                <?php
                                foreach($all_history as $result) {
                                    echo '<tr>';
                                    echo '<td>';
                                    echo $result['TITLE'];
                                    echo '</td>';
                                    echo '<td>';

                                    //rating system
                                    if ($result['RATING'] >= 7.5){
                                        echo '<div class="label label-success">';
                                        echo $result['RATING'];
                                        echo '</div>';
                                    }
                                    else if($result['RATING'] < 5){
                                        echo '<div class="label label-danger">';
                                        echo $result['RATING'];
                                        echo '</div>';
                                    }
                                    else{
                                        echo '<div class="label label-warning">';
                                        echo $result['RATING'];
                                        echo '</div>';
                                    }

                                    echo '</td>';
                                    echo '<td>';
                                    echo $result['GENRE'];
                                    echo '</td>';
                                    echo '<td>';
                                    echo $result['RELEASEDATE'];
                                    echo '</td>';

                                }
                                ?>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--        favorites Modal-->
            <div class="modal fade" id="favoritesModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Favorites</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Rating</th>
                                    <th>Genre</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach($favorites as $each_fav) {
                                    $delete_id = $each_fav['MOVIE_ID'];
                                    echo '<tr>';
                                    echo '<td>';
                                    echo $each_fav['TITLE'];
                                    echo '</td>';
                                    echo '<td>';

                                    //rating system
                                    if ($each_fav['RATING'] >= 7.5){
                                        echo '<div class="label label-success">';
                                        echo $each_fav['RATING'];
                                        echo '</div>';
                                    }
                                    else if($each_fav['RATING'] < 5){
                                        echo '<div class="label label-danger">';
                                        echo $each_fav['RATING'];
                                        echo '</div>';
                                    }
                                    else{
                                        echo '<div class="label label-warning">';
                                        echo $each_fav['RATING'];
                                        echo '</div>';
                                    }

                                    echo '</td>';
                                    echo '<td>';
                                    echo $each_fav['GENRE'];
                                    echo '</td>';
                                    echo '</tr>';

                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal - Update Member Details -->
            <div class="modal fade" id="update_member_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Settings</h4>
                        </div>
                        <div class="modal-body">
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
                                <label for="update_phone">Phone</label>
                                <input type="text" id="update_phone" placeholder="Phone" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <label for="update_address">Address</label>
                                <input type="text" id="update_address" placeholder="Address" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <label for="update_city">City</label>
                                <input type="text" id="update_city" placeholder="City" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <label for="update_state">State</label>
                                <input type="text" id="update_state" placeholder="State" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <label for="update_zip">Zip</label>
                                <input type="text" id="update_zip" placeholder="Zip" class="form-control"/>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="updateMemberSettings()" >Save Changes</button>
                            <input type="hidden" id="hidden_member_id">
                        </div>
                    </div>
                </div>
            </div>
            <!-- // Modal -->
        </div>
    </body>
</html>