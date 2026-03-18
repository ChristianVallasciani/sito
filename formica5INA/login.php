<?php
session_start();

if(isset($_SESSION['utente_loggato']) && $_SESSION['utente_loggato'] === true){
    header("Location: loggato.php");
    exit;
}
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
        <div class="login-container col-12 col-sm-8 col-md-6 col-lg-5">
            <h2 class="text-center mb-4">LOG IN</h2>
            
            <form id="login-form" action="registerlogin.php" method="POST">

                <div class="mb-3">
                    <label for="text" class="form-label fw-bold">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Inserisci il tuo username..." required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Inserisci la password..." required>
                </div>

                <div class="d-grid gap-2">

                    <div class="mb-3 text-center">
                        <a href="signup.php" class="text-decoration-none">Non hai un account? Registrati</a>
                    </div>

                    <button type="submit" class="btn btn-primary">Entra</button>
                    <a href="index.php" class="btn btn-secondary" role="button">Annulla</a>

                </div>
            </form>
        </div>
    </main>
    
    <?php include 'footer.html' ?>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    

    <script src="assets/js/script.js"></script>

</body>
</html>