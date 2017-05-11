<!--Dynamic content for watchlist in navigation bar of members.php-->
<?php
session_start();
require('database.php');

$connection = new DatabaseActions();
$results = $connection->getToWatchList($_POST["id"]);

echo '<div class="page-header" align="center"><h2>To Watch List</h2></div>';

foreach($results as $result) {
    echo '<div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <img src="http://www.placehold.it/800x450/ddd/bbb&text=Image" alt="...">
                        <div class="caption">';

    echo '<h4>';
    //rating system
    if ($result['RATING'] >= 7.5) {
        echo '<span class="label label-success">';
        echo $result['RATING'];
        echo '</span>  ';
    } else if ($result['RATING'] < 5) {
        echo '<span class="label label-danger">';
        echo $result['RATING'];
        echo '</span>  ';
    } else {
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
                          <a href="#" class="btn btn-default" role="button">Watch Movie</a></p>';

    echo '</div></div></div>';
}
?>