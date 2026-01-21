<?php
$host = "localhost";
$dbname = "my_christianvallasciani";
$user = "4063703";       
$pass = "EtrU3TgCEXkE";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die('Connessione al database fallita: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');