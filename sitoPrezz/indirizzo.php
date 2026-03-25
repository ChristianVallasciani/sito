<!doctype html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aggiungi indirizzo</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body class="bg-light">
    <?php include 'header.html'; ?>

    <main class="container my-5">
      <?php
        include 'connessione.php';

        if (!isset($_COOKIE['email'])) {
          header('Location: login.php');
          exit;
        }

        $email = $_COOKIE['email'];
        $messaggio = '';

        $stmtUtente = mysqli_prepare($conn, 'SELECT id FROM utenti WHERE email = ? LIMIT 1');
        mysqli_stmt_bind_param($stmtUtente, 's', $email);
        mysqli_stmt_execute($stmtUtente);
        $resultUtente = mysqli_stmt_get_result($stmtUtente);
        $utente = $resultUtente ? mysqli_fetch_assoc($resultUtente) : null;
        mysqli_stmt_close($stmtUtente);

        if (!$utente) {
          header('Location: login.php');
          exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $indirizzo = trim($_POST['indirizzo'] ?? '');
          $citta = trim($_POST['citta'] ?? '');
          $cap = trim($_POST['cap'] ?? '');
          $provincia = trim($_POST['provincia'] ?? '');

          if ($indirizzo === '' || $citta === '' || $cap === '' || $provincia === '') {
            $messaggio = "<div class='alert alert-danger'>Compila tutti i campi obbligatori.</div>";
          } else {
            $stmt = mysqli_prepare($conn, 'INSERT INTO indirizzi (utente_id, indirizzo, citta, cap, provincia) VALUES (?, ?, ?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'issss', $utente['id'], $indirizzo, $citta, $cap, $provincia);

            if (mysqli_stmt_execute($stmt)) {
              $messaggio = "<div class='alert alert-success'>Indirizzo salvato correttamente.</div>";
            } else {
              $messaggio = "<div class='alert alert-danger'>Errore durante il salvataggio dell'indirizzo.</div>";
            }

            mysqli_stmt_close($stmt);
          }
        }

        echo $messaggio;
      ?>

      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card shadow-sm border-0">
            <div class="card-body p-4">
              <h3 class="text-center mb-4">Aggiungi indirizzo</h3>
              <form method="POST" action="indirizzo.php">
                <div class="mb-3">
                  <label for="indirizzo" class="form-label">Indirizzo *</label>
                  <input type="text" class="form-control" id="indirizzo" name="indirizzo" required>
                </div>
                <div class="mb-3">
                  <label for="citta" class="form-label">Città *</label>
                  <input type="text" class="form-control" id="citta" name="citta" required>
                </div>
                <div class="row g-3">
                  <div class="col-md-4">
                    <label for="cap" class="form-label">CAP *</label>
                    <input type="text" class="form-control" id="cap" name="cap" required>
                  </div>
                  <div class="col-md-4">
                    <label for="provincia" class="form-label">Provincia</label>
                    <input type="text" class="form-control" id="provincia" name="provincia" required>
                  </div>
                </div>
                <div class="d-grid mt-4">
                  <button type="submit" class="btn btn-primary">Salva indirizzo</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>

    <?php include 'footer.html'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
