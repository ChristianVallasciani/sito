<?php
$registrationError = '';
$registrationSuccess = '';
$formData = [
  'name' => '',
  'surname' => '',
  'email' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $formData['name'] = trim($_POST['name'] ?? '');
  $formData['surname'] = trim($_POST['surname'] ?? '');
  $formData['email'] = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');
  $confirmPassword = trim($_POST['confirm_password'] ?? '');

  if (in_array('', $formData, true) || $password === '' || $confirmPassword === '') {
    $registrationError = 'Compila tutti i campi per continuare.';
  } elseif (!preg_match('/^[A-Za-z]+$/', $formData['name']) || !preg_match('/^[A-Za-z]+$/', $formData['surname'])) {
    $registrationError = 'Nome e cognome possono contenere solo lettere.';
  } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
    $registrationError = 'Inserisci un indirizzo email valido.';
  } elseif ($password !== $confirmPassword) {
    $registrationError = 'Le password non coincidono.';
  } elseif (strlen($password) < 8) {
    $registrationError = 'La password deve contenere almeno 8 caratteri.';
  } else {
    require_once 'connessione.php';

    $nomeEsc = mysqli_real_escape_string($conn, $formData['name']);
    $cognomeEsc = mysqli_real_escape_string($conn, $formData['surname']);
    $emailEsc = mysqli_real_escape_string($conn, $formData['email']);

    // Fase 1: costruzione query SELECT per verificare esistenza email
    $queryCheck = "SELECT id FROM utenti WHERE email = '$emailEsc' LIMIT 1";
    // Fase 2: esecuzione query
    $risultatoCheck = mysqli_query($conn, $queryCheck)
      or die('Errore SELECT: ' . mysqli_error($conn) . ' ' . mysqli_errno($conn));

    if (mysqli_num_rows($risultatoCheck) > 0) {
      $registrationError = 'Esiste gia un account associato a questa email.';
    } else {
      // Fase 1: costruzione query INSERT
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);
      $passwordEsc = mysqli_real_escape_string($conn, $passwordHash);
      $queryInsert = "INSERT INTO utenti (nome, cognome, email, password) VALUES ('$nomeEsc', '$cognomeEsc', '$emailEsc', '$passwordEsc')";
      // Fase 2: invio comando SQL
      mysqli_query($conn, $queryInsert)
        or die('Errore INSERT: ' . mysqli_error($conn) . ' ' . mysqli_errno($conn));

      // Fase 3: valutazione esito
      if (mysqli_affected_rows($conn) === 1) {
        $registrationSuccess = 'Registrazione completata! Ora puoi accedere.';
        $formData = ['name' => '', 'surname' => '', 'email' => ''];
      } else {
        $registrationError = 'Si e verificato un errore durante la registrazione. Riprova piu tardi.';
      }
    }

    mysqli_free_result($risultatoCheck);
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
    <title>Registrazione | PREZZ</title>
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
      <div class="container my-5">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <?php if ($registrationError): ?>
              <div class="alert alert-danger text-center" role="alert">
                <?php echo htmlspecialchars($registrationError, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php endif; ?>

            <?php if ($registrationSuccess): ?>
              <div class="alert alert-success text-center" role="alert">
                <?php echo htmlspecialchars($registrationSuccess, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card shadow-sm border-0">
              <div class="card-body p-4">
                <h3 class="text-center mb-4">Crea il tuo account</h3>

                <form method="POST" action="register.php" onsubmit="return validateRegister()">
                  <div class="mb-3">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Inserisci il tuo nome" value="<?php echo htmlspecialchars($formData['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                  </div>

                  <div class="mb-3">
                    <label for="surname" class="form-label">Cognome</label>
                    <input type="text" class="form-control" id="surname" name="surname" placeholder="Inserisci il tuo cognome" value="<?php echo htmlspecialchars($formData['surname'], ENT_QUOTES, 'UTF-8'); ?>" required>
                  </div>

                  <div class="mb-3">
                    <label for="email" class="form-label">Indirizzo Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="nome@email.com" value="<?php echo htmlspecialchars($formData['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
                  </div>

                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Inserisci la tua password" minlength="8" required>
                  </div>

                  <div class="mb-3">
                    <label for="confirm_password" class="form-label">Conferma la password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Ripeti la password" minlength="8" required>
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

    <script>
      function validateRegister() {
        const name = document.getElementById('name').value.trim();
        const surname = document.getElementById('surname').value.trim();
        const pw1 = document.getElementById('password').value;
        const pw2 = document.getElementById('confirm_password').value;

        const onlyLetters = /^[A-Za-z]+$/.test(name) && /^[A-Za-z]+$/.test(surname);

        if (!onlyLetters) {
          alert('Nome e cognome possono contenere solo lettere.');
          return false;
        }

        if (pw1 !== pw2 || pw1.length < 8) {
          alert('Le password devono coincidere e contenere almeno 8 caratteri.');
          return false;
        }

        return true;
      }
    </script>

  </body>
</html>




