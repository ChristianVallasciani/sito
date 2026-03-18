<?php
session_start();
include('config.php');

if(!isset($_SESSION['utente_loggato']) && $_SESSION['utente_loggato'] === true){
    header("Location: loggato.php");
    exit;
}

$conn = mysqli_connect($host, $username, $password, $database) OR die("Errore connessione: " . mysqli_error($conn));

$username = $_SESSION['username'];

if(empty($username)){
    header("Location: recapiti.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $indirizzo = trim($_POST['indirizzo']);
    $citta = trim($_POST['citta']);
    $cap = trim($_POST['cap']);
    $prioritaria = isset($_POST['prioritaria']) ? $_POST['prioritaria'] : '';

    if(isset($_POST['prioritaria'])){
        $reset = "UPDATE recapiti SET prioritaria = 0 WHERE username = '$username'";
        mysqli_query($conn, $reset);
        $prioritaria = 1;
    } else {
        $prioritaria = 0;
    }


    $check = "SELECT COUNT(*) as tot FROM recapiti WHERE username = '$username'";
    $res = mysqli_query($conn, $check);
    $row = mysqli_fetch_assoc($res);

    if($row['tot'] == 0){
        $prioritaria = 1;
    }


    $query = "INSERT INTO recapiti (username, indirizzo, citta, cap, prioritaria) VALUES ('$username', '$indirizzo', '$citta', $cap, $prioritaria)";
    mysqli_query($conn, $query) OR die("Errore update: " . mysqli_error($conn));

    mysqli_close($conn);
    header("Location: recapiti.php");
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.html' ?>
    
    <div id="error-notification" class="alert alert-danger fade show" role="alert" 
        style="display:none; position: fixed; bottom: 20px; right: 20px; z-index: 1050; min-width: 250px;">
        <span id="error-message"></span>
    </div>


    <main class="container">
        <div class="signup-container col-12 col-sm-8 col-md-6 col-lg-5">
            <h2 class="text-center mb-4">NUOVO RECAPITO</h2>
            
            <form id="signup-form" action="aggiungi_rec.php" method="POST">
                <div class="mb-3">
                    <label for="indirizzo" class="form-label fw-bold">Indirizzo</label>
                    <input type="text" class="form-control" id="indirizzo" name="indirizzo" placeholder="Inserisci il nuovo indirizzo..." required>
                </div>
                
                <div class="mb-3">
                    <label for="citta" class="form-label fw-bold">Città</label>
                    <input type="text" class="form-control" id="citta" name="citta" placeholder="Inserisci la città" required>
                </div>
                
                <div class="mb-3">
                    <label for="cap" class="form-label fw-bold">CAP</label>
                    <input type="text" class="form-control" id="cap" name="cap" placeholder="Inserisci il CAP..." required>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="prioritaria" name="prioritaria">
                    <label class="form-check-label" for="prioritaria">
                        Imposta come scelta prioritaria
                    </label>
                </div>
                
                <div class="d-grid gap-2">

                    <button type="submit" class="btn btn-primary">Aggiungi Recapito</button>
                    <a href="recapiti.php" class="btn btn-secondary" role="button">Annulla</a>

                </div>
            </form>
        </div>
    </main>
    
    <?php include 'footer.html' ?>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    

    <script src="assets/js/script.js"></script>

</body>
</html>