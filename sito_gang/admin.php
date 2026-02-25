<?php
include "connessione.php";

if (!isset($_COOKIE['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_COOKIE['email'];
$query = "SELECT * FROM utenti WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$utente = $result ? mysqli_fetch_assoc($result) : null;

if (!$utente || (int)$utente['ruolo'] !== 1) {
    header("Location: index.php");
    exit;
}

// Gestione richieste AJAX
$isAjax = isset($_SERVER['HTTP_X_AJAX']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAjax) {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';

    if ($action === 'cambia' && isset($data['email'], $data['ruolo'])) {
        $email_change = $data['email'];
        $nuovo_ruolo = (int)$data['ruolo'];
        if ($nuovo_ruolo === 0 || $nuovo_ruolo === 1) {
            $update_stmt = mysqli_prepare($conn, "UPDATE utenti SET ruolo = ? WHERE email = ?");
            mysqli_stmt_bind_param($update_stmt, "is", $nuovo_ruolo, $email_change);
            mysqli_stmt_execute($update_stmt);
            echo json_encode(['success' => true, 'message' => 'Ruolo aggiornato.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ruolo non valido.']);
        }
        exit;
    }

    if ($action === 'cancella' && isset($data['email'])) {
        $delete_stmt = mysqli_prepare($conn, "DELETE FROM utenti WHERE email = ?");
        mysqli_stmt_bind_param($delete_stmt, "s", $data['email']);
        mysqli_stmt_execute($delete_stmt);
        echo json_encode(['success' => true, 'message' => 'Utente eliminato.']);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Azione non valida.']);
    exit;
}

if (isset($_POST['cambia']) && isset($_POST['email']) && isset($_POST['ruolo'])) {
    $email_change = $_POST['email'];
    $nuovo_ruolo = (int)$_POST['ruolo'];
    if ($nuovo_ruolo === 0 || $nuovo_ruolo === 1) {
        $update_query = "UPDATE utenti SET ruolo = ? WHERE email = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "is", $nuovo_ruolo, $email_change);
        mysqli_stmt_execute($update_stmt);
    }
}

if (isset($_POST['cancella']) && isset($_POST['email'])) {
    $email_delete = $_POST['email'];
    $delete_query = "DELETE FROM utenti WHERE email = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "s", $email_delete);
    mysqli_stmt_execute($delete_stmt);
}

$users_query = "SELECT * FROM utenti";
$users = mysqli_query($conn, $users_query);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Pannello Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    </head>
    <body>
        <?php include "header.php"; ?>
        <div class="container mt-5">
            <h3 class="mb-4">Pannello Admin</h3>
            <div id="msg-container"></div>
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Email</th>
                        <th>Ruolo</th>
                        <th>Cambia Stato</th>
                        <th>Rimuovi</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($u = mysqli_fetch_assoc($users)) { ?>
                    <tr id="row-<?= htmlspecialchars($u['email']) ?>">
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td class="ruolo-cell"><?= $u['ruolo'] == 1 ? 'Admin' : 'Utente' ?></td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <select class="form-select form-select-sm w-auto ruolo-select">
                                    <option value="0" <?= $u['ruolo'] == 0 ? 'selected' : '' ?>>Utente</option>
                                    <option value="1" <?= $u['ruolo'] == 1 ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <button class="btn btn-primary btn-sm" onclick="cambiaRuolo('<?= htmlspecialchars($u['email']) ?>', this)">Cambia</button>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="cancellaUtente('<?= htmlspecialchars($u['email']) ?>', this)">Cancella</button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
        <div style="position: absolute; bottom: 0; width: 100vw;">
            <?php include "footer.html"; ?>
        </div>
        <script src="https://kit.fontawesome.com/2459a8ac1f.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script>
            function showMsg(msg, type) {
                document.getElementById('msg-container').innerHTML =
                    '<div class="alert alert-' + type + ' alert-dismissible fade show">' + msg +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                setTimeout(() => { const a = document.querySelector('#msg-container .alert'); if(a) a.classList.remove('show'); }, 3000);
            }

            function cambiaRuolo(email, btn) {
                var row = document.getElementById('row-' + CSS.escape(email));
                var nuovoRuolo = parseInt(row.querySelector('.ruolo-select').value);
                btn.disabled = true; btn.textContent = '...';

                var xhr = new XMLHttpRequest();
                xhr.open('PUT', 'service.php', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onreadystatechange = function() {
                  if (xhr.readyState === 4) {
                    btn.disabled = false; btn.textContent = 'Cambia';
                    if (xhr.status === 200) {
                      var data = JSON.parse(xhr.responseText);
                      if (data.status) {
                        row.querySelector('.ruolo-cell').textContent = nuovoRuolo === 1 ? 'Admin' : 'Utente';
                        showMsg(data.message, 'success');
                      } else { showMsg(data.message, 'danger'); }
                    } else { showMsg('Errore di connessione.', 'danger'); }
                  }
                };
                xhr.send(JSON.stringify({ email: email, ruolo: nuovoRuolo }));
            }

            function cancellaUtente(email, btn) {
                if (!confirm('Sei sicuro di voler cancellare ' + email + '?')) return;
                btn.disabled = true; btn.textContent = '...';

                var xhr = new XMLHttpRequest();
                xhr.open('DELETE', 'service.php', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onreadystatechange = function() {
                  if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                      var data = JSON.parse(xhr.responseText);
                      if (data.status) {
                        document.getElementById('row-' + CSS.escape(email)).remove();
                        showMsg(data.message, 'success');
                      } else { showMsg(data.message, 'danger'); btn.disabled = false; btn.textContent = 'Cancella'; }
                    } else { showMsg('Errore di connessione.', 'danger'); btn.disabled = false; btn.textContent = 'Cancella'; }
                  }
                };
                xhr.send(JSON.stringify({ email: email }));
            }
        </script>
    </body>
</html>
