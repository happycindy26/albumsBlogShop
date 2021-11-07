<?php
$serverName = "localhost";
$username = "root";
$password = "";
$db = "retro";

$mysqli = new mysqli($serverName, $username, $password, $db);
if ($mysqli->connect_error)
{
    die("Connection failed: " .$mysqli->connection_error);
}
//echo "Connected successfully";
?>