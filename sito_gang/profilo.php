<?php
include "connessione.php";

if (!isset($_COOKIE['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_COOKIE['email'];

$query = "SELECT * FROM utenti WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$utente = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <title>Profilo</title>
    </head>
    <body>
        <?php include "header.php"; ?>
        <div class="row row-cols-1 row-cols-md-3 mt-3 me-0 p-5">
            <div class="m-auto">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Il tuo profilo</h5>
                        <p class="mb-2">
                            <strong>Username:</strong><br>
                            <?php echo $utente['nome']; ?>
                        </p>
                        <p class="mb-2">
                            <strong>Email:</strong><br>
                            <?php echo $utente['email']; ?>
                        </p>
                        <p class="mb-3">
                            <strong>Tipo utente:</strong><br>
                            <?php echo isset($utente['ruolo']) && (int)$utente['ruolo'] === 1 ? 'Admin' : 'Utente'; ?>
                        </p>
                        <h6>Indirizzi associati:</h6>
                        <ul class="list-unstyled">
                            <?php 
                                $addr_query = "SELECT * FROM indirizzi WHERE utente_email = ?";
                                $addr_stmt = mysqli_prepare($conn, $addr_query);
                                mysqli_stmt_bind_param($addr_stmt, "s", $email);
                                mysqli_stmt_execute($addr_stmt);
                                $risultato = mysqli_stmt_get_result($addr_stmt);
                                
                                if(mysqli_num_rows($risultato) > 0)
                                {
                                    while($riga = mysqli_fetch_assoc($risultato)) {
                                        $isPrincipale = isset($riga['principale']) && (int)$riga['principale'] === 1;
                                        echo "<li>";
                                        if($isPrincipale) {
                                            echo "<i class='fa-solid fa-circle-dot' style='color: #28a745; margin-right: 5px;' title='Indirizzo principale'></i>";
                                        }
                                        echo htmlspecialchars("Via {$riga['via']}, {$riga['citta']}, {$riga['paese']}, {$riga['cap']}, Provincia: {$riga['provincia']}");
                                        echo "</li>";
                                    }
                                }
                                else
                                {
                                    echo "<li>Nessun indirizzo aggiunto.</li>";
                                }
                            ?>
                        </ul>
                        <a href="indirizzo.php" class="btn btn-outline-primary btn-sm mb-3">Aggiungi indirizzo</a>
                        <?php 
                            if(isset($utente['ruolo']) && (int)$utente['ruolo'] === 1) 
                            {
                                echo "<a href='admin.php' class='btn btn-primary btn-sm'>Pannello Admin</a>";
                            }
                        ?>
                        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <div style="position: absolute; bottom: 0; width: 100vw;">
            <?php include "footer.html"; ?>
        </div>
        <script src="https://kit.fontawesome.com/be97dda312.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>
</html>
