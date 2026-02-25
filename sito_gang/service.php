<?php
// =============================================
// SERVIZIO RESTful PHP - API REST Endpoint
// =============================================
// Restituisce solo dati in JSON separato dal frontend
// Endpoint: service.php
//
// METODI HTTP supportati:
//   GET    → Visualizzare l'elenco degli utenti registrati
//   POST   → Aggiungere un nuovo utente (+ login, indirizzo)
//   PUT    → Modificare i dati di un utente esistente
//   DELETE → Eliminare un utente
// =============================================

// Indica al client che il contenuto restituito è JSON
header("Content-Type: application/json");

// Permette richieste da qualsiasi dominio (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Gestione preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ----------------------
// CONNESSIONE AL DATABASE
// ----------------------
include "connessione.php";

if (!$conn) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Errore connessione database"
    ]);
    exit;
}

// ----------------------
// LETTURA METODO HTTP E DATI
// ----------------------
$method = $_SERVER['REQUEST_METHOD'];
$data   = json_decode(file_get_contents('php://input'), true);

// ----------------------
// ROUTING PER METODO HTTP
// ----------------------
switch ($method) {

    // ==============================================
    // GET → Visualizzare l'elenco degli utenti
    // ==============================================
    // GET service.php              → tutti gli utenti
    // GET service.php?email=xxx    → singolo utente
    case 'GET':
        // Parametro opzionale: filtra per email
        $email_filter = $_GET['email'] ?? '';

        if ($email_filter !== '') {
            // ---------- Singolo utente ----------
            $stmt = mysqli_prepare($conn, "SELECT email, nome, surname, ruolo FROM utenti WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "s", $email_filter);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $utente = $result ? mysqli_fetch_assoc($result) : null;

            if (!$utente) {
                http_response_code(404);
                echo json_encode(["status" => false, "message" => "Utente non trovato."]);
            } else {
                echo json_encode([
                    "status"  => true,
                    "message" => "Dettaglio utente",
                    "data"    => $utente
                ]);
            }
        } else {
            // ---------- Lista completa ----------
            $result = mysqli_query($conn, "SELECT email, nome, surname, ruolo FROM utenti");
            $users = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }

            echo json_encode([
                "status"  => true,
                "message" => "Lista utenti",
                "data"    => $users
            ]);
        }
        break;

    // ==============================================
    // POST → Aggiungere un nuovo utente / Login / Indirizzo
    // ==============================================
    // Il campo "action" distingue le operazioni di creazione:
    //   action = "register"           → registrazione utente
    //   action = "login"              → autenticazione
    //   action = "aggiungi_indirizzo" → nuovo indirizzo
    case 'POST':
        $action = $data['action'] ?? '';

        // ----------------------
        // LOGIN
        // ----------------------
        if ($action === 'login') {
            $email    = trim($data['email'] ?? '');
            $password = trim($data['password'] ?? '');
            $ricordami = !empty($data['remember']);

            if (empty($email) || empty($password)) {
                echo json_encode(["status" => false, "message" => "Email e password obbligatorie."]);
                break;
            }

            $stmt = mysqli_prepare($conn, "SELECT * FROM utenti WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (!$result || mysqli_num_rows($result) === 0) {
                echo json_encode(["status" => false, "message" => "Utente non trovato."]);
                break;
            }

            $utente = mysqli_fetch_assoc($result);

            if (!password_verify($password, $utente['password'])) {
                echo json_encode(["status" => false, "message" => "Password errata."]);
                break;
            }

            $cookieDuration = $ricordami ? time() + 86400 * 30 : 0;
            setcookie("email", $email, $cookieDuration, "/");

            echo json_encode([
                "status"  => true,
                "message" => "Benvenuto {$utente['nome']}! Login effettuato.",
                "data"    => [
                    "email" => $utente['email'],
                    "nome"  => $utente['nome'],
                    "ruolo" => (int)$utente['ruolo']
                ]
            ]);
        }

        // ----------------------
        // REGISTRAZIONE (Aggiungere un nuovo utente)
        // ----------------------
        elseif ($action === 'register') {
            $name             = trim($data['name'] ?? '');
            $surname          = trim($data['surname'] ?? '');
            $email            = trim($data['email'] ?? '');
            $password         = trim($data['password'] ?? '');
            $confirm_password = trim($data['confirm_password'] ?? '');

            if (empty($name) || empty($surname) || empty($email) || empty($password) || empty($confirm_password)) {
                echo json_encode(["status" => false, "message" => "Tutti i campi sono obbligatori."]);
                break;
            }

            if (!preg_match("/^[A-Za-z]+$/", $name) || !preg_match("/^[A-Za-z]+$/", $surname)) {
                echo json_encode(["status" => false, "message" => "Nome e cognome devono contenere solo lettere."]);
                break;
            }

            if ($password !== $confirm_password) {
                echo json_encode(["status" => false, "message" => "Le password non coincidono."]);
                break;
            }

            if (strlen($password) < 8) {
                echo json_encode(["status" => false, "message" => "La password deve avere almeno 8 caratteri."]);
                break;
            }

            $check_stmt = mysqli_prepare($conn, "SELECT * FROM utenti WHERE email = ?");
            mysqli_stmt_bind_param($check_stmt, "s", $email);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);

            if (mysqli_num_rows($check_result) !== 0) {
                echo json_encode(["status" => false, "message" => "L'email è già registrata."]);
                break;
            }

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = mysqli_prepare($conn, "INSERT INTO utenti (nome, surname, email, password, ruolo) VALUES (?, ?, ?, ?, 0)");
            mysqli_stmt_bind_param($insert_stmt, "ssss", $name, $surname, $email, $password_hash);

            if (mysqli_stmt_execute($insert_stmt)) {
                http_response_code(201); // 201 Created
                echo json_encode([
                    "status"  => true,
                    "message" => "Benvenuto $name! Registrazione completata.",
                    "data"    => ["email" => $email, "nome" => $name]
                ]);
            } else {
                echo json_encode(["status" => false, "message" => "Errore durante la registrazione."]);
            }
        }

        // ----------------------
        // AGGIUNGI INDIRIZZO
        // ----------------------
        elseif ($action === 'aggiungi_indirizzo') {
            if (!isset($_COOKIE['email'])) {
                http_response_code(401);
                echo json_encode(["status" => false, "message" => "Non autenticato."]);
                break;
            }

            $email     = $_COOKIE['email'];
            $via       = trim($data['via'] ?? '');
            $citta     = trim($data['citta'] ?? '');
            $cap       = trim($data['cap'] ?? '');
            $provincia = trim($data['provincia'] ?? '');
            $paese     = trim($data['paese'] ?? '');

            if ($via === '' || $citta === '' || $cap === '' || $paese === '') {
                echo json_encode(["status" => false, "message" => "Compila tutti i campi obbligatori."]);
                break;
            }
            if (strlen($via) < 3 || strlen($via) > 100) {
                echo json_encode(["status" => false, "message" => "La via deve avere tra 3 e 100 caratteri."]);
                break;
            }
            if (strlen($citta) < 2 || strlen($citta) > 50) {
                echo json_encode(["status" => false, "message" => "La città deve avere tra 2 e 50 caratteri."]);
                break;
            }
            if (!preg_match('/^\d{5}$/', $cap)) {
                echo json_encode(["status" => false, "message" => "Il CAP deve essere composto da 5 cifre."]);
                break;
            }
            if (strlen($provincia) > 2) {
                echo json_encode(["status" => false, "message" => "La provincia deve essere max 2 caratteri."]);
                break;
            }
            if (strlen($paese) < 2 || strlen($paese) > 50) {
                echo json_encode(["status" => false, "message" => "Il paese deve avere tra 2 e 50 caratteri."]);
                break;
            }

            $stmt = mysqli_prepare($conn, "INSERT INTO indirizzi (utente_email, via, citta, cap, provincia, paese) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'ssssss', $email, $via, $citta, $cap, $provincia, $paese);

            if (mysqli_stmt_execute($stmt)) {
                http_response_code(201);
                echo json_encode([
                    "status"  => true,
                    "message" => "Indirizzo salvato correttamente.",
                    "data"    => ["via" => $via, "citta" => $citta, "cap" => $cap, "provincia" => $provincia, "paese" => $paese]
                ]);
            } else {
                echo json_encode(["status" => false, "message" => "Errore durante il salvataggio."]);
            }
            mysqli_stmt_close($stmt);
        }

        // ----------------------
        // ACTION NON VALIDA
        // ----------------------
        else {
            http_response_code(400);
            echo json_encode(["status" => false, "message" => "Azione POST non valida. Usa action: login | register | aggiungi_indirizzo"]);
        }
        break;

    // ==============================================
    // PUT → Modificare i dati di un utente esistente
    // ==============================================
    // Campi aggiornabili: nome, surname, ruolo
    // Richiede: { "email": "...", ... campi da aggiornare }
    case 'PUT':
        $email = trim($data['email'] ?? '');

        if (empty($email)) {
            http_response_code(400);
            echo json_encode(["status" => false, "message" => "Email dell'utente obbligatoria."]);
            break;
        }

        // Verifica che l'utente esista
        $check_stmt = mysqli_prepare($conn, "SELECT * FROM utenti WHERE email = ?");
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        $utente_esistente = $check_result ? mysqli_fetch_assoc($check_result) : null;

        if (!$utente_esistente) {
            http_response_code(404);
            echo json_encode(["status" => false, "message" => "Utente non trovato."]);
            break;
        }

        // Raccogli i campi da aggiornare
        $campi  = [];
        $valori = [];
        $tipi   = '';

        if (isset($data['nome'])) {
            $campi[]  = "nome = ?";
            $valori[] = trim($data['nome']);
            $tipi    .= 's';
        }
        if (isset($data['surname'])) {
            $campi[]  = "surname = ?";
            $valori[] = trim($data['surname']);
            $tipi    .= 's';
        }
        if (isset($data['ruolo'])) {
            $nuovo_ruolo = (int)$data['ruolo'];
            if ($nuovo_ruolo !== 0 && $nuovo_ruolo !== 1) {
                echo json_encode(["status" => false, "message" => "Ruolo non valido (0 = Utente, 1 = Admin)."]);
                break;
            }
            $campi[]  = "ruolo = ?";
            $valori[] = $nuovo_ruolo;
            $tipi    .= 'i';
        }
        if (isset($data['password'])) {
            $campi[]  = "password = ?";
            $valori[] = password_hash(trim($data['password']), PASSWORD_DEFAULT);
            $tipi    .= 's';
        }

        if (empty($campi)) {
            http_response_code(400);
            echo json_encode(["status" => false, "message" => "Nessun campo da aggiornare. Campi validi: nome, surname, ruolo, password."]);
            break;
        }

        // Costruisci la query UPDATE dinamica
        $sql = "UPDATE utenti SET " . implode(', ', $campi) . " WHERE email = ?";
        $tipi   .= 's';
        $valori[] = $email;

        $update_stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($update_stmt, $tipi, ...$valori);

        if (mysqli_stmt_execute($update_stmt)) {
            // Rileggi i dati aggiornati
            $read_stmt = mysqli_prepare($conn, "SELECT email, nome, surname, ruolo FROM utenti WHERE email = ?");
            mysqli_stmt_bind_param($read_stmt, "s", $email);
            mysqli_stmt_execute($read_stmt);
            $read_result = mysqli_stmt_get_result($read_stmt);
            $utente_aggiornato = mysqli_fetch_assoc($read_result);

            echo json_encode([
                "status"  => true,
                "message" => "Utente aggiornato.",
                "data"    => $utente_aggiornato
            ]);
        } else {
            echo json_encode(["status" => false, "message" => "Errore durante l'aggiornamento."]);
        }
        break;

    // ==============================================
    // DELETE → Eliminare un utente
    // ==============================================
    // Richiede: { "email": "..." }
    case 'DELETE':
        $email = trim($data['email'] ?? '');

        if (empty($email)) {
            http_response_code(400);
            echo json_encode(["status" => false, "message" => "Email dell'utente obbligatoria."]);
            break;
        }

        // Verifica che l'utente esista
        $check_stmt = mysqli_prepare($conn, "SELECT email FROM utenti WHERE email = ?");
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if (!$check_result || mysqli_num_rows($check_result) === 0) {
            http_response_code(404);
            echo json_encode(["status" => false, "message" => "Utente non trovato."]);
            break;
        }

        $delete_stmt = mysqli_prepare($conn, "DELETE FROM utenti WHERE email = ?");
        mysqli_stmt_bind_param($delete_stmt, "s", $email);

        if (mysqli_stmt_execute($delete_stmt)) {
            echo json_encode([
                "status"  => true,
                "message" => "Utente eliminato.",
                "data"    => ["email" => $email]
            ]);
        } else {
            echo json_encode(["status" => false, "message" => "Errore durante l'eliminazione."]);
        }
        break;

    // ==============================================
    // METODO NON SUPPORTATO
    // ==============================================
    default:
        http_response_code(405); // 405 Method Not Allowed
        echo json_encode([
            "status"  => false,
            "message" => "Metodo HTTP non supportato. Usa: GET, POST, PUT, DELETE."
        ]);
        break;
}

mysqli_close($conn);
?>
