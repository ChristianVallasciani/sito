<?php
include '../connessione.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if (!isset($conn) || !$conn || mysqli_connect_errno()) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Errore connessione database.'
    ]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

if (!is_array($input)) {
    $input = $_POST;
}

function rispondiJson(int $statusCode, bool $status, string $message, array $extra = []): void
{
    http_response_code($statusCode);
    echo json_encode(array_merge([
        'status' => $status,
        'message' => $message
    ], $extra));
}

switch ($method) {
    case 'GET':
        $result = mysqli_query($conn, 'SELECT nome, surname, email, ruolo FROM utenti');
        $utenti = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $utenti[] = $row;
        }

        rispondiJson(200, true, 'Lista utenti', ['data' => $utenti]);
        break;

    case 'POST':
        $nome = trim($input['nome'] ?? '');
        $cognome = trim($input['surname'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = trim($input['password'] ?? '');

        if ($nome === '' || $cognome === '' || $email === '' || $password === '') {
            rispondiJson(422, false, 'I campi nome, surname, email e password sono obbligatori.');
            break;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            rispondiJson(422, false, 'Email non valida.');
            break;
        }

        $checkStmt = mysqli_prepare($conn, 'SELECT email FROM utenti WHERE email = ? LIMIT 1');
        mysqli_stmt_bind_param($checkStmt, 's', $email);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);
        $exists = mysqli_num_rows($checkResult) > 0;
        mysqli_stmt_close($checkStmt);

        if ($exists) {
            rispondiJson(409, false, 'Email gia presente.');
            break;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, 'INSERT INTO utenti (nome, surname, email, password, ruolo) VALUES (?, ?, ?, ?, 0)');
        mysqli_stmt_bind_param($stmt, 'ssss', $nome, $cognome, $email, $passwordHash);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($ok) {
            rispondiJson(200, true, 'Utente aggiunto con successo.');
        } else {
            rispondiJson(500, false, 'Errore aggiunta utente.');
        }
        break;

    case 'PUT':
        $email = trim($input['email'] ?? '');
        $nome = trim($input['nome'] ?? '');
        $cognome = trim($input['surname'] ?? '');
        $ruolo = isset($input['ruolo']) ? (int)$input['ruolo'] : null;

        if ($email === '') {
            rispondiJson(422, false, 'Email obbligatoria per aggiornare utente.');
            break;
        }

        $campi = [];
        $tipi = '';
        $valori = [];

        if ($nome !== '') {
            $campi[] = 'nome = ?';
            $tipi .= 's';
            $valori[] = $nome;
        }

        if ($cognome !== '') {
            $campi[] = 'surname = ?';
            $tipi .= 's';
            $valori[] = $cognome;
        }

        if ($ruolo !== null) {
            $campi[] = 'ruolo = ?';
            $tipi .= 'i';
            $valori[] = $ruolo;
        }

        if (empty($campi)) {
            rispondiJson(422, false, 'Nessun campo da aggiornare.');
            break;
        }

        $sql = 'UPDATE utenti SET ' . implode(', ', $campi) . ' WHERE email = ?';
        $tipi .= 's';
        $valori[] = $email;

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, $tipi, ...$valori);
        $ok = mysqli_stmt_execute($stmt);
        $righeAggiornate = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        if ($ok && $righeAggiornate >= 0) {
            rispondiJson(200, true, 'Utente aggiornato con successo.');
        } else {
            rispondiJson(500, false, 'Errore aggiornamento utente.');
        }
        break;

    case 'DELETE':
        $email = trim($input['email'] ?? '');

        if ($email === '') {
            rispondiJson(422, false, 'Email obbligatoria per eliminare utente.');
            break;
        }

        $stmt = mysqli_prepare($conn, 'DELETE FROM utenti WHERE email = ?');
        mysqli_stmt_bind_param($stmt, 's', $email);
        $ok = mysqli_stmt_execute($stmt);
        $righeEliminate = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        if ($ok && $righeEliminate > 0) {
            rispondiJson(200, true, 'Utente eliminato con successo.');
        } else {
            rispondiJson(404, false, 'Utente non trovato o non eliminato.');
        }
        break;

    default:
        rispondiJson(405, false, 'Metodo non supportato.');
        break;
}

mysqli_close($conn);
