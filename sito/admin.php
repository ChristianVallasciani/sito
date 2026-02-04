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
                    <tr>
                        <td><?= $u['email'] ?></td>
                        <td><?= $u['ruolo'] == 1 ? 'Admin' : 'Utente' ?></td>

                        <td>
                            <form method="post" class="d-flex justify-content-center gap-2">
                                <input type="hidden" name="email" value="<?= $u['email'] ?>">
                                <select name="ruolo" class="form-select form-select-sm w-auto">
                                    <option value="0" <?= $u['ruolo'] == 0 ? 'selected' : '' ?>>Utente</option>
                                    <option value="1" <?= $u['ruolo'] == 1 ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <button name="cambia" class="btn btn-primary btn-sm">Cambia</button>
                            </form>
                        </td>
                        <td>
                            <form method="post" onsubmit="">
                                <input type="hidden" name="email" value="<?= $u['email'] ?>">
                                <button name="cancella" class="btn btn-danger btn-sm">Cancella</button>
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
    </body>
</html>
