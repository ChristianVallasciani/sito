<?php
session_start();

include('config.php');

$conn = mysqli_connect($host, $username, $password, $database) OR die ("Errore durante l'apertura della connessione: " . mysqli_error($conn) . " " . mysqli_errno($conn));

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = strip_tags(trim($_POST['username'] ?? ''));
    $password = strip_tags(trim($_POST['password'] ?? ''));

    $errori = [];

    if (empty($username) || empty($password)) {
        $errori[] = 'Tutti i campi sono obbligatori.';
    }

    if (strlen($username) < 5) {
        $errori[] = 'Il tuo username deve essere di almeno 5 caratteri.';
    }

    if (strlen($password) < 6) {
        $errori[] = 'La password deve essere di almeno 6 caratteri.';
    }

    if (!preg_match('/[A-Z]/', $password)) {
        $errori[] = 'La password deve contenere almeno una lettera maiuscola.';
    }

    if (!preg_match('/[0-9]/', $password)) {
        $errori[] = 'La password deve contenere almeno un numero.';
    }

    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $errori[] = 'La password deve contenere almeno un simbolo speciale.';
    }

    if (!empty($errori)) {
        foreach ($errori as $error) {
            echo "$error<br>";
        }
    } else {

        $query = "SELECT * FROM utenti WHERE username = '$username'";
        $risultato = mysqli_query($conn, $query) OR die ("Errore durante la select: " . mysqli_error($conn) . " " . mysqli_errno($conn));

        if (mysqli_num_rows($risultato) > 0) {
            $utente = mysqli_fetch_assoc($risultato);

            if (password_verify($password, $utente['password'])) {
                $nome = $utente['nome'];
                
                    $_SESSION['utente_loggato'] = true;
                    $_SESSION['nome_utente'] = $nome;
                    $_SESSION['username'] = $username;
                    if($utente['admin'] == 1){
                        $_SESSION['admin'] = true;
                    }

                    header("Location: loggato.php");
                    exit;
                
            } else {
                echo "Password errata.";
            }

        } else {
            echo "Username non trovato.";
        }
    }

} else {
    echo "Accesso non valido, non utilizzato POST";
    exit;
}

mysqli_close($conn) OR die ("Errore durante la chiusura della connessione: " . mysqli_error($conn) . " " . mysqli_errno($conn));
?>