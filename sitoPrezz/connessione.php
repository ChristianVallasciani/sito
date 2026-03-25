<?php
$host = "localhost";
$dbname = "ina5_vallasciani";
$user = "ina5";       
$pass = "Venita3#";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die('Connessione al database fallita: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');


