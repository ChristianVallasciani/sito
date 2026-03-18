<?php
session_start();
include('config.php');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
    exit;
}

$conn = mysqli_connect($host, $username, $password, $database) OR die("Errore connessione: " . mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Gestione Utenti</title>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Gestione Utenti</h2>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM utenti";
                $result = mysqli_query($conn, $query) OR die("Errore select: " . mysqli_error($conn));

                while ($row = mysqli_fetch_assoc($result)) {
                    $Admin = $row['admin'] == 1;
                    echo "<tr data-username='{$row['username']}'>
                    <td>{$row['nome']}</td>
                    <td>{$row['cognome']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['email']}</td>
                    <td>" . ($Admin ? 'Sì' : 'No') . "</td>
                    <td>";

                    if (!$Admin) {
                        echo "<a href='modifica.php?username={$row['username']}' class='btn btn-primary btn-sm'>Modifica</a>
                        <button onclick=\"eliminaUtente('{$row['username']}')\" class='btn btn-danger btn-sm'>Elimina</button>";
                    } else {
                        echo "<a href='modifica.php?username={$row['username']}' class='btn btn-primary btn-sm'>Modifica</a>";
                    }

                    echo "</td>
                </tr>";
                }

                mysqli_close($conn);
                ?>
            </tbody>
        </table>
        <div class="mt-4 text-center">
            <a href="index.php" class="btn btn-secondary">Torna alla Home</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    function eliminaUtente(username) {
        if (!confirm('Sei sicuro di voler cancellare questo utente?')) {
            return;
        }

        var richiesta = new XMLHttpRequest();
        richiesta.open("GET", "elimina.php?username=" + encodeURIComponent(username), true);

        richiesta.onreadystatechange = function () {
            if (richiesta.readyState === 4 && richiesta.status === 200) {
                if (richiesta.responseText === 'success') {
                    var safeUsername = CSS.escape(username);
                    var row = document.querySelector(`tr[data-username="${safeUsername}"]`);
                    if (row) {
                        row.remove();
                    }
                } else {
                    alert('Errore durante la cancellazione dell’utente.');
                }
            }
        };

        richiesta.send(null);
    }
</script>

</html>