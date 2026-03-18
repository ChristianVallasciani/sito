<?php
session_start();
include('config.php');

if(!isset($_SESSION['utente_loggato']) || $_SESSION['utente_loggato'] !== true){
    header("Location: loggato.php");
    exit;
}

$conn = mysqli_connect($host, $username, $password, $database) OR die("Errore connessione: " . mysqli_error($conn));

$username = $_SESSION['username'];
$indirizzoVecchio = mysqli_real_escape_string($conn, $_GET['indirizzo'] ?? '');

if(empty($username)){
    header("Location: recapiti.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $indirizzoNuovo = trim($_POST['indirizzo']);
    $citta = trim($_POST['citta']);
    $cap = trim($_POST['cap']);
    $prioritaria = isset($_POST['prioritaria']) ? 1 : 0;

    if($prioritaria == 1){
        $reset = "UPDATE recapiti SET prioritaria = 0 WHERE username = '$username'";
        mysqli_query($conn, $reset);
    }

    $query = "UPDATE recapiti SET indirizzo='$indirizzoNuovo', citta='$citta', cap='$cap', prioritaria='$prioritaria' WHERE username='$username' AND indirizzo='$indirizzoVecchio'";
    mysqli_query($conn, $query) OR die("Errore update: " . mysqli_error($conn));

    mysqli_close($conn);
    header("Location: recapiti.php");
    exit;
}

$query = "SELECT * FROM recapiti WHERE username='$username' AND indirizzo='$indirizzoVecchio'";
$result = mysqli_query($conn, $query) OR die("Errore select: " . mysqli_error($conn));
$recapito = mysqli_fetch_assoc($result);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Modifica Recapito</title>
</head>
<body>
    <?php include 'header.html' ?>
<div class="container mt-5">
    <h2>Modifica Recapito</h2>
    <form method="post">
        <div class="mb-3">
            <label>Indirizzo</label>
            <input type="text" name="indirizzo" class="form-control" value="<?= htmlspecialchars($recapito['indirizzo']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Città</label>
            <input type="text" name="citta" class="form-control" value="<?= htmlspecialchars($recapito['citta']) ?>" required>
        </div>
        <div class="mb-3">
            <label>CAP</label>
            <input type="text" name="cap" class="form-control" value="<?= htmlspecialchars($recapito['cap']) ?>" required>
        </div>

        <input class="form-check-input" type="checkbox" id="prioritaria" name="prioritaria"
        <?= ($recapito['prioritaria'] == 1) ? 'checked' : '' ?>>
        <label class="form-check-label" for="prioritaria">
            Imposta come scelta prioritaria
        </label>
        <br>
        <br>
        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
        <a href="recapiti.php" class="btn btn-secondary">Annulla</a>
    </form>
</div>
<div style="position: absolute; bottom: 0; width: 100vw;">
    <?php include 'footer.html' ?>
</div>
</body>
</html>
