<?php
header('Content-Type: application/json');

include 'connessione.php';

if (!isset($conn) || !$conn || mysqli_connect_errno()) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Connessione al database non riuscita: ' . ($conn_error ?? mysqli_connect_error())
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

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$confermaPassword = trim($_POST['confirm_password'] ?? '');
$termini = isset($_POST['termini']) ? $_POST['termini'] : '';

$errori = [];

if ($username === '' || $email === '' || $password === '' || $confermaPassword === '') {
    $errori[] = 'Tutti i campi sono obbligatori.';
}

if (strlen($username) < 3) {
    $errori[] = 'Lo username deve essere di almeno 3 caratteri.';
}

if (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {
    $errori[] = 'Lo username puo contenere solo lettere, numeri e underscore.';
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

$stmtCheck = mysqli_prepare($conn, 'SELECT id FROM utenti WHERE email = ? OR username = ? LIMIT 1');
if (!$stmtCheck) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Errore query controllo utente: ' . mysqli_error($conn)
    ]);
    exit;
}
mysqli_stmt_bind_param($stmtCheck, 'ss', $email, $username);
mysqli_stmt_execute($stmtCheck);
$resultCheck = mysqli_stmt_get_result($stmtCheck);
$esiste = mysqli_num_rows($resultCheck) > 0;
mysqli_stmt_close($stmtCheck);

if ($esiste) {
    http_response_code(409);
    echo json_encode([
        'status' => false,
        'message' => 'Email o username gia registrati, prova con altri dati.'
    ]);
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$ruolo = 'user';
$stmtInsert = mysqli_prepare($conn, 'INSERT INTO utenti (username, email, password, ruolo) VALUES (?, ?, ?, ?)');
if (!$stmtInsert) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Errore query registrazione: ' . mysqli_error($conn)
    ]);
    exit;
}
mysqli_stmt_bind_param($stmtInsert, 'ssss', $username, $email, $passwordHash, $ruolo);
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
