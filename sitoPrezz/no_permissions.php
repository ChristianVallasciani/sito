<!doctype html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Accesso Negato | PREZZ</title>
        <link rel="icon" href="assets/img/logo.png" type="image/png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                min-height: 100vh;
            }
            .blur-circle {
                width: 220px;
                height: 220px;
                background: rgba(13, 110, 253, 0.15);
                filter: blur(60px);
                position: absolute;
                z-index: 0;
            }
        </style>
    </head>

    <body class="bg-light d-flex flex-column position-relative overflow-hidden">
        <div class="blur-circle top-0 start-0 translate-middle"></div>
        <div class="blur-circle bottom-0 end-0 translate-middle"></div>

        <?php include 'header.html'; ?>

        <main class="flex-grow-1 d-flex align-items-center py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm text-center p-4 position-relative">
                            <div class="mb-3 text-warning fs-1">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <h1 class="h3 mb-3">Accesso negato</h1>
                            <p class="text-secondary mb-4">Non hai i permessi necessari per visualizzare questa sezione. Contatta un amministratore se ritieni che si tratti di un errore.</p>
                            <div class="d-grid gap-3">
                                <a href="index.php" class="btn btn-primary">Torna alla home</a>
                                <a href="login.php" class="btn btn-outline-secondary">Accedi con un altro account</a>
                            </div>
                            <div class="alert alert-warning mt-4 mb-0" role="alert">
                                Richiesta permessi avanzati in corso: riceverai una conferma via email.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php include 'footer.html'; ?>
    </body>
</html>
