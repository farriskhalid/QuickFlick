/*
 ******************************************
 * Method: hideElement
 * Purpose: to hide div with id=element
 ******************************************
 */
function hideElement(element) {
    var x = document.getElementById(element);
    x.style.display = 'none';
}

/*
 ******************************************
 * Method: unhideElement
 * Purpose: to un-hide div with id=element
 ******************************************
 */
function unhideElement(element) {
    var x = document.getElementById(element);
    x.style.display = 'block';
}

function deleteFavorite(title, user_id){
    $(document).ready(function(){
        $.ajax({
            type: 'GET',
            url: 'movie/actions/deleteFavorite.php'
        })
    });
}

/*
 ******************************************
 * Method: search
 * Purpose: to dynamically display
 *          page div component upon
 *          event keyup from search input
 *          and populate
 ******************************************
 */
function search(){
    $(document).ready(function(){
        $('#search-box').on("keyup input", function () {
            var inputVal = $(this).val();
            if(inputVal.length <= 0){
                unhideElement('recommended');
                hideElement('result');
            }
            else{
                $.ajax({
                    type: 'GET',
                    url: 'search.php',
                    data: 'search_text=' + $('#search-box').val(),
                    success: function (msg) {
                        hideElement('recommended');
                        unhideElement('result');
                        $('#result').html(msg);
                    }
                })
            }
        });
    });
}

/*
 ******************************************
 * Method: reset
 * Purpose: resets page components to
 *          display recommendations results
 *          with search bar
 *          This is used when pressing the
 *          'FlickPick' logo
 ******************************************
 */
function reset(){
    $("#result").hide();
    $("#recommended").show();
}

/*
 ******************************************
 * Method: showToWatch
 * @param id is the unique movie id
 * Purpose: dynamically loads member's
 *          watch list
 ******************************************
 */
function showToWatch(id) {
    $.ajax({
        type: 'POST',
        url: 'watchlist.php',
        data: "id=" + id,
        success: function (data) {
            unhideElement('result');
            hideElement('recommended');
            $('#result').html(data);
        }
    });
}

/*
 ******************************************
 * Method: addToWatchList
 * @param id is the unique movie id
 * Purpose: adds a movie from
  *         recommendation's list to a
  *         member's watchlist in the
  *         database
 ******************************************
 */
function addToWatchList(id) {
    $.ajax({
        type: 'POST',
        url: 'movie/actions/addToWatchList.php',
        data: "id=" + id,
        success: function (data) {}
    });
}
/*
 ******************************************
 * Method: GetMemberDetails
 * @param id is the unique member id
 * Purpose: grabs member details from the
 *          database and populates
 *          update_member_modal modal
 ******************************************
 */
function GetMemberDetails(id) {
    // Add User ID to the hidden field
    $("#hidden_member_id").val(id);
    $.post("member/actions/details.php", {
            id: id
        },
        function (data, status) {
            // PARSE json data
            var member = JSON.parse(data);

            // Assign existing values to the modal popup fields
            $("#update_id").val(id);
            $("#update_email").val(member.EMAIL);
            $("#update_password").val(member.PASSWORD);
            $("#update_first_name").val(member.FNAME);
            $("#update_last_name").val(member.LNAME);
            $("#update_phone").val(member.PHONE);
            $("#update_address").val(member.ADDRESS);
            $("#update_city").val(member.CITY);
            $("#update_state").val(member.STATE);
            $("#update_zip").val(member.ZIP);
        }
    );
    // Open modal popup
    $("#update_member_modal").modal("show");
}

/*
 ******************************************
 * Method: updateMemberSettings
 * Purpose: updates member details to
 *          database based on
 *          update_member_modal modal input
 ******************************************
 */
function updateMemberSettings(){
    // get values
    var email = $("#update_email").val();
    var password = $("#update_password").val();
    var fname = $("#update_first_name").val();
    var lname = $("#update_last_name").val();
    var address = $("#update_address").val();
    var phone = $("#update_phone").val();
    var city = $("#update_city").val();
    var state = $("#update_state").val();
    var zip = $("#update_zip").val();


    if (email == "") {
        alert("Email field is required!");
    }
    else if (password==""){
        alert("Password field is required!");
    }
    else if (fname == "") {
        alert("First name field is required!");
    }
    else if (lname == "") {
        alert("Last name field is required!");
    }
    else if (address==""){
        alert("Address field is required!");
    }
    else if (phone==""){
        alert("Phone number field is required!");
    }
    else if (city==""){
        alert("City field is required!");
    }
    else if (state==""){
        alert("State field is required!");
    }
    else if (zip==""){
        alert("Zip field is required!");
    }
    else {
        // get hidden field value
        var id = $("#hidden_member_id").val();

        // Update the details by requesting to the server using ajax
        $.post("member/actions/update.php", {
                id: id,
                email: email,
                password: password,
                fname: fname,
                lname: lname,
                phone: phone,
                address: address,
                city: city,
                state: state,
                zip: zip
            },
            function (data, status) {
                // hide modal popup
                $("#update_member_modal").modal("hide");

            }
        );
    }
}

/*
 ******************************************
 * Method: readEmployeeRecords
 * Purpose: specifically for CEO.php
 *          grabs employee details from
 *          database to be dynamically
 *          loaded
 ******************************************
 */
function readEmployeeRecords() {
    $.get("read.php", {}, function (data, status) {
        $(".records_content").html(data);
    });
}
/*
 ******************************************
 * Method: GetEmployeeDetails
 * @param id is the unique employee id
 * Purpose: specifically for CEO.php
 *          dynamically loads content
 *          when CEO.php wants to edit
 *          in update_employee_modal div
 ******************************************
 */
function GetEmployeeDetails(id) {
    // Add User ID to the hidden field
    $("#hidden_employee_id").val(id);
    $.post("ceo/actions/details.php", {
            id: id
        },
        function (data, status) {
            // PARSE json data
            var employee = JSON.parse(data);

            // Assign existing values to the modal popup fields
            $("#update_id").val(id);
            $("#update_email").val(employee.EMAIL);
            $("#update_password").val(employee.PASSWORD);
            $("#update_first_name").val(employee.FNAME);
            $("#update_last_name").val(employee.LNAME);
            $("#update_job_location").val(employee.JOB_LOCATION);
            $("#update_position").val(employee.POSITION);
            $("#update_salary").val(employee.SALARY);
        }
    );
    // Open modal popup
    $("#update_employee_modal").modal("show");
}
/*
 ******************************************
 * Method: addEmployee
 * Purpose: specifically for CEO.php
 *          to add a new employee to
 *          database
 ******************************************
 */
function addEmployee() {
    // get values
    var email = $("#email").val();
    email = email.trim();
    var password = $("#password").val();
    password = password.trim();
    var fname = $("#fname").val();
    fname = fname.trim();
    var lname = $("#lname").val();
    lname = lname.trim();
    var job_location = $("#job_location").val();
    job_location = job_location.trim();
    var position = $("#position").val();
    position = position.trim();
    var salary = $("#salary").val();
    salary = salary.trim();


    if (email == "") {
        alert("Email field is required!");
    }

    else if (password==""){
        alert("Password field is required!");
    }
    else if (fname == "") {
        alert("First name field is required!");
    }
    else if (lname == "") {
        alert("Last name field is required!");
    }
    else if (job_location==""){
        alert("Location field is required!");
    }
    else if (position==""){
        alert("Position field is required!");
    }
    else if (salary==""){
        alert("Salary field is required!");
    }

    else {
        // Add employee record
        $.post("ceo/actions/create.php", {
            email: email,
            password: password,
            fname: fname,
            lname: lname,
            job_location: job_location,
            position: position,
            salary: salary

        }, function (data, status) {
            // close the popup
            $("#add_new_record_modal").modal("hide");

            // dynamically reload the table in ceo.php
            readEmployeeRecords();

            // clear fields from the popup
            $("#email").val("");
            $("#password").val("");
            $("#fname").val("");
            $("#lname").val("");
            $("#job_location").val("");
            $("#position").val("");
            $("#salary").val("");
        });
    }
}
/*
 ******************************************
 * Method: UpdateEmployeeDetails
 * Purpose: updates employee details to
 *          database based on
 *          update_employee_modal modal input
 ******************************************
 */
function UpdateEmployeeDetails() {
    // get values
    var email = $("#update_email").val();
    var password = $("#update_password").val();
    var fname = $("#update_first_name").val();
    var lname = $("#update_last_name").val();
    var job_location = $("#update_job_location").val();
    var position = $("#update_position").val();
    var salary = $("#update_salary").val();


    if (email == "") {
        alert("Email field is required!");
    }
    else if (password==""){
        alert("Password field is required!");
    }
    else if (fname == "") {
        alert("First name field is required!");
    }
    else if (lname == "") {
        alert("Last name field is required!");
    }
    else if (job_location==""){
        alert("Location field is required!");
    }
    else if (position==""){
        alert("Position field is required!");
    }
    else if (salary==""){
        alert("Salary field is required!");
    }
    else {
        // get hidden field value
        var id = $("#hidden_employee_id").val();

        // Update the details by requesting to the server using ajax
        $.post("ceo/actions/update.php", {
                id: id,
                email: email,
                password: password,
                fname: fname,
                lname: lname,
                job_location : job_location,
                position: position,
                salary: salary
            },
            function (data, status) {
                // hide modal popup
                $("#update_employee_modal").modal("hide");
                // dynamically load employees table in ceo.php
                readEmployeeRecords();
            }
        );
    }
}

/*
 ******************************************
 * Method: DeleteEmployee
 * @param id is the employee id
 * Purpose: deletes employee based on id
 ******************************************
 */
function DeleteEmployee(id) {
    var conf = confirm("Are you sure you want to fire this employee ?");
    if (conf == true) {
        $.post("ceo/actions/delete.php", {
                id: id
            },
            function (data, status) {
                // dynamically reload employee records in ceo.php
                readEmployeeRecords();
            }
        );
    }
}

/*
 ******************************************
 * Method: readMovieRecords
 * Purpose: dynamically loads movie records
 *          in div='records_content'
 ******************************************
 */
function readMovieRecords() {
    $.get("readMovies.php", {}, function (data, status) {
        $(".records_content").html(data);
    });
}
/*
 ******************************************
 * Method: addMovie
 * Purpose: adds movie to database from
 *          add_new_movie_modal input
 ******************************************
 */
function addMovie(){
// get values
var movieid = $('#movie_id').val();
var title = $("#title").val();
var rating = $("#rating").val();
var genre = $("#genre").val();
var release_date = $("#release_date").val();

if (title == "") {
    alert("Title field is required!");
}
else if (movieid=="") {
    alert("Id field is required!");
}
else if (rating==""){
    alert("Rating field is required!");
}
else if (genre == "") {
    alert("Genre name field is required!");
}
else if (release_date == "") {
    alert("Release Date name field is required!");
}

else {
    $.post("movie/actions/createMovie.php", {
        id :movieid,
        title: title,
        rating: rating,
        genre: genre,
        release_date: release_date

    }, function (data, status) {
        // close the popup
        $("#add_new_movie_modal").modal("hide");

        // dynamically reload the table in ceo.php
        readMovieRecords();

        // clear fields from the popup
        $('#movie_id').val("");
        $("#title").val("");
        $("#rating").val("");
        $("#genre").val("");
        $("#release_date").val("");
    });
}
}
/*
 ******************************************
 * Method: DeleteMovie
 * @param id is the movie id
 * Purpose: deletes movie from database
 *          that matches the id parameter
 ******************************************
 */
function DeleteMovie(id) {
    var conf = confirm("Are you sure you want to delete this movie?");
    if (conf == true) {
        $.post("movie/actions/deleteMovie.php", {
                id: id
            },
            function (data, status) {
                // dynamically reload table in ceo.php
                readMovieRecords();
            }
        );
    }
}

/*
 ******************************************
 * Method: GetMovieDetails
 * @param id is the unique member id
 * Purpose: grabs movie details from the
 *          database and populates
 *          update_movies_modal modal
 ******************************************
 */
function GetMovieDetails(id){
    // Add User ID to the hidden field
    console.log(id);
    $("#hidden_movie_id").val(id);
    $.post("movie/actions/detailsMovie.php", {
            id: id
        },
        function (data, status) {
            // PARSE json data
            var movie = JSON.parse(data);

            // Assign existing values to the modal popup fields
            $("#update_title").val(movie.TITLE);
            $("#update_rating").val(movie.RATING);
            $("#update_genre").val(movie.GENRE);
            $("#update_release_date").val(movie.RELEASEDATE);
        }
    );
    // Open modal popup
    $("#update_movies_modal").modal("show");
}
/*
 ******************************************
 * Method: UpdateMovieDetails
 * Purpose: updates movie details to
 *          database based on
 *          update_movies_modal modal input
 ******************************************
 */
function UpdateMovieDetails() {
    // get values
    var title = $("#update_title").val();
    var rating = $("#update_rating").val();
    var genre = $("#update_genre").val();
    var release_date = $("#update_release_date").val();


    if (title == "") {
        alert("Title field is required!");
    }
    else if (rating==""){
        alert("Rating field is required!");
    }
    else if (genre == "") {
        alert("Genre field is required!");
    }
    else if (release_date == "") {
        alert("Release Date field is required!");
    }
    else {
        // get hidden field value
        var id = $("#hidden_movie_id").val();

        // Update the details by requesting to the server using ajax
        $.post("movie/actions/updateMovie.php", {
                id: id,
                title: title,
                rating: rating,
                genre: genre,
                release_date: release_date
            },
            function (data, status) {

                // hide modal popup
                $("#update_movies_modal").modal("hide");
                // dynamically load movies table
                readMovieRecords();
            }
        );
    }
}


/*
 *
 */