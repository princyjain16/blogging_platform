<?php

$host = "127.0.0.1";
$username = "root";
$password = "Princy@16";
$database = "1stop_blog";

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>