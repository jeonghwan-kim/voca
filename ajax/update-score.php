<?php
header ("Content-Type: application/json");

if (!isset($_POST['id']) || !isset($_POST['score'])) exit();

require_once ('../functions.php');

$score = $id = "";
if (isset($_POST['id'])) $id = trim($_POST['id']);
if (isset($_POST['score'])) $score = trim($_POST['score']);

$q = "UPDATE wordlist SET score=$score WHERE id=$id";

$r = send_query( $q );

echo 'true';

?>