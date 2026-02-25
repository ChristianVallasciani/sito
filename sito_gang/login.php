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

// Rilevamento richiesta AJAX
$isAjax = isset($_SERVER['HTTP_X_AJAX']);
$errore = '';
$successo = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($isAjax) {
        $data = json_decode(file_get_contents('php://input'), true);
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');
        $ricordami = !empty($data['remember']);
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $ricordami = isset($_POST['remember']);
    }
    
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
          if ($isAjax) {
              header('Content-Type: application/json');
              echo json_encode(['success' => true, 'message' => $successo]);
              exit;
          }
          header('Location: profilo.php');
          exit;
        } else {
          $errore = "Errore: password errata.";
        }
      }
    }
    
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $errore]);
        exit;
    }
}
?>

      <div id="msg-container" class="mx-auto my-3" style="max-width: 600px;"></div>

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

                <form id="loginForm" onsubmit="handleLogin(event)">
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
                    <button type="submit" class="btn btn-primary" id="submitBtn">Accedi</button>
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
      function showMsg(msg, type) {
        document.getElementById('msg-container').innerHTML =
          '<div class="alert alert-' + type + ' alert-dismissible fade show">' + msg +
          '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
      }

      function handleLogin(e) {
        e.preventDefault();
        var email = document.getElementById('email').value.trim();
        var password = document.getElementById('password').value.trim();
        var remember = document.getElementById('remember').checked;
        var btn = document.getElementById('submitBtn');

        if (!email || !password) { showMsg('Email e password sono obbligatorie', 'danger'); return; }
        if (password.length < 8) { showMsg('La password deve avere almeno 8 caratteri', 'danger'); return; }

        btn.disabled = true;
        btn.textContent = 'Accesso in corso...';

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'service.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');

        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              var data = JSON.parse(xhr.responseText);
              if (data.status) {
                showMsg(data.message, 'success');
                setTimeout(function() { window.location.href = 'profilo.php'; }, 1500);
              } else {
                showMsg(data.message, 'danger');
                btn.disabled = false;
                btn.textContent = 'Accedi';
              }
            } else {
              showMsg('Errore di connessione.', 'danger');
              btn.disabled = false;
              btn.textContent = 'Accedi';
            }
          }
        };

        xhr.send(JSON.stringify({ action: 'login', email: email, password: password, remember: remember }));
      }
    </script>
  </body>
</html>