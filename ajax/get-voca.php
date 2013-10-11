<?php
header ("Content-Type: application/json");

require_once ('../functions.php');

$book = $date = $score = "";
if (isset($_GET['book'])) $book = trim($_GET['book']);
if (isset($_GET['date'])) $date = trim($_GET['date']);
if (isset($_GET['score'])) $score = trim($_GET['score']);

if ($book && $date == "" && $score == "")
	$q = "SELECT * FROM wordlist WHERE book='$book'";

else if ($book && $date && $score == "")
	$q = "SELECT * FROM wordlist WHERE book='$book' AND date='$date'";

else
	$q = "SELECT * FROM wordlist WHERE book='$book' AND date='$date' AND score=$score";

$r = send_query( $q );

$return = array();
while ( $row = mysqli_fetch_array( $r ) ) {
	array_push($return, $row);
}

echo json_encode($return);

?>