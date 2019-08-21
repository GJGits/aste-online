<?php
// sanitize input string
function sanitizeString($var) {
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var = stripcslashes($var);
	return mysql_real_escape_string($var);
}

// connect to the DB
// return connection handle
function dbConnect() {
    $conn = @mysqli_connect("localhost","root","secret");
    if(mysqli_connect_error())
        die("Error when connecting to the db: "
            .mysqli_connect_errno()."-".mysqli_connect_error());
    if(!@mysqli_select_db($conn,"myblog"))
        die("Error when selecting the db: ".mysqli_error($conn));
    return $conn;
}

// store a post in the DB with given title and text
function store($title, $text) {
	$conn = dbConnect();
	$title = nl2br(sanitizeString($title));
	$text = nl2br(sanitizeString($text));
	$date = date("Y-m-d H:i:s");
	$sql = "INSERT INTO `myblog`.`post`(`date`, `title`, `text`) VALUES ('".
			$date."', '".$title."', '".$text ."')";
	if (!@mysqli_query($conn, $sql)) {
		die("Query error: ". $sql. "<br>". mysqli_error($conn));
	}
	mysqli_close($conn);
}

// read posts starting from given position (from) in descending date order
// read at most howmany posts (default is 10)
// return array of read posts
function read($from, $howmany=10) {
    $conn = dbConnect();
    $result = array();
    if(!is_numeric($from))
    	return $result;
    if(!is_numeric($howmany))
    	return $result;
    $from=$from-1;
    $sql="SELECT * FROM post ORDER BY date DESC LIMIT ".$from.", ".$howmany;
    if(!($response = @mysqli_query($conn, $sql)))
        die("Error in query: ".$sql."<br>".mysqli_error($conn));
    while ($row = mysqli_fetch_array($response)) {
    	$result[] = $row;
    }
    mysqli_close($conn);
    return $result;
}

// get number of posts
function postNumber() 
{
    $conn = dbConnect();
    $sql = "SELECT MAX(id) as number FROM post";
    if(!$response = @mysqli_query($conn,$sql))
        die("Error in query: ".$sql."<br>".mysql_error($conn));
    $number = mysqli_fetch_array($response);
    mysqli_close($conn);
    if ($number[0])
    	return $number[0];
    else
    	return 0;
}


// check given username and password
// return true if user exists with given username and password
function validUser($user, $password) {
    $conn = dbConnect();
    $user = sanitizeString($user);
    $password = sanitizeString($password);
    $sql = "SELECT password FROM user WHERE name = '". $user . "'";
    $resp = @mysqli_query($conn,$sql);
    if(!$resp)
		die("Error in query: ".$sql."<br>".mysqli_error($conn));
    if (mysqli_num_rows($resp) == 0)
    	return FALSE;
    $row = mysqli_fetch_array($resp, MYSQL_NUM);
    $res = (md5($password) == $row[0]);
    mysqli_close($conn); 
    return ($res);
}


?>