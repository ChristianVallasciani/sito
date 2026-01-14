<!doctype html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Area Amministratore | PREZZ</title>
        <link rel="icon" href="assets/img/logo.png" type="image/png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                min-height: 100vh;
            }
            .placeholder-card {
                border-top: 4px solid #0d6efd;
            }
        </style>
    </head>

    <body class="bg-light d-flex flex-column">
        <?php include 'header.html'; ?>

        <main class="flex-grow-1 py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="badge text-bg-secondary px-3 py-2 text-uppercase">Pannello in arrivo</span>
                    <h1 class="display-6 fw-bold mt-3">Area amministratore</h1>
                    <p class="text-secondary">Questa pagina è un'anteprima grafica: le azioni verranno abilitate in seguito.</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-6 col-xl-3">
                        <div class="card shadow-sm border-0 h-100 placeholder-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary-subtle text-primary p-3 me-3">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                    <div>
                                        <p class="text-uppercase text-secondary mb-0 small">Sezione</p>
                                        <h5 class="mb-0">Gestione utenti</h5>
                                    </div>
                                </div>
                                <p class="text-secondary">Controlla profili, ruoli e accessi del team.</p>
                                <button class="btn btn-outline-primary w-100" disabled>Funzione non attiva</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card shadow-sm border-0 h-100 placeholder-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-success-subtle text-success p-3 me-3">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>
                                    <div>
                                        <p class="text-uppercase text-secondary mb-0 small">Sezione</p>
                                        <h5 class="mb-0">Gestione contenuti</h5>
                                    </div>
                                </div>
                                <p class="text-secondary">Bozze e pubblicazioni saranno gestite da qui.</p>
                                <button class="btn btn-outline-success w-100" disabled>Funzione non attiva</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card shadow-sm border-0 h-100 placeholder-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-warning-subtle text-warning p-3 me-3">
                                        <i class="fa-solid fa-chart-line"></i>
                                    </div>
                                    <div>
                                        <p class="text-uppercase text-secondary mb-0 small">Sezione</p>
                                        <h5 class="mb-0">Statistiche</h5>
                                    </div>
                                </div>
                                <p class="text-secondary">Grafici e report verranno visualizzati qui.</p>
                                <button class="btn btn-outline-warning w-100" disabled>Funzione non attiva</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card shadow-sm border-0 h-100 placeholder-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-danger-subtle text-danger p-3 me-3">
                                        <i class="fa-solid fa-gear"></i>
                                    </div>
                                    <div>
                                        <p class="text-uppercase text-secondary mb-0 small">Sezione</p>
                                        <h5 class="mb-0">Impostazioni</h5>
                                    </div>
                                </div>
                                <p class="text-secondary">Preferenze e sicurezza saranno configurate qui.</p>
                                <button class="btn btn-outline-danger w-100" disabled>Funzione non attiva</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php include 'footer.html'; ?>
    </body>
</html>
