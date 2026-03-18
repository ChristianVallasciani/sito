<?php
session_start();
include('config.php');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
    exit;
}

$conn = mysqli_connect($host, $username, $password, $database) OR die("Errore connessione: " . mysqli_error($conn));

$username = mysqli_real_escape_string($conn, $_GET['username'] ?? '');

if (empty($username)) {
    header("Location: gestisci.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $cognome = trim($_POST['cognome']);
    $email = trim($_POST['email']);
    $new_username = trim($_POST['username']);
    $admin = isset($_POST['admin']) ? 1 : 0;

    $query = "UPDATE utenti SET nome='$nome', cognome='$cognome', username='$new_username', email='$email', admin=$admin WHERE username='$username'";
    mysqli_query($conn, $query) OR die("Errore update: " . mysqli_error($conn));

    if (!empty($_POST['password'])) {
        $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
        $query2 = "UPDATE utenti SET password='$password' WHERE username='$new_username'";
        mysqli_query($conn, $query2) OR die("Errore update password: " . mysqli_error($conn));
    }

    mysqli_close($conn);
    header("Location: gestisci.php");
    exit;
}

$query = "SELECT * FROM utenti WHERE username='$username'";
$result = mysqli_query($conn, $query) OR die("Errore select: " . mysqli_error($conn));
$utente = mysqli_fetch_assoc($result);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Modifica Utente</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Modifica Utente</h2>
        <form method="post">
            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($utente['nome']) ?>"
                    required>
            </div>
            <div class="mb-3">
                <label>Cognome</label>
                <input type="text" name="cognome" class="form-control"
                    value="<?= htmlspecialchars($utente['cognome']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control"
                    value="<?= htmlspecialchars($utente['username']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($utente['email']) ?>"
                    required>
            </div>
            <div class="mb-3">
                <label>Nuova Password (lascia vuoto per mantenere quella attuale)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="admin" class="form-check-input" id="adminCheck" <?= $utente['admin'] ? 'checked disabled' : '' ?>>
                <label class="form-check-label" for="adminCheck">Admin</label>
                <?php if ($utente['admin']): ?>
                    <small class="text-muted">
                        Non puoi togliere i permessi a un admin
                    </small>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Salva Modifiche</button>
            <a href="gestisci.php" class="btn btn-secondary">Annulla</a>
        </form>
    </div>
</body>

</html>