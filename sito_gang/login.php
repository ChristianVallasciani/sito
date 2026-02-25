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

    <?php include 'header.php'; ?>

    <main>
<?php
include "connessione.php";


$errore = '';
$successo = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $ricordami = isset($_POST['remember']);
    
    if (empty($email) || empty($password)) {
      $errore = "Errore: email e password obbligatorie.";
    } else {
      $query = "SELECT * FROM utenti WHERE email=?";
      $stmt = mysqli_prepare($conn, $query);
      mysqli_stmt_bind_param($stmt, "s", $email);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      
      if (!$result || mysqli_num_rows($result) == 0) {
        $errore = "Errore: utente non trovato.";
      } else {
        $utenteTrovato = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $utenteTrovato['password'])) {
          $cookieDuration = $ricordami ? time() + 86400 * 30 : 0;
          setcookie("email", $email, $cookieDuration, "/");
          $successo = "Benvenuto {$utenteTrovato['nome']}! Login effettuato correttamente.";
          header('Location: profilo.php');
          exit;
        } else {
          $errore = "Errore: password errata.";
        }
      }
    }
}
?>

      <?php if($errore): ?>
        <div class='alert alert-danger mx-auto my-3' style='max-width: 600px;'><?php echo htmlspecialchars($errore); ?></div>
      <?php endif; ?>
      
      <?php if($successo): ?>
        <div class='alert alert-success mx-auto my-3' style='max-width: 600px;'><?php echo htmlspecialchars($successo); ?></div>
      <?php endif; ?>

      <div class="container my-5">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card shadow-sm border-0">
              <div class="card-body p-4">
                <h3 class="text-center mb-4">Accedi al tuo account</h3>

                <form action="login.php" method="POST" onsubmit="return checkLogin()">
                  <div class="mb-3">
                    <label for="email" class="form-label">Indirizzo Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="nome@email.com" required>
                  </div>

                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Inserisci la tua password" required minlength="8">
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
      function checkLogin(){
        const email = document.getElementById("email").value;
        const pwd = document.getElementById("password").value;
        
        if (!email || !pwd) {
          alert("Email e password sono obbligatorie");
          return false;
        }
        
        if (pwd.length < 8) {
          alert("La password deve avere almeno 8 caratteri");
          return false;
        }
        
        return true;
      }
    </script>
  </body>
</html>