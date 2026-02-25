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
        $isAjax = isset($_SERVER['HTTP_X_AJAX']);
        $messaggio = '';
        $errore = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          if ($isAjax) {
            $data = json_decode(file_get_contents('php://input'), true);
            $via = trim($data['via'] ?? '');
            $citta = trim($data['citta'] ?? '');
            $cap = trim($data['cap'] ?? '');
            $provincia = trim($data['provincia'] ?? '');
            $paese = trim($data['paese'] ?? '');
          } else {
            $via = trim($_POST['via'] ?? '');
            $citta = trim($_POST['citta'] ?? '');
            $cap = trim($_POST['cap'] ?? '');
            $provincia = trim($_POST['provincia'] ?? '');
            $paese = trim($_POST['paese'] ?? '');
          }

          if ($via === '' || $citta === '' || $cap === '' || $paese === '') {
            $errore = 'Compila tutti i campi obbligatori.';
          } elseif (strlen($via) < 3 || strlen($via) > 100) {
            $errore = 'La via deve avere tra 3 e 100 caratteri.';
          } elseif (strlen($citta) < 2 || strlen($citta) > 50) {
            $errore = 'La città deve avere tra 2 e 50 caratteri.';
          } elseif (!preg_match('/^\d{5}$/', $cap)) {
            $errore = 'Il CAP deve essere composto da 5 cifre.';
          } elseif (strlen($provincia) > 2) {
            $errore = 'La provincia deve essere un codice di 2 caratteri.';
          } elseif (strlen($paese) < 2 || strlen($paese) > 50) {
            $errore = 'Il paese deve avere tra 2 e 50 caratteri.';
          } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO indirizzi (utente_email, via, citta, cap, provincia, paese) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'ssssss', $email, $via, $citta, $cap, $provincia, $paese);

            if (mysqli_stmt_execute($stmt)) {
              $messaggio = 'Indirizzo salvato correttamente.';
            } else {
              $errore = 'Errore durante il salvataggio.';
            }
            mysqli_stmt_close($stmt);
          }

          if ($isAjax) {
            header('Content-Type: application/json');
            if ($errore) {
              echo json_encode(['success' => false, 'message' => $errore]);
            } else {
              echo json_encode(['success' => true, 'message' => $messaggio]);
            }
            exit;
          }

          if ($errore) echo "<div class='alert alert-danger'>" . htmlspecialchars($errore) . "</div>";
          if ($messaggio) echo "<div class='alert alert-success'>" . htmlspecialchars($messaggio) . "</div>";
        }
      ?>

      <div id="msg-container"></div>

      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card shadow-sm border-0">
            <div class="card-body p-4">
              <h3 class="text-center mb-4">Aggiungi indirizzo</h3>
              <form id="addressForm" onsubmit="handleAddress(event)">
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
                  <button type="submit" class="btn btn-primary" id="submitBtn">Salva indirizzo</button>
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
      function showMsg(msg, type) {
        document.getElementById('msg-container').innerHTML =
          '<div class="alert alert-' + type + ' alert-dismissible fade show">' + msg +
          '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
      }

      function handleAddress(e) {
        e.preventDefault();
        var via = document.getElementById('via').value.trim();
        var citta = document.getElementById('citta').value.trim();
        var cap = document.getElementById('cap').value.trim();
        var provincia = document.getElementById('provincia').value.trim();
        var paese = document.getElementById('paese').value.trim();
        var btn = document.getElementById('submitBtn');

        if (!via || !citta || !cap || !paese) { showMsg('Compila tutti i campi obbligatori', 'danger'); return; }
        if (via.length < 3 || via.length > 100) { showMsg('La via deve avere tra 3 e 100 caratteri', 'danger'); return; }
        if (citta.length < 2 || citta.length > 50) { showMsg('La città deve avere tra 2 e 50 caratteri', 'danger'); return; }
        if (!/^\d{5}$/.test(cap)) { showMsg('Il CAP deve contenere esattamente 5 cifre', 'danger'); return; }
        if (provincia.length > 2) { showMsg('La provincia deve essere max 2 caratteri', 'danger'); return; }
        if (paese.length < 2 || paese.length > 50) { showMsg('Il paese deve avere tra 2 e 50 caratteri', 'danger'); return; }

        btn.disabled = true;
        btn.textContent = 'Salvataggio in corso...';

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'service.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4) {
            btn.disabled = false;
            btn.textContent = 'Salva indirizzo';
            if (xhr.status === 200) {
              var data = JSON.parse(xhr.responseText);
              if (data.status) {
                showMsg(data.message, 'success');
                document.getElementById('addressForm').reset();
              } else {
                showMsg(data.message, 'danger');
              }
            } else {
              showMsg('Errore di connessione.', 'danger');
            }
          }
        };
        xhr.send(JSON.stringify({ action: 'aggiungi_indirizzo', via: via, citta: citta, cap: cap, provincia: provincia, paese: paese }));
      }
    </script>
  </body>
</html>
