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
      <?php
      include "connessione.php";
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $surname = trim($_POST['surname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');
        
        if (empty($name) || empty($surname) || empty($email) || empty($password) || empty($confirm_password)) {
          echo "<div class='container mt-3'><div class='alert alert-danger text-center'>Devono essere riempiti tutti i campi</div></div>";
          exit;
        }

        if(!preg_match("/^[A-Za-z]+$/", $name) || !preg_match("/^[A-Za-z]+$/", $surname)) {
          echo "<div class='container mt-3'><div class='alert alert-danger text-center'>Nome e cognome devono contenere solo lettere.</div></div>";
          exit;
        }

        if ($password !== $confirm_password) {
          echo "<div class='container mt-3'><div class='alert alert-danger text-center'>Le password non coincidono.</div></div>";
          exit;
        }
        
        $check_query = "SELECT * FROM utenti WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_query);
        if (mysqli_num_rows($check_result) != 0) {
            echo "<div class='alert alert-danger mx-auto my-3 fixed-top' style='max-width: 600px;'>L'email è già registrata.</div>";
        	exit;
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $insert_query = "INSERT INTO utenti (nome, surname, email, password, ruolo) VALUES ('$name', '$surname', '$email', '$password_hash', 0)";
        mysqli_query($conn, $insert_query);

        echo "<div class='alert alert-success mx-auto my-3 fixed-top' style='max-width: 600px;'>Benvenuto $name! I tuoi dati sono stati salvati correttamente!</div>";
    
         mysqli_close($conn);

      }
      ?>

      <div class="container my-5">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card shadow-sm border-0">
              <div class="card-body p-4">
                <h3 class="text-center mb-4">Crea il tuo account</h3>

                <form method="POST" action="register.php" onsubmit="return checkregister()">
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
      function checkregister(){
        const pw1 = document.getElementById("password").value;
        const pw2 = document.getElementById("confirm_password").value;
        
        const name = document.getElementById("name").value;
        const surname = document.getElementById("surname").value;

        const soloLettere = /^[A-Za-z]+$/.test(name) && /^[A-Za-z]+$/.test(surname);

        if (pw1 != pw2 || pw1.length < 8 ){
          alert("Le password non corrispondono o caratteri non sufficienti");
          return false;
        }
        
        if (!soloLettere) {
          alert("Nome e cognome devono contenere solo lettere.");
          return false;
        }
        
        return true;
      }
    </script>

  </body>
</html>




