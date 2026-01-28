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
        $indirizzo_modifica = null;
        $titolo = 'Aggiungi indirizzo';

        // Controllo se siamo in modalità modifica
        if (isset($_GET['modifica'])) {
          $id_modifica = $_GET['modifica'];
          $query_modifica = mysqli_query($conn, "SELECT * FROM indirizzi WHERE id = '$id_modifica' AND utente_email = '$email'");
          if ($query_modifica && mysqli_num_rows($query_modifica) > 0) {
            $indirizzo_modifica = mysqli_fetch_assoc($query_modifica);
            $titolo = 'Modifica indirizzo';
          }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $via = trim($_POST['via'] ?? '');
          $citta = trim($_POST['citta'] ?? '');
          $cap = trim($_POST['cap'] ?? '');
          $provincia = trim($_POST['provincia'] ?? '');
          $paese = trim($_POST['paese'] ?? '');
          $id_modifica = $_POST['id_modifica'] ?? '';

          if ($via === '' || $citta === '' || $cap === '' || $paese === '') {
            $messaggio = "<div class='alert alert-danger'>Compila tutti i campi obbligatori.</div>";
          } else {
            // Metodo non sicuro come richiesto
            if (!empty($id_modifica)) {
              // Modifica
              $query = "UPDATE indirizzi SET via='$via', citta='$citta', cap='$cap', provincia='$provincia', paese='$paese' WHERE id='$id_modifica' AND utente_email='$email'";
              if (mysqli_query($conn, $query)) {
                $messaggio = "<div class='alert alert-success'>Indirizzo modificato correttamente. <a href='profilo.php'>Torna al profilo</a></div>";
              } else {
                $messaggio = "<div class='alert alert-danger'>Errore durante la modifica dell'indirizzo.</div>";
              }
            } else {
              // Inserimento
              $query = "INSERT INTO indirizzi (utente_email, via, citta, cap, provincia, paese) VALUES ('$email', '$via', '$citta', '$cap', '$provincia', '$paese')";
              if (mysqli_query($conn, $query)) {
                $messaggio = "<div class='alert alert-success'>Indirizzo salvato correttamente. <a href='profilo.php'>Torna al profilo</a></div>";
              } else {
                $messaggio = "<div class='alert alert-danger'>Errore durante il salvataggio dell'indirizzo.</div>";
              }
            }
          }
        }

        echo $messaggio;
      ?>

      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card shadow-sm border-0">
            <div class="card-body p-4">
              <h3 class="text-center mb-4"><?php echo $titolo; ?></h3>
              <form method="POST" action="indirizzo.php<?php echo isset($_GET['modifica']) ? '?modifica='.$_GET['modifica'] : ''; ?>">
                <?php if ($indirizzo_modifica): ?>
                  <input type="hidden" name="id_modifica" value="<?php echo $indirizzo_modifica['id']; ?>">
                <?php endif; ?>
                <div class="mb-3">
                  <label for="via" class="form-label">Via *</label>
                  <input type="text" class="form-control" id="via" name="via" value="<?php echo $indirizzo_modifica['via'] ?? ''; ?>" required>
                </div>
                <div class="mb-3">
                  <label for="citta" class="form-label">Città *</label>
                  <input type="text" class="form-control" id="citta" name="citta" value="<?php echo $indirizzo_modifica['citta'] ?? ''; ?>" required>
                </div>
                <div class="row g-3">
                  <div class="col-md-4">
                    <label for="cap" class="form-label">CAP *</label>
                    <input type="text" class="form-control" id="cap" name="cap" value="<?php echo $indirizzo_modifica['cap'] ?? ''; ?>" required>
                  </div>
                  <div class="col-md-4">
                    <label for="provincia" class="form-label">Provincia</label>
                    <input type="text" class="form-control" id="provincia" name="provincia" value="<?php echo $indirizzo_modifica['provincia'] ?? ''; ?>">
                  </div>
                  <div class="col-md-4">
                    <label for="paese" class="form-label">Paese *</label>
                    <input type="text" class="form-control" id="paese" name="paese" value="<?php echo $indirizzo_modifica['paese'] ?? ''; ?>" required>
                  </div>
                </div>
                <div class="d-grid mt-4">
                  <button type="submit" class="btn btn-primary"><?php echo $indirizzo_modifica ? 'Aggiorna indirizzo' : 'Salva indirizzo'; ?></button>
                </div>
                <?php if ($indirizzo_modifica): ?>
                  <div class="d-grid mt-2">
                    <a href="profilo.php" class="btn btn-secondary">Annulla</a>
                  </div>
                <?php endif; ?>
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
