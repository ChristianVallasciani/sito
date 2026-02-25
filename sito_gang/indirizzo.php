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
    <?php include 'header.php'; ?>

    <main class="container my-5">
      <?php
        include 'connessione.php';

        if (!isset($_COOKIE['email'])) {
          header('Location: login.php');
          exit;
        }

        $email = $_COOKIE['email'];
        $messaggio = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $via = trim($_POST['via'] ?? '');
          $citta = trim($_POST['citta'] ?? '');
          $cap = trim($_POST['cap'] ?? '');
          $provincia = trim($_POST['provincia'] ?? '');
          $paese = trim($_POST['paese'] ?? '');

          // Validazione campi obbligatori
          if ($via === '' || $citta === '' || $cap === '' || $paese === '') {
            $messaggio = "<div class='alert alert-danger'>Compila tutti i campi obbligatori.</div>";
          } 
          // Validazione lunghezza via
          else if (strlen($via) < 3 || strlen($via) > 100) {
            $messaggio = "<div class='alert alert-danger'>La via deve avere tra 3 e 100 caratteri.</div>";
          }
          // Validazione lunghezza città
          else if (strlen($citta) < 2 || strlen($citta) > 50) {
            $messaggio = "<div class='alert alert-danger'>La città deve avere tra 2 e 50 caratteri.</div>";
          }
          // Validazione formato CAP (5 cifre)
          else if (!preg_match('/^\d{5}$/', $cap)) {
            $messaggio = "<div class='alert alert-danger'>Il CAP deve essere composto da 5 cifre.</div>";
          }
          // Validazione provincia
          else if (strlen($provincia) > 2) {
            $messaggio = "<div class='alert alert-danger'>La provincia deve essere un codice di 2 caratteri.</div>";
          }
          // Validazione paese
          else if (strlen($paese) < 2 || strlen($paese) > 50) {
            $messaggio = "<div class='alert alert-danger'>Il paese deve avere tra 2 e 50 caratteri.</div>";
          }
          // Se tutto è valido, salva nel database
          else {
            $stmt = mysqli_prepare($conn, "INSERT INTO indirizzi (utente_email, via, citta, cap, provincia, paese) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'ssssss', $email, $via, $citta, $cap, $provincia, $paese);

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
              <form method="POST" action="indirizzo.php" onsubmit="return validateForm()">
                <div class="mb-3">
                  <label for="via" class="form-label">Via *</label>
                  <input type="text" class="form-control" id="via" name="via" required minlength="3" maxlength="100" placeholder="Es: Via Roma 10">
                </div>
                <div class="mb-3">
                  <label for="citta" class="form-label">Città *</label>
                  <input type="text" class="form-control" id="citta" name="citta" required minlength="2" maxlength="50" placeholder="Es: Milano">
                </div>
                <div class="row g-3">
                  <div class="col-md-4">
                    <label for="cap" class="form-label">CAP *</label>
                    <input type="text" class="form-control" id="cap" name="cap" required pattern="\d{5}" placeholder="Es: 20100" title="Inserisci 5 cifre">
                  </div>
                  <div class="col-md-4">
                    <label for="provincia" class="form-label">Provincia</label>
                    <input type="text" class="form-control" id="provincia" name="provincia" maxlength="2" placeholder="Es: MI" pattern="[A-Za-z]{0,2}">
                  </div>
                  <div class="col-md-4">
                    <label for="paese" class="form-label">Paese *</label>
                    <input type="text" class="form-control" id="paese" name="paese" required minlength="2" maxlength="50" placeholder="Es: Italia">
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
    <script>
      function validateForm() {
        const via = document.getElementById('via').value.trim();
        const citta = document.getElementById('citta').value.trim();
        const cap = document.getElementById('cap').value.trim();
        const provincia = document.getElementById('provincia').value.trim();
        const paese = document.getElementById('paese').value.trim();

        // Validazione via
        if (via.length < 3 || via.length > 100) {
          alert('La via deve avere tra 3 e 100 caratteri');
          return false;
        }

        // Validazione città
        if (citta.length < 2 || citta.length > 50) {
          alert('La città deve avere tra 2 e 50 caratteri');
          return false;
        }

        // Validazione CAP (5 cifre)
        if (!/^\d{5}$/.test(cap)) {
          alert('Il CAP deve contenere esattamente 5 cifre numeriche');
          return false;
        }

        // Validazione provincia (max 2 caratteri)
        if (provincia.length > 2) {
          alert('La provincia deve essere un codice di massimo 2 caratteri');
          return false;
        }

        // Validazione paese
        if (paese.length < 2 || paese.length > 50) {
          alert('Il paese deve avere tra 2 e 50 caratteri');
          return false;
        }

        return true;
      }
    </script>
  </body>
</html>
