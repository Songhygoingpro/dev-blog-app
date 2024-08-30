<?php

$host = "localhost";
$port = "3307";
$username = "root";
$password = "";
$database_name = "blog_app";

$conn = new mysqli($host, $username, $password, $database_name, $port);

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);
}
