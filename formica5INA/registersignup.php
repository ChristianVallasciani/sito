<?php

session_start();

include('config.php');

$conn = mysqli_connect($host, $username, $password, $database) OR die ("Errore durante l'apertura della connessione: " . mysqli_error($conn) . " " . mysqli_errno($conn) );

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST['nome'] ?? '');
    $cognome = trim($_POST['cognome'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confermaPassword = trim($_POST['confermapassword'] ?? '');
    $termini = isset($_POST['termini']) ? $_POST['termini'] : '';

    $errori = [];

    if (empty($nome) || empty($cognome) || empty($email) || empty($password) || empty($confermaPassword)) {
        $errori[] = 'Tutti i campi sono obbligatori.';
    }

    if (strlen($nome) < 2) {
        $errori[] = 'Il nome deve essere di almeno 2 lettere.';
    }

    if (strlen($cognome) < 2) {
        $errori[] = 'Il cognome deve essere di almeno 2 lettere.';
    }

    if (strlen($username) < 5) {
        $errori[] = 'Il tuo username deve essere di almeno 5 caratteri.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errori[] = 'L\'indirizzo email non è valido.';
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

    if ($password !== $confermaPassword) {
        $errori[] = 'Le password non corrispondono.';
    }

    if (empty($termini)) {
        $errori[] = 'Devi accettare i Termini e le Condizioni per procedere.';
    }


    $queryCheckUsername = "SELECT * FROM utenti WHERE username = '$username' LIMIT 1";
    $resultUsername = mysqli_query($conn, $queryCheckUsername) OR die ("Errore nella query: " . mysqli_error($conn) . " " . mysqli_errno($conn));

    if (mysqli_num_rows($resultUsername) > 0) {
        $errori[] = 'Username già in uso, scegline un altro.';
    }

    $queryCheckEmail = "SELECT * FROM utenti WHERE email = '$email' LIMIT 1";
    $resultEmail = mysqli_query($conn, $queryCheckEmail) OR die ("Errore nella query: " . mysqli_error($conn) . " " . mysqli_errno($conn));

    if (mysqli_num_rows($resultEmail) > 0) {
        $errori[] = 'Email già registrata, prova con un altro indirizzo.';
    }


    if (!empty($errori)) {
        foreach ($errori as $error) {
            echo "$error<br>";
        }
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO utenti (nome, cognome, username, email, password, admin) 
                  VALUES ('$nome', '$cognome', '$username', '$email', '$passwordHash', 0)";

        $risultato = mysqli_query($conn, $query) OR die ("Errore durante la 'inserimento dei dati: " . mysqli_error($conn) . " " . mysqli_errno($conn) );

        header("Location: registrato.php");
        exit;
    }

} else {
    echo "Accesso non valido, non utilizzato POST";
    exit;
}


mysqli_close($conn) OR die ("Errore durante la chiusura della connessione: " . mysqli_error($conn) . " " . mysqli_errno($conn) );
?>
