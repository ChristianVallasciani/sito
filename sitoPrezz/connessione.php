<?php
$host = "localhost";
$dbname = "ina5_vallasciani";
$user = "ina5";       
$pass = "Venita3#";

$conn = mysqli_connect($host, $user, $pass, $dbname);

$conn_error = null;

if (!$conn) {
    $conn_error = mysqli_connect_error();
} else {
    mysqli_set_charset($conn, 'utf8mb4');
}


