<?php
include "connessione.php";

if (!isset($_COOKIE['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_COOKIE['email'];



$query = mysqli_query($conn, "SELECT * FROM utenti WHERE email = '$email'");

$utente = mysqli_fetch_assoc($query);
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
        <?php include "header.html"; ?>
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
                        <div class="list-group mb-3">
                            <?php 
                                // Gestione eliminazione
                                if(isset($_GET['elimina'])) {
                                    $id_elimina = $_GET['elimina'];
                                    mysqli_query($conn, "DELETE FROM indirizzi WHERE id = '$id_elimina' AND utente_email = '$email'");
                                    echo "<div class='alert alert-success'>Indirizzo eliminato con successo.</div>";
                                }
                                
                                $risultato = mysqli_query($conn, "SELECT * FROM indirizzi WHERE utente_email = '$email'");
                                
                                if(mysqli_num_rows($risultato) > 0)
                                {
                                    while($riga = mysqli_fetch_assoc($risultato)) {
                                        echo "<div class='list-group-item d-flex justify-content-between align-items-center'>";
                                        echo "<div>";
                                        echo "<strong>Via {$riga['via']}</strong><br>";
                                        echo "{$riga['citta']}, {$riga['cap']}";
                                        if(!empty($riga['provincia'])) echo " ({$riga['provincia']})";
                                        echo "<br>{$riga['paese']}";
                                        echo "</div>";
                                        echo "<div>";
                                        echo "<a href='indirizzo.php?modifica={$riga['id']}' class='btn btn-sm btn-warning me-1'>Modifica</a>";
                                        echo "<a href='profilo.php?elimina={$riga['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Sei sicuro di voler eliminare questo indirizzo?\")'>Elimina</a>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                }
                                else
                                {
                                    echo "<div class='list-group-item text-center'>Nessun indirizzo aggiunto.</div>";
                                }
                            ?>
                        </div>
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
        <script src="https://kit.fontawesome.com/2459a8ac1f.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>
</html>
