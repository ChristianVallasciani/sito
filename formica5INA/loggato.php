<?php
session_start();

if (!isset($_SESSION['utente_loggato']) || $_SESSION['utente_loggato'] !== true) {
    header("Location: login.php");
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

    <main class="container">
        <div class="login-container col-12 col-sm-8 col-md-6 col-lg-5">
            <h2 class="text-center mb-4">Sei già loggato</h2>
            <p class="text-center mb-4">Ciao, <?php echo isset($_SESSION['nome_utente']) ? htmlspecialchars($_SESSION['nome_utente']) : 'Utente'; ?>! Sei già loggato nel sistema.</p>

            <div class="d-grid gap-2">
                <a href="logout.php" class="btn btn-danger">Logout</a>
                <a href="recapiti.php" class="btn btn-info text-white">Recapiti</a>
            </div>
        </div>
    </main>
    
    <?php include 'footer.html' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>

</body>
</html>