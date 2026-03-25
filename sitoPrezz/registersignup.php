<?php
header('Content-Type: application/json');

include 'connessione.php';

if (!isset($conn) || !$conn || mysqli_connect_errno()) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Connessione al database non riuscita.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => false,
        'message' => 'Metodo non consentito.'
    ]);
    exit;
}

$nome = trim($_POST['name'] ?? '');
$cognome = trim($_POST['surname'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$confermaPassword = trim($_POST['confirm_password'] ?? '');
$termini = isset($_POST['termini']) ? $_POST['termini'] : '';

$errori = [];

if ($nome === '' || $cognome === '' || $email === '' || $password === '' || $confermaPassword === '') {
    $errori[] = 'Tutti i campi sono obbligatori.';
}

if (strlen($nome) < 2) {
    $errori[] = 'Il nome deve essere di almeno 2 lettere.';
}

if (strlen($cognome) < 2) {
    $errori[] = 'Il cognome deve essere di almeno 2 lettere.';
}

if (!preg_match('/^[A-Za-z]+$/', $nome) || !preg_match('/^[A-Za-z]+$/', $cognome)) {
    $errori[] = 'Nome e cognome devono contenere solo lettere.';
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

if ($password !== $confermaPassword) {
    $errori[] = 'Le password non corrispondono.';
}

if (empty($termini)) {
    $errori[] = 'Devi accettare i Termini e le Condizioni per procedere.';
}

if (!empty($errori)) {
    http_response_code(422);
    echo json_encode([
        'status' => false,
        'message' => implode(' ', $errori)
    ]);
    exit;
}

$stmtCheck = mysqli_prepare($conn, 'SELECT email FROM utenti WHERE email = ? LIMIT 1');
mysqli_stmt_bind_param($stmtCheck, 's', $email);
mysqli_stmt_execute($stmtCheck);
$resultCheck = mysqli_stmt_get_result($stmtCheck);
$esiste = mysqli_num_rows($resultCheck) > 0;
mysqli_stmt_close($stmtCheck);

if ($esiste) {
    http_response_code(409);
    echo json_encode([
        'status' => false,
        'message' => 'Email gia registrata, prova con un altro indirizzo.'
    ]);
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$stmtInsert = mysqli_prepare($conn, 'INSERT INTO utenti (nome, surname, email, password, ruolo) VALUES (?, ?, ?, ?, 0)');
mysqli_stmt_bind_param($stmtInsert, 'ssss', $nome, $cognome, $email, $passwordHash);
$ok = mysqli_stmt_execute($stmtInsert);
mysqli_stmt_close($stmtInsert);

if (!$ok) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Errore durante la registrazione.'
    ]);
    exit;
}

echo json_encode([
    'status' => true,
    'message' => 'Registrazione completata con successo.',
    'redirect' => 'login.php'
]);
