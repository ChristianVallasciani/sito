<?php
$db = mysqli_connect("localhost", "ina5", "Venita3#", "ina5_pietroni");

if (!isset($_COOKIE['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_COOKIE['email'];
$query = mysqli_query($db, "SELECT * FROM users WHERE email = '$email'");
$utente = mysqli_fetch_assoc($query);

if ($utente['tipo'] != 1) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['cambia'])) {
    $email = $_POST['email'];
    $nuovo_tipo = (int)$_POST['tipo'];

    mysqli_query($db, "UPDATE users SET tipo = $nuovo_tipo WHERE email = '$email'");
}

if (isset($_POST['cancella'])) {
    $email = $_POST['email'];
    mysqli_query($db, "DELETE FROM users WHERE email = '$email'");
}

$users = mysqli_query($db, "SELECT * FROM users");
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
                        <td><?= $u['tipo'] == 1 ? 'Admin' : 'Utente' ?></td>

                        <td>
                            <form method="post" class="d-flex justify-content-center gap-2">
                                <input type="hidden" name="email" value="<?= $u['email'] ?>">
                                <select name="tipo" class="form-select form-select-sm w-auto">
                                    <option value="0">Utente</option>
                                    <option value="1">Admin</option>
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
