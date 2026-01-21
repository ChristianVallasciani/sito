<?php
include "connessione.php";

if (!isset($_COOKIE['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_COOKIE['email'];
$query = mysqli_query($conn, "SELECT * FROM utenti WHERE email = '$email'");
$utente = $query ? mysqli_fetch_assoc($query) : null;

if (!$utente || (int)$utente['ruolo'] !== 1) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['cambia'])) {
    $email = $_POST['email'];
    $nuovo_ruolo = (int)$_POST['ruolo'];

    mysqli_query($conn, "UPDATE utenti SET ruolo = $nuovo_ruolo WHERE email = '$email'");
}

if (isset($_POST['cancella'])) {
    $email = $_POST['email'];
    mysqli_query($conn, "DELETE FROM utenti WHERE email = '$email'");
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
