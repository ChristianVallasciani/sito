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

    <?php include 'header.php'; ?>
 

    <main>
      <?php
      include "connessione.php";
      $isAjax = isset($_SERVER['HTTP_X_AJAX']);

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($isAjax) {
          $data = json_decode(file_get_contents('php://input'), true);
          $name = trim($data['name'] ?? '');
          $surname = trim($data['surname'] ?? '');
          $email = trim($data['email'] ?? '');
          $password = trim($data['password'] ?? '');
          $confirm_password = trim($data['confirm_password'] ?? '');
        } else {
          $name = trim($_POST['name'] ?? '');
          $surname = trim($_POST['surname'] ?? '');
          $email = trim($_POST['email'] ?? '');
          $password = trim($_POST['password'] ?? '');
          $confirm_password = trim($_POST['confirm_password'] ?? '');
        }

        $errore = '';
        if (empty($name) || empty($surname) || empty($email) || empty($password) || empty($confirm_password)) {
          $errore = 'Devono essere riempiti tutti i campi';
        } elseif (!preg_match("/^[A-Za-z]+$/", $name) || !preg_match("/^[A-Za-z]+$/", $surname)) {
          $errore = 'Nome e cognome devono contenere solo lettere.';
        } elseif ($password !== $confirm_password) {
          $errore = 'Le password non coincidono.';
        } else {
          $check_query = "SELECT * FROM utenti WHERE email = ?";
          $check_stmt = mysqli_prepare($conn, $check_query);
          mysqli_stmt_bind_param($check_stmt, "s", $email);
          mysqli_stmt_execute($check_stmt);
          $check_result = mysqli_stmt_get_result($check_stmt);
          if (mysqli_num_rows($check_result) != 0) {
            $errore = "L'email è già registrata.";
          } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO utenti (nome, surname, email, password, ruolo) VALUES (?, ?, ?, ?, 0)";
            $insert_stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, "ssss", $name, $surname, $email, $password_hash);

            if (mysqli_stmt_execute($insert_stmt)) {
              if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => "Benvenuto $name! Registrazione completata."]);
                exit;
              }
              header('Location: profilo.php');
              exit;
            } else {
              $errore = 'Errore durante la registrazione.';
            }
          }
        }

        if ($isAjax) {
          header('Content-Type: application/json');
          echo json_encode(['success' => false, 'message' => $errore]);
          exit;
        }

        if ($errore) {
          echo "<div class='container mt-3'><div class='alert alert-danger text-center'>" . htmlspecialchars($errore) . "</div></div>";
        }
      }
      ?>

      <div id="msg-container" class="mx-auto my-3" style="max-width: 600px;"></div>

      <div class="container my-5">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card shadow-sm border-0">
              <div class="card-body p-4">
                <h3 class="text-center mb-4">Crea il tuo account</h3>

                <form id="registerForm" onsubmit="handleRegister(event)">
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
                    <input type="password" class="form-control" id="password" name="password" placeholder="Inserisci la tua password" required minlength="8">
                  </div>

                  <div class="mb-3">
                    <label for="confirm_password" class="form-label">Conferma la password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Ripeti la password" required>
                  </div>

                  <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary" id="submitBtn">Registrati</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <?php include 'footer.html'; ?>

    <script>
      function showMsg(msg, type) {
        document.getElementById('msg-container').innerHTML =
          '<div class="alert alert-' + type + ' alert-dismissible fade show">' + msg +
          '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
      }

      function handleRegister(e) {
        e.preventDefault();
        var name = document.getElementById('name').value.trim();
        var surname = document.getElementById('surname').value.trim();
        var email = document.getElementById('email').value.trim();
        var password = document.getElementById('password').value.trim();
        var confirmPassword = document.getElementById('confirm_password').value.trim();
        var btn = document.getElementById('submitBtn');

        if (!name || !surname || !email || !password || !confirmPassword) { showMsg('Tutti i campi sono obbligatori', 'danger'); return; }
        if (!/^[A-Za-z]+$/.test(name) || !/^[A-Za-z]+$/.test(surname)) { showMsg('Nome e cognome devono contenere solo lettere', 'danger'); return; }
        if (password !== confirmPassword) { showMsg('Le password non corrispondono', 'danger'); return; }
        if (password.length < 8) { showMsg('La password deve avere almeno 8 caratteri', 'danger'); return; }

        btn.disabled = true;
        btn.textContent = 'Registrazione in corso...';

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
                btn.textContent = 'Registrati';
              }
            } else {
              showMsg('Errore di connessione.', 'danger');
              btn.disabled = false;
              btn.textContent = 'Registrati';
            }
          }
        };

        xhr.send(JSON.stringify({
          action: 'register',
          name: name,
          surname: surname,
          email: email,
          password: password,
          confirm_password: confirmPassword
        }));
      }
    </script>

  </body>
</html>




