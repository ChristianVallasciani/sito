<!doctype html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shop PREZZ</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  </head>

  <body>
    <?php include 'header.php'; ?>
      <!-- SEZIONE CARRELLO -->
      <div class="container-fluid d-flex justify-content-center justify-content-md-end py-3">
        <div class="position-relative">
          <a href="index.php">
            <img src="assets/img/carrello.png" class="img-fluid rounded" alt="Carrello" style="max-height: 60px;">
          </a>
          <span id="number" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            0
          </span>
        </div>
      </div>
    <?php include 'card.html'; ?>
    
    <?php include 'footer.html'; ?>

    <script>
      let number = 0;
      function CartNumber() {
        document.getElementById("number").textContent = ++number;
      }
    </script>
    <script src="https://kit.fontawesome.com/be97dda312.js" crossorigin="anonymous"></script>
  </body>
</html>
