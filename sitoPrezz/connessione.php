<?php
$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$database = getenv('DB_NAME') ?: 'sito';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die('Connessione al database fallita: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
