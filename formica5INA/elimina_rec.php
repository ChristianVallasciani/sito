<?php
session_start();
include('config.php');

if (!isset($_SESSION['utente_loggato']) || $_SESSION['utente_loggato'] !== true) {
    echo "Non autorizzato";
    exit;
}

$conn = mysqli_connect($host, $username, $password, $database) OR die("Errore connessione: " . mysqli_error($conn));

$username = $_SESSION['username'];
$indirizzo = mysqli_real_escape_string($conn, $_GET['indirizzo'] ?? '');

if (!empty($username) && !empty($indirizzo)) {
    $check = "SELECT prioritaria FROM recapiti WHERE indirizzo='$indirizzo' AND username='$username'";
    $res = mysqli_query($conn, $check);
    $row = mysqli_fetch_assoc($res);
    $eraPrioritario = $row && $row['prioritaria'] == 1;

    $query = "DELETE FROM recapiti WHERE indirizzo='$indirizzo' AND username='$username'";
    if (mysqli_query($conn, $query)) {

        if ($eraPrioritario) {
            $setNew = "
                UPDATE recapiti 
                SET prioritaria = 1 
                WHERE username = '$username'
                ORDER BY indirizzo ASC
                LIMIT 1
            ";
            mysqli_query($conn, $setNew);
        }

        echo "success";
    } else {

        echo "errore";
    }
} else {
    echo "Dati mancanti";
}

mysqli_close($conn);
?>
