<?php
$host = "localhost";
$dbname = "my_christianvallasciani";
$user = "christianvallasciani";       
$pass = "";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die('Connessione al database fallita: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');