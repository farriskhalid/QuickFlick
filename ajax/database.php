<?php
class DatabaseActions{
    public $connection;

    /*
 ******************************************
 * Method: connect
 * Purpose: connects to database
 ******************************************
 */
    public function connect() {
//        define('DBCONNSTRING',);   //You can statically define here if you wanted to and plug the statically defined
                                    // into the PDO connection below
//        define('DBUSER',);
//        define('DBPASS','');

        try{
            $this->connection = new PDO('mysql:host=127.0.0.1;dbname=narayan3', 'root', '');
        } catch(PDOException $e){
            die( "Connection failed: " . $e->getMessage());
        }
    }

    /*
     * this function checks if a specific email already exists in either the member table or employee table
     * it returns a string of either 'employee','member', or 'none'
     */

    /**
     * Method: checkCredentials
     * @param $email - see if a specific email already exists in either the member table or employee table
     * @return string - of either 'employee','member', or 'none'
     */
    private function checkCredentials($email){
        $employeeRecord = $this->connection->prepare('SELECT FNAME FROM EmployeesGroup WHERE email=:email');
        $employeeRecord->bindParam(':email',$email);

        $memberRecord = $this->connection->prepare('SELECT FNAME FROM Members WHERE email=:email');
        $memberRecord->bindParam(':email',$email);

        $employeeRecord->execute();
        $result = $employeeRecord->fetch(PDO::FETCH_ASSOC);

        if ($result['FNAME'] == "Boss"){
            return 'boss';
        }
        // check if employee query execution is empty
        else if(isset($result['FNAME'])) {
            return 'employee';
        }
        else{
            $memberRecord->execute();
            $result2 = $memberRecord->fetch(PDO::FETCH_ASSOC);
            if (!empty($result2)){
                return 'member';
            }
            else{
                return 'none';
            }

        }

    }

    /*
     * SPECIFIC LOGS IN THE MEMBER/EMPLOYEE; $EMAIL IS CHECKED BEFORE A LOGIN IS DONE
     * IF A USER'S EMAIL IS ALREADY IN USE, HE/SHE CANNOT LOG IN
     */
    public function login($email, $password){
        $type = $this->checkCredentials($email);

        if ($type=='boss'){
            $records = $this->connection->prepare('SELECT FNAME,EMPLOYEE_ID,email,password FROM EmployeesGroup WHERE email=:email');
            $records->bindParam(':email', $email);
            $records->execute();
            $results = $records->fetch(PDO::FETCH_ASSOC);

            //CEO SPECIFIC
            if(count($results)>0 && ($password == $results['password'])){
                $_SESSION['user_id'] = $results['EMPLOYEE_ID'];
                $_SESSION['f_name'] = $results['FNAME'];
                header("location: ajax/ceo.php");
            }
            else {
                return 'Wrong password';
            }
        }
        else if($type=='employee'){
            $records = $this->connection->prepare('SELECT FNAME,EMPLOYEE_ID,email,password FROM EmployeesGroup WHERE email=:email');
            $records->bindParam(':email', $email);
            $records->execute();
            $results = $records->fetch(PDO::FETCH_ASSOC);

            //statement checks for password match, password match guarantees users be redirect welcome.php
            if(count($results)>0 && ($password == $results['password'])){
                echo 'employee sucks with passwords';
                $_SESSION['user_id'] = $results['EMPLOYEE_ID'];
                $_SESSION['f_name'] = $results['FNAME'];
                header("location: ajax/employee.php");
            }
            else {
                return 'Wrong password';
            }
        }
        else if($type=='member'){
            $records = $this->connection->prepare('SELECT FNAME,MEMBER_ID,email,password FROM Members WHERE email=:email');
            $records->bindParam(':email', $email);
            $records->execute();
            $results = $records->fetch(PDO::FETCH_ASSOC);

            //  password_verify($password,$results['password'])){
            //statement checks for password match, password match guarantees users be redirect welcome.php
            if(count($results)>0 && ($password==$results['password'])){
                $_SESSION['user_id'] = $results['MEMBER_ID'];
                $_SESSION['f_name'] = $results['FNAME'];
                header("location: ajax/member.php");
            }
            else {
                return 'Wrong password';
            }
        }
        return "You do not exist, please register.";
    }
    /*
     * REGISTER A NEW MEMBER
     */
    public function register($email,$password,$fname, $lname,$phone, $address, $city, $state, $zip){
        $result = $this->checkCredentials($email);

        if($result == 'none'){
            $connect = $this->connection->prepare('INSERT INTO members(email,password,fname,lname,
                                              phone,address,city,state,zip) VALUES(:email,:password,
                                              :fname,:lname,:phone,:address,:city,:state,:zip)');

            $connect->bindParam(':email',$email);
            $connect->bindParam(':password',$password);
            $connect->bindParam(':fname',$fname);
            $connect->bindParam(':lname',$lname);
            $connect->bindParam(':phone',$phone);
            $connect->bindParam(':address',$address);
            $connect->bindParam(':city',$city);
            $connect->bindParam(':state',$state);
            $connect->bindParam(':zip',$zip);

            if($connect->execute()){
                echo 'success';
            }
        }
        else{
            echo 'nope';
        };

    }

    /*
     * Retrieves a specific member's favorite movies list
     * and returns it
     */
    public function favorites(){
        $connect = $this->connection->prepare('select * from movies natural join favoritemovies where MEMBER_ID =:member_id');
        $connect->bindParam(':member_id',$_SESSION['user_id']);

        $connect->execute();
        while ($movie = $connect->fetch(PDO::FETCH_ASSOC)){
            $favorites[] = $movie;
        }
        return !empty($favorites)?$favorites:false;
    }

    /*
     * Retrieve's a specific member's history of movies watched
     * and returns it
     */
    public function history(){
        $history_query = $this->connection->prepare('select * from Movies natural join WatchHistory where MEMBER_ID=:member_id');
        $history_query->bindParam(':member_id',$_SESSION['user_id']);
        $history_query->execute();
        while ($history_item = $history_query->fetch(PDO::FETCH_ASSOC)){
            $all_history[] = $history_item;
        }
        return !empty($all_history)?$all_history:false;
    }

    /*
     * Creates a recommendation
     *
     */
    public function recommendations(){
        $connect = $this->connection->prepare('select * from movies where genre=(select genre from movies 
                                              natural join favoritemovies where MEMBER_ID=:member_id limit 1) order by rand() limit 3');
//        $connect = $this->connection->prepare('select * from movies natural join favoritemovies where MEMBER_ID =:member_id LIMIT 3');
        $connect->bindParam(':member_id',$_SESSION['user_id']);

        $connect->execute();
        while ($movie = $connect->fetch(PDO::FETCH_ASSOC)){
            $favorites[] = $movie;
        }
        return $favorites;
    }

    /*
     * searches the database for 'term' that fits any attribute
     */
    public function search($term){
        $search = "%". $term ."%";
        $results = $this->connection->prepare("select * from movies natural join actors natural join actsin
                  where title like :term or rating like :term or genre like :term or releasedate like :term 
                  or fname like :term or lname like :term GROUP by TITLE");
        $results->bindParam(':term', $search);
        $results->execute();

        while ($single_movie = $results->fetch(PDO::FETCH_ASSOC)){
            $all_movies[] = $single_movie;
        }
        return !empty($all_movies)?$all_movies:false;
    }

    /*
     * deletes a record from favorite's table using movie id combined with user id
     */
    public function deleteFavorite($movie_id, $member_id){
        $query = $this->connection->prepare('delete from favoritemovies where movie_id=:movie_id and member_id=:member_id');
        $query->bindParam(':movie_id',$movie_id);
        $query->bindParam(':member_id',$member_id);

        $query->execute();
    }

    /*
     * MEMBER adds a movie to their to watch list
     */
    public function addToWatch($movie_id, $member_id){
        $query = $this->connection->prepare("INSERT into towatch(MOVIE_ID, MEMBER_ID) VALUES(:movie_id, :member_id)");
        $query->bindParam(":movie_id",$movie_id);
        $query->bindParam(":member_id",$member_id);

        $query->execute();
    }
    /*
     * get to watch list for member
     */
    public function getToWatchList($member_id){
        $this->connect();
        $query = $this->connection->prepare("SELECT * FROM towatch WHERE MEMBER_ID=:member_id");
        $query->bindParam(":member_id",$member_id);

        $query->execute();

        while ($results = $query->fetch(PDO::FETCH_ASSOC)){
            $query2 = $this->connection->prepare("SELECT * FROM movies WHERE MOVIE_ID=:movie_id");
            $query2->bindParam(":movie_id",$results['MOVIE_ID']);

            $query2->execute();
            while($all_movies = $query2->fetch(PDO::FETCH_ASSOC)){
                $all_results[] = $all_movies;
            }
        }

        return !empty($all_results)?$all_results:false;

    }

    /*
     * Update member settings
     */
    public function updateMemberSettings($id, $email, $password, $first_name, $last_name, $phone, $address, $city, $state, $zip){
        $query = $this->connection->prepare("UPDATE members set EMAIL=:email, PASSWORD=:password,
                                            FNAME=:fname, LNAME=:lname, PHONE=:phone, ADDRESS=:address, 
                                            CITY=:city, STATE=:state, ZIP=:zip WHERE MEMBER_ID=:id");

        $query->bindParam(':id',$id);
        $query->bindParam(':email', $email);
        $query->bindParam(':password', $password);
        $query->bindParam(':fname', $first_name);
        $query->bindParam(':lname',$last_name);
        $query->bindParam(':phone',$phone);
        $query->bindParam(':address',$address);
        $query->bindParam(':city',$city);
        $query->bindParam(':state',$state);
        $query->bindParam(':zip',$zip);

        $query->execute();

    }

    /**
     * function specifically created for employees
     */
    public function UpdateEmployee($id, $email, $password, $fname, $lname)
    {
        $query = $this->connection->prepare('UPDATE employeesgroup 
                                              SET EMAIL = :email, PASSWORD = :password , FNAME = :fname, 
                                              LNAME = :lname
                                               WHERE EMPLOYEE_ID= :id');

        $query->bindParam("id", $id, PDO::PARAM_INT);
        $query->bindParam("email", $email,PDO::PARAM_STR);
        $query->bindParam("password", $password, PDO::PARAM_STR);
        $query->bindParam("fname", $fname, PDO::PARAM_STR);
        $query->bindParam(':lname', $lname);

        $query->execute();
    }
    /*
     * returns member details as json
     */
    public function memberDetails($member_id)
    {
        $query = $this->connection->prepare("SELECT * FROM members WHERE member_id = :id");
        $query->bindParam("id", $member_id, PDO::PARAM_STR);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);
        return json_encode($data);
    }

    /**************************************************************************************************************
     * ************************************************************************************************************
     * ***********************               CEO  FUNCTIONS ON EMPLOYEES     **************************************
     * ************************************************************************************************************
     * ************************************************************************************************************
     * ************************************************************************************************************
     * */
    public function Create($email, $password, $fname, $lname, $job_location, $position, $salary)
    {
        $query = $this->connection->prepare("INSERT INTO employeesgroup(email, password, fname, lname, job_location, position, salary)
                                    VALUES (:email, :password, :fname, :lname, :job_location, :position, :salary)");

        $query->bindParam(':email', $email);
        $query->bindParam(':password', $password);
        $query->bindParam(':fname', $fname);
        $query->bindParam(':lname',$lname);
        $query->bindParam(':job_location',$job_location);
        $query->bindParam(':position',$position);
        $query->bindParam(':salary',$salary);


        $query->execute();
    }

    public function Read()
    {
        $this->connect();
        $query = $this->connection->prepare("SELECT * FROM employeesgroup");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }
    /*
     * Delete Employee Record by ID
     * */
    public function Delete($employee_id)
    {
        $query = $this->connection->prepare("DELETE FROM employeesgroup WHERE employee_id = :id");
        $query->bindParam("id", $employee_id, PDO::PARAM_STR);
        $query->execute();
    }
    /*
     * Update Employee Record by ID
     * */
    public function Update($id, $email, $password, $fname, $lname, $job_location, $position, $salary)
    {
        $query = $this->connection->prepare('UPDATE employeesgroup 
                                              SET EMAIL = :email, PASSWORD = :password , FNAME = :fname, 
                                              LNAME = :lname, JOB_LOCATION = :job_location, POSITION = :position,
                                              SALARY = :salary
                                               WHERE EMPLOYEE_ID= :id');

        $query->bindParam("id", $id, PDO::PARAM_INT);
        $query->bindParam("email", $email,PDO::PARAM_STR);
        $query->bindParam("password", $password, PDO::PARAM_STR);
        $query->bindParam("fname", $fname, PDO::PARAM_STR);
        $query->bindParam(':lname', $lname);
        $query->bindParam(':job_location', $job_location);
        $query->bindParam(':position', $position);
        $query->bindParam(':salary', $salary);

        $query->execute();
    }
    /*
     * Get Employee Details by ID
     * */
    public function Details($employee_id)
    {
        $query = $this->connection->prepare("SELECT * FROM employeesgroup WHERE employee_id = :id");
        $query->bindParam("id", $employee_id, PDO::PARAM_STR);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);
        return json_encode($data);
    }

    /**************************************************************************************************************
     * ************************************************************************************************************
     * ******************************       MOVIE FUNCTIONS          **********************************************
     * ************************************************************************************************************
     * ************************************************************************************************************
     * ************************************************************************************************************
     * */
    public function createMovie($id, $title, $rating, $genre, $release_date){
        $query = $this->connection->prepare("INSERT INTO movies(MOVIE_ID, TITLE, RATING, GENRE, RELEASEDATE)
                                    VALUES (:id, :title, :rating, :genre, :release_date)");
        $query->bindParam(':id', $id);
        $query->bindParam(':title', $title);
        $query->bindParam(':rating', $rating);
        $query->bindParam(':genre', $genre);
        $query->bindParam(':release_date',$release_date);

        $query->execute();
    }

    public function movieRead()
    {
        $this->connect();
        $query = $this->connection->prepare("SELECT * FROM movies");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }
    /*
     * Delete Movie Record by ID
     * */
    public function movieDelete($movie_id)
    {
        $new_id = "";
        if(strlen($movie_id)<5){
            $i = strlen($movie_id);
            while ($i < 5){
                $new_id .= "0";
                $i++;
            }
            $new_id.=$movie_id;
        }

        $movie_id = $new_id;

        $query = $this->connection->prepare("DELETE FROM movies WHERE movie_id = :id");
        $query->bindParam("id", $movie_id, PDO::PARAM_STR);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);

        return $data;
    }

    /*
     * Update Movie Record by id
     * */
    public function movieUpdate($movie_id, $title, $rating, $genre, $release_date)
    {
        $new_id = "";
        if(strlen($movie_id)<5){
            $i = strlen($movie_id);
            while ($i < 5){
                $new_id .= "0";
                $i++;
            }
            $new_id.=$movie_id;
        }

        $movie_id = $new_id;

        $query = $this->connection->prepare('UPDATE movies 
                                              SET TITLE = :title, RATING=:rating, GENRE=:genre, RELEASEDATE=:releasedate
                                               WHERE MOVIE_ID= :id');

        $query->bindParam("id", $movie_id, PDO::PARAM_INT);
        $query->bindParam(':title', $title);
        $query->bindParam(':rating', $rating);
        $query->bindParam(':genre', $genre);
        $query->bindParam(':releasedate', $release_date);

        $query->execute();
    }

    /*
     * Get Movie Details
     * */
    public function movieDetails($movie_id)
    {
        $new_id = "";
        if(strlen($movie_id)<5){
            $i = strlen($movie_id);
            while ($i < 5){
                $new_id .= "0";
                $i++;
            }
            $new_id.=$movie_id;
        }

        $query = $this->connection->prepare("SELECT * FROM movies WHERE MOVIE_ID = :id");
        $query->bindParam(":id", $new_id);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);
        return json_encode($data);
    }
}

$connection = new DatabaseActions();
$connection->connect();