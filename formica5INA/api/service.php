<?php
// Header comuni
include('../config.php');
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
// ----------------------
// CONNESSIONE AL DATABASE
// ----------------------
$conn = mysqli_connect($host, $username, $password, $database);
// Controlla eventuali errori di connessione
if (!$conn) {
    http_response_code(500); // codice HTTP 500 = errore server
    //da PHP in JSON
    echo json_encode([
    "status" => false,
    "message" => "Errore connessione database"
    ]);
    exit;
}
// Imposta il charset per evitare problemi con i caratteri speciali
mysqli_set_charset($conn, "utf8mb4");
// Ottieni il metodo HTTP
$method = $_SERVER['REQUEST_METHOD'];
// Leggi il corpo della richiesta
$input = json_decode(file_get_contents("php://input"), true);
switch ($method) {
    case 'GET':
    $result = mysqli_query($conn, "SELECT nome, cognome, username, email FROM utenti");
    $utenti = [];
    while($row = mysqli_fetch_assoc($result)){
    $utenti[] = $row;
    }
    // ----------------------
    // RISPOSTA IN JSON
    // ----------------------
    echo json_encode([
    "status" => true,
    "message" => "Lista utenti",
    "data" => $utenti
    ]);
    break;

    case 'POST':
    if (!empty($input['cognome']) && !empty($input['nome'])) {
    $cognome = mysqli_real_escape_string($conn, $input['cognome']);
    $nome = mysqli_real_escape_string($conn, $input['nome']);
    $username = mysqli_real_escape_string($conn, $input['username'] ?? '');;
    $email = mysqli_real_escape_string($conn, $input['email'] ?? '');
    $result = mysqli_query($conn, "INSERT INTO utenti (nome, cognome, username, email, admin) VALUES ('$nome', '$cognome', '$username', '$email', 0)");
    // ----------------------
    // RISPOSTA IN JSON
    // ----------------------
    if ($result) {
        echo json_encode([
            "status" => true,
            "message" => "Utente aggiunto con successo"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Errore aggiunta utente"
        ]);
    }
    break;

    case 'PUT':
    $cognome = mysqli_real_escape_string($conn, $input['cognome']);
    $nome = mysqli_real_escape_string($conn, $input['nome']);
    $username = mysqli_real_escape_string($conn, $input['username'] ?? '');;
    $email = mysqli_real_escape_string($conn, $input['email'] ?? '');
    // ----------------------
    // QUERY UTENTI
    // ----------------------
    $result = mysqli_query($conn, "UPDATE utenti SET nome = '$nome', cognome = '$cognome', email = '$email' WHERE username = '$username'");
    // ----------------------
    // RISPOSTA IN JSON
    // ----------------------
    if ($result) {
        echo json_encode([
            "status" => true,
            "message" => "Utente aggiornato con successo"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Errore aggiornamento utente"
        ]);
    }
    break;

    case 'DELETE':
    $username = mysqli_real_escape_string($conn, $input['username'] ?? '');
    // ----------------------
    // QUERY UTENTI
    // ----------------------
    $result = mysqli_query($conn, "DELETE FROM utenti WHERE username = '$username'");
    // ----------------------
    // RISPOSTA IN JSON
    // ----------------------
    if ($result) {
        echo json_encode([
            "status" => true,
            "message" => "Utente eliminato con successo"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Errore eliminazione utente"
        ]);
    }
}
}
mysqli_close($conn);
?>