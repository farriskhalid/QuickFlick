<!--dynamic content for reading in movies-->
<?php
require 'database.php';

$data = '';

$movies = $connection->movieRead();

if (count($movies) > 0) {

    $data .= '<div class="page-header" align="center">
                    <h3>Movies
                        <button class="btn btn-success" data-toggle="modal" data-target="#add_new_movie_modal">Add Movie!</button>
                    </h3>
              </div>
                        <div class="table-responsive">
                     
                        <table class="table table-hover">
                        <tr>
                            <th>Movie ID</th>
                            <th>Title</th>
                            <th>Rating</th>
                            <th>Genre</th>
                            <th>Release Date</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        
                        </div>';

    foreach ($movies as $movie) {
        $data .= '<tr>
                <td>' . $movie['MOVIE_ID'] . '</td>
                <td>' . $movie['TITLE'] . '</td>
                <td>' . $movie['RATING'] . '</td>
                <td>' . $movie['GENRE'] . '</td>
                <td>' . $movie['RELEASEDATE'] . '</td>
                <td>
                    <button onclick="GetMovieDetails(' . $movie['MOVIE_ID'] . ')" class="glyphicon glyphicon-edit"></button>
                </td>
                <td>
                    <button onclick="DeleteMovie(' . $movie['MOVIE_ID'] . ')" class="glyphicon glyphicon-trash"></button>
                </td>
            </tr>';
    }
} else {
    // records not found
    $data .= '<tr><td colspan="6">Movie not found!</td></tr>';
}

$data .= '</table>';

echo $data;

