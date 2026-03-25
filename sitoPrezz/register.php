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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <h3 class="text-center mb-4">Crea il tuo account</h3>

                <form id="signup-form" method="POST" action="registersignup.php">
                  <div class="mb-3">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Inserisci il tuo nome" required>
                  </div>

                  <div class="mb-3">
                    <label for="surname" class="form-label">Cognome</label>
                    <input type="text" class="form-control" id="surname" name="surname" placeholder="Inserisci il tuo cognome" required>
                  </div>

                  <div class="mb-3">
                    <label for="email" class="form-label">Indirizzo Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="nome@email.com" required>
                  </div>

                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Inserisci la tua password" required>
                  </div>

                  <div class="mb-3">
                    <label for="confirm_password" class="form-label">Conferma la password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Ripeti la password" required>
                  </div>

                  <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="termini" name="termini" required>
                    <label class="form-check-label" for="termini">
                      Accetto i <a href="#">Termini e Condizioni</a>
                    </label>
                  </div>

                  <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary">Registrati</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <?php include 'footer.html'; ?>

    <script src="assets/js/auth.js"></script>

  </body>
</html>




