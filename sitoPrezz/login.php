<?php
if (isset($_COOKIE['email']) && $_COOKIE['email'] !== '') {
  header('Location: profilo.php');
  exit;
}
?>

<!doctype html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shop PREZZ</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        main {
            flex: 1 0 auto;
        }
    </style>
  </head>

  <body class="bg-light">

    <?php include 'header.html'; ?>

    <main>
      <div id="error-notification" class="alert alert-danger fade show" role="alert"
        style="display:none; position: fixed; bottom: 20px; right: 20px; z-index: 1050; min-width: 250px;">
        <span id="error-message"></span>
      </div>

      <div class="container my-5">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card shadow-sm border-0">
              <div class="card-body p-4">
                <h3 class="text-center mb-4">Accedi al tuo account</h3>

                <form id="login-form" action="registerlogin.php" method="POST">
                  <div class="mb-3">
                    <label for="email" class="form-label">Indirizzo Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="nome@email.com" required>
                  </div>

                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Inserisci la tua password" required>
                  </div>

                  <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ricordami</label>
                  </div>

                  <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Accedi</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mb-4">
        <p>Non hai un account?</p>
        <a href="register.php">Registrati</a>
      </div>
    </main>

    <?php include 'footer.html'; ?>

    <script src="assets/js/auth.js"></script>
  </body>
</html>