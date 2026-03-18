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
<?php
include "connessione.php";


  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $email = trim($_POST['email'] ?? '');
      $password = trim($_POST['password'] ?? '');
      $ricordami = isset($_POST['remember']);
   
      $query = "SELECT * FROM utenti WHERE email='$email'";
      $result = mysqli_query($conn, $query);
      if (!$result || mysqli_num_rows($result) == 0) {
        echo "<div class='alert alert-danger mx-auto my-3 fixed-top' style='max-width: 600px;'>Errore: utente non trovato.</div>";
        exit;
    }
    $utenteTrovato = mysqli_fetch_assoc($result);

    if (password_verify($password, $utenteTrovato['password'])) {

    $cookieDuration = $ricordami ? time() + 86400 * 30 : 0; // session cookie se non ricordami
    setcookie("email", $email, $cookieDuration, "/");

        echo "<div class='alert alert-success mx-auto my-3 fixed-top' style='max-width: 600px;'>Benvenuto {$utenteTrovato['nome']}! Login effettuato correttamente.</div>";
    } else {
        echo "<div class='alert alert-danger mx-auto my-3 fixed-top' style='max-width: 600px;'>Errore: password errata.</div>";
  }
}
?>

      <div class="container my-5">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card shadow-sm border-0">
              <div class="card-body p-4">
                <h3 class="text-center mb-4">Accedi al tuo account</h3>

                <form action="login.php" method="POST" onsubmit="return chekregister()">
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

    <script>
      function chekregister(){
        const email = document.getElementById("email").value;
        const pwd = document.getElementById("password").value;

      }
    </script>
  </body>
</html>