<?php
include "connessione.php";

if (!isset($_COOKIE['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_COOKIE['email'];
$stmtUtente = mysqli_prepare($conn, 'SELECT * FROM utenti WHERE email = ? LIMIT 1');
mysqli_stmt_bind_param($stmtUtente, 's', $email);
mysqli_stmt_execute($stmtUtente);
$resultUtente = mysqli_stmt_get_result($stmtUtente);
$utente = $resultUtente ? mysqli_fetch_assoc($resultUtente) : null;
mysqli_stmt_close($stmtUtente);

if (!$utente || $utente['ruolo'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$users = mysqli_query($conn, "SELECT * FROM utenti");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Pannello Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    </head>
    <body>
        <?php include "header.html"; ?>
        <div class="container mt-5">
            <div id="admin-alert" class="alert d-none" role="alert"></div>
            <h3 class="mb-4">Pannello Admin</h3>
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
                    <tr data-email="<?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?>">
                        <td><?= $u['email'] ?></td>
                        <td class="role-label"><?= $u['ruolo'] === 'admin' ? 'Admin' : 'Utente' ?></td>

                        <td>
                            <form class="d-flex justify-content-center gap-2 js-update-role">
                                <input type="hidden" name="email" value="<?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?>">
                                <select name="ruolo" class="form-select form-select-sm w-auto">
                                    <option value="user" <?= $u['ruolo'] === 'user' ? 'selected' : '' ?>>Utente</option>
                                    <option value="admin" <?= $u['ruolo'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <button class="btn btn-primary btn-sm" type="submit">Cambia</button>
                            </form>
                        </td>
                        <td>
                            <form class="js-delete-user">
                                <input type="hidden" name="email" value="<?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?>">
                                <button class="btn btn-danger btn-sm" type="submit">Cancella</button>
                            </form>
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
            function showAdminAlert(message, type) {
                const box = document.getElementById('admin-alert');
                if (!box) return;

                box.textContent = message;
                box.className = 'alert alert-' + type;
            }

            async function callAdminApi(method, payload) {
                const response = await fetch('api/service.php', {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                let data = null;
                try {
                    data = await response.json();
                } catch (error) {
                    throw new Error('Risposta non valida dal server.');
                }

                if (!response.ok || !data.status) {
                    throw new Error(data.message || 'Operazione non riuscita.');
                }

                return data;
            }

            document.querySelectorAll('.js-update-role').forEach(function(form) {
                form.addEventListener('submit', async function(event) {
                    event.preventDefault();

                    const email = form.querySelector('input[name="email"]').value;
                    const ruolo = form.querySelector('select[name="ruolo"]').value;

                    try {
                        await callAdminApi('PUT', { email: email, ruolo: ruolo });
                        const row = form.closest('tr');
                        if (row) {
                            const roleLabel = row.querySelector('.role-label');
                            if (roleLabel) {
                                roleLabel.textContent = ruolo === 'admin' ? 'Admin' : 'Utente';
                            }
                        }
                        showAdminAlert('Ruolo aggiornato con successo.', 'success');
                    } catch (error) {
                        showAdminAlert(error.message, 'danger');
                    }
                });
            });

            document.querySelectorAll('.js-delete-user').forEach(function(form) {
                form.addEventListener('submit', async function(event) {
                    event.preventDefault();

                    const email = form.querySelector('input[name="email"]').value;
                    if (!confirm('Confermi la cancellazione di questo utente?')) {
                        return;
                    }

                    try {
                        await callAdminApi('DELETE', { email: email });
                        const row = form.closest('tr');
                        if (row) {
                            row.remove();
                        }
                        showAdminAlert('Utente cancellato con successo.', 'success');
                    } catch (error) {
                        showAdminAlert(error.message, 'danger');
                    }
                });
            });
        </script>
    </body>
</html>
