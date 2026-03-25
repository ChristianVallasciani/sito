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

if (!$conn) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Errore connessione database.'
    ]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?? [];

switch ($method) {
    case 'GET':
        $result = mysqli_query($conn, 'SELECT nome, surname, email, ruolo FROM utenti');
        $utenti = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $utenti[] = $row;
        }

        echo json_encode([
            'status' => true,
            'message' => 'Lista utenti',
            'data' => $utenti
        ]);
        break;

    case 'POST':
        $nome = trim($input['nome'] ?? '');
        $cognome = trim($input['surname'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = trim($input['password'] ?? '');

        if ($nome === '' || $cognome === '' || $email === '' || $password === '') {
            http_response_code(422);
            echo json_encode([
                'status' => false,
                'message' => 'I campi nome, surname, email e password sono obbligatori.'
            ]);
            break;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(422);
            echo json_encode([
                'status' => false,
                'message' => 'Email non valida.'
            ]);
            break;
        }

        $checkStmt = mysqli_prepare($conn, 'SELECT email FROM utenti WHERE email = ? LIMIT 1');
        mysqli_stmt_bind_param($checkStmt, 's', $email);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);
        $exists = mysqli_num_rows($checkResult) > 0;
        mysqli_stmt_close($checkStmt);

        if ($exists) {
            http_response_code(409);
            echo json_encode([
                'status' => false,
                'message' => 'Email gia presente.'
            ]);
            break;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, 'INSERT INTO utenti (nome, surname, email, password, ruolo) VALUES (?, ?, ?, ?, 0)');
        mysqli_stmt_bind_param($stmt, 'ssss', $nome, $cognome, $email, $passwordHash);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($ok) {
            echo json_encode([
                'status' => true,
                'message' => 'Utente aggiunto con successo.'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'message' => 'Errore aggiunta utente.'
            ]);
        }
        break;

    case 'PUT':
        $email = trim($input['email'] ?? '');
        $nome = trim($input['nome'] ?? '');
        $cognome = trim($input['surname'] ?? '');
        $ruolo = isset($input['ruolo']) ? (int)$input['ruolo'] : null;

        if ($email === '') {
            http_response_code(422);
            echo json_encode([
                'status' => false,
                'message' => 'Email obbligatoria per aggiornare utente.'
            ]);
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
            http_response_code(422);
            echo json_encode([
                'status' => false,
                'message' => 'Nessun campo da aggiornare.'
            ]);
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
            echo json_encode([
                'status' => true,
                'message' => 'Utente aggiornato con successo.'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'message' => 'Errore aggiornamento utente.'
            ]);
        }
        break;

    case 'DELETE':
        $email = trim($input['email'] ?? '');

        if ($email === '') {
            http_response_code(422);
            echo json_encode([
                'status' => false,
                'message' => 'Email obbligatoria per eliminare utente.'
            ]);
            break;
        }

        $stmt = mysqli_prepare($conn, 'DELETE FROM utenti WHERE email = ?');
        mysqli_stmt_bind_param($stmt, 's', $email);
        $ok = mysqli_stmt_execute($stmt);
        $righeEliminate = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        if ($ok && $righeEliminate > 0) {
            echo json_encode([
                'status' => true,
                'message' => 'Utente eliminato con successo.'
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'status' => false,
                'message' => 'Utente non trovato o non eliminato.'
            ]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode([
            'status' => false,
            'message' => 'Metodo non supportato.'
        ]);
        break;
}

mysqli_close($conn);
