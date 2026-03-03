<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "todo_db";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}
?>
