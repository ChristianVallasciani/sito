<?php
session_start();
header('Content-Type: application/json');

include 'connessione.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => false,
        'message' => 'Metodo non consentito.'
    ]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$remember = isset($_POST['remember']) && $_POST['remember'] === 'on';

$errori = [];

if ($email === '' || $password === '') {
    $errori[] = 'Tutti i campi sono obbligatori.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errori[] = 'L\'indirizzo email non e valido.';
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
    http_response_code(422);
    echo json_encode([
        'status' => false,
        'message' => implode(' ', $errori)
    ]);
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT nome, email, password, ruolo FROM utenti WHERE email = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$utente = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$utente) {
    http_response_code(401);
    echo json_encode([
        'status' => false,
        'message' => 'Utente non trovato.'
    ]);
    exit;
}

if (!password_verify($password, $utente['password'])) {
    http_response_code(401);
    echo json_encode([
        'status' => false,
        'message' => 'Password errata.'
    ]);
    exit;
}

$cookieDuration = $remember ? time() + (86400 * 30) : 0;
setcookie('email', $email, $cookieDuration, '/', '', false, true);

$_SESSION['utente_loggato'] = true;
$_SESSION['email'] = $email;
$_SESSION['nome_utente'] = $utente['nome'];
$_SESSION['ruolo'] = (int)$utente['ruolo'];

echo json_encode([
    'status' => true,
    'message' => 'Login effettuato con successo.',
    'redirect' => 'profilo.php'
]);
