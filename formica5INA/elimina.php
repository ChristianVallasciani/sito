<?php
session_start();
include('config.php');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo "Non autorizzato";
    exit;
}

$conn = mysqli_connect($host, $username, $password, $database)
    OR die("Errore connessione: " . mysqli_error($conn));

$username = mysqli_real_escape_string($conn, $_GET['username'] ?? '');

if (!empty($username)) {

    $delRecapiti = "DELETE FROM recapiti WHERE username='$username'";
    if (!mysqli_query($conn, $delRecapiti)) {
        echo "errore";
        exit;
    }

    $delUtente = "DELETE FROM utenti WHERE username='$username'";
    if (mysqli_query($conn, $delUtente)) {
        echo "success";
    } else {
        echo "errore";
    }

} else {
    echo "Dati mancanti";
}

mysqli_close($conn);
?>
