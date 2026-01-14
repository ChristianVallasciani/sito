<?php
session_start();

$loginError = '';
$loginSuccess = '';
$loginForm = ['email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $loginForm['email'] = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($loginForm['email'] === '' || $password === '') {
    $loginError = 'Inserisci email e password.';
  } elseif (!filter_var($loginForm['email'], FILTER_VALIDATE_EMAIL)) {
    $loginError = 'Formato email non valido.';
  } else {
    require_once 'connessione.php';

    $emailEsc = mysqli_real_escape_string($conn, $loginForm['email']);

    // Fase 1: costruzione query SELECT
    $query = "SELECT id, nome, cognome, email, password FROM utenti WHERE email = '$emailEsc' LIMIT 1";
    // Fase 2: esecuzione query
    $risultato = mysqli_query($conn, $query)
      or die('Errore SELECT: ' . mysqli_error($conn) . ' ' . mysqli_errno($conn));

    if (mysqli_num_rows($risultato) === 0) {
      $loginError = 'Non esiste alcun account associato a questa email. Registrati prima di accedere.';
    } else {
      $utente = mysqli_fetch_assoc($risultato);

      if ($utente && password_verify($password, $utente['password'])) {
        $_SESSION['user'] = [
          'id' => $utente['id'],
          'nome' => $utente['nome'],
          'cognome' => $utente['cognome'],
          'email' => $utente['email']
        ];
        $loginSuccess = 'Bentornato ' . htmlspecialchars($utente['nome'] . ' ' . $utente['cognome'], ENT_QUOTES, 'UTF-8') . '!';
      } else {
        $loginError = 'Password errata. Riprova.';
      }
    }

    mysqli_free_result($risultato);
    mysqli_close($conn)
      or die('Errore chiusura: ' . mysqli_error($conn) . ' ' . mysqli_errno($conn));
  }
}
?>
<!doctype html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | PREZZ</title>
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
      <div class="container my-5">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <?php if ($loginError): ?>
              <div class="alert alert-danger text-center" role="alert">
                <?php echo htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php endif; ?>

            <?php if ($loginSuccess): ?>
              <div class="alert alert-success text-center" role="alert">
                <?php echo $loginSuccess; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card shadow-sm border-0">
              <div class="card-body p-4">
                <h3 class="text-center mb-4">Accedi al tuo account</h3>

                <form action="login.php" method="POST">
                  <div class="mb-3">
                    <label for="email" class="form-label">Indirizzo Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="nome@email.com" value="<?php echo htmlspecialchars($loginForm['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
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

  </body>
</html>