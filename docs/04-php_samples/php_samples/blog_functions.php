<?php
function dbConnect() {
	$conn = mysqli_connect("localhost", "root", "secret");
	if (mysqli_connect_errno()) {
		die("Internal error: connection to DB failed ".
		    mysqli_connect_error());
	}
	if (!mysqli_select_db($conn, "myblog")) {
		die("Internal error: selection of DB failed");
	}
	return $conn;
}

function store($title, $text) {
	$conn = dbConnect();
	$title = nl2br(htmlentities($title));
	$text = nl2br(htmlentities($text));
	$date = date("Y-m-d H:i:s");
	$sql = "INSERT INTO post(date, title, text) VALUES ('".
			$date . "', '" . $title . "', '" .$text ."')";
	if (!@mysqli_query($conn, $sql)) {
		die("Query error: ". $sql. "<br>". mysqli_error($conn));
	}
	mysqli_close($conn);
}

function read($from, $howmany=10) {
	if (!is_numeric($from))
		return FALSE;
	if (!is_numeric($howmany))
		return FALSE;
	$conn = dbConnect();
	$result = array();
	$sql="SELECT * FROM post ORDER BY date DESC LIMIT ". $from .", ".$howmany;
	if (!($response=@mysqli_query($conn,$sql))) {
		die("Internal error: error in query");
	}
	while ($row=mysqli_fetch_array($response))
		$result[] = $row;
	mysqli_close($conn);
	return $result;	 
}

function validUser($username , $password) {
	$conn = dbConnect();
	$sql = "SELECT password FROM user WHERE name = '".$username . "'";
	if (!($response = mysqli_query($conn, $sql))) {
		die("Internal error: query error");
	}
	if (mysqli_num_rows($response)==0)
	    return FALSE;
	$row = mysqli_fetch_array($response);
	return (md5($password)==$row[0]);
}

?>