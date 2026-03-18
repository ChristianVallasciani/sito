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
        <div class="signup-container col-12 col-sm-8 col-md-6 col-lg-5">
            <h2 class="text-center mb-4">SIGN UP</h2>
            
            <form id="signup-form" action="registersignup.php" method="POST">
                <div class="mb-3">
                    <label for="nome" class="form-label fw-bold">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Inserisci il tuo nome..." required>
                </div>
                
                <div class="mb-3">
                    <label for="cognome" class="form-label fw-bold">Cognome</label>
                    <input type="text" class="form-control" id="cognome" name="cognome" placeholder="Inserisci il tuo cognome..." required>
                </div>
                
                <div class="mb-3">
                    <label for="cognome" class="form-label fw-bold">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Inserisci un username..." required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Inserisci la tua email..." required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Inserisci una password..." required>
                </div>
                
                <div class="mb-4">
                    <label for="conferma-password" class="form-label fw-bold">Conferma Password</label>
                    <input type="password" class="form-control" id="confermapassword" name="confermapassword" placeholder="Ripeti la password..." required>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="termini" name="termini" required>
                    <label class="form-check-label" for="termini">
                        Accetto i <a href="#">Termini e Condizioni</a>
                    </label>
                </div>
                
                <div class="d-grid gap-2">

                    <div class="mb-3 text-center">
                        <a href="login.php" class="text-decoration-none">Hai già un account? Accedi</a>
                    </div>

                    <button type="submit" class="btn btn-primary">Iscriviti</button>
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