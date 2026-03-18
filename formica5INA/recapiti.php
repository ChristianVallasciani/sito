<?php
session_start();
include('config.php');

if (!isset($_SESSION['utente_loggato']) || $_SESSION['utente_loggato'] !== true) {
    header("Location: loggato.php");
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
    <title>Gestione Recapiti</title>
</head>

<body>
    <?php include 'header.html' ?>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Gestione Recapiti</h2>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Indirizzo</th>
                    <th>Città</th>
                    <th>CAP</th>
                    <th>Scelta Prioritaria</th>
                </tr>
            </thead>
            <tbody>
                    <?php
                    $user = $_SESSION['username'];
                    $query = "SELECT * FROM recapiti WHERE username = '$user'";
                    $result = mysqli_query($conn, $query) OR die("Errore select: " . mysqli_error($conn));
                    if (mysqli_num_rows($result) == 0) {
                        echo "<tr><td colspan='4' class='text-center'>Non hai ancora impostato recapiti</td></tr>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr data-indirizzo='{$row['indirizzo']}'>
                        <td>{$row['indirizzo']}</td>
                        <td>{$row['citta']}</td>
                        <td>{$row['cap']}</td>
                        <td>" . ($row['prioritaria'] ? 'Sì' : '') . "</td>
                        <td>
                            <a href='modifica_rec.php?indirizzo={$row['indirizzo']}' class='btn btn-primary btn-sm'>Modifica</a>
                            <button onclick=\"eliminaRecapito('{$row['indirizzo']}')\" class='btn btn-danger btn-sm'>Elimina</button>
                        </td>
                    </tr>";
                        }
                    }


                    mysqli_close($conn);
                    ?>
            </tbody>
        </table>
        <div class="mt-4 text-center">
            <a href="aggiungi_rec.php" class="btn btn-secondary">Aggiungi Recapito</a>
        </div>
    </div>

    <div style="position: absolute; bottom: 0; width: 100vw;">
        <?php include 'footer.html' ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

<script>
    function eliminaRecapito(indirizzo) {
        if (!confirm('Sei sicuro di voler cancellare questo recapito?')) {
            return;
        }

        var richiesta = new XMLHttpRequest();

        richiesta.open("GET", "elimina_rec.php?indirizzo=" + encodeURIComponent(indirizzo), true);

        richiesta.onreadystatechange = function () {
            if (richiesta.readyState == 4 && richiesta.status == 200) {
                if (richiesta.responseText === 'success') {
                    var row = document.querySelector(`tr[data-indirizzo="${indirizzo}"]`);
                    if (row) {
                        row.remove();
                    }
                } else {
                    alert('Errore durante la cancellazione del recapito.');
                }
            }
        };

        richiesta.send(null);
    }
</script>

</html>