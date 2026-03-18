<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="assets/css/style.css" rel="stylesheet">

</head>
<body>
    <?php include 'header.html' ?>

    <div id="cart-notification" class="alert alert-success alert-dismissible fade show cart-notification" role="alert" style="display: none;">
        <span id="cart-message"></span>
    </div>

    <div id="login-error-notification" class="alert alert-danger alert-dismissible fade show cart-notification" role="alert" style="display: none;">
        <span id="login-error-message"></span>
    </div>

    <div class="container mt-3">
        <div class="d-flex justify-content-end">
            <div class="nav-link position-relative">
                🛒 Carrello
                <span id="cart-count" class="position-absolute top-0 translate-middle badge rounded-pill bg-danger">
                    0
                </span>
            </div>
        </div>
    </div>

    
    <main>
        <section class="py-5">
            <div class="container">
                <div class="row">

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <img src="assets/img/thetrumanshow.jpg" class="card-img-top" alt="The Truman Show">
                            <div class="card-body">
                                <h5 class="card-title">The Truman Show</h5>
                                <p class="card-text">Commedia/Drammatico</p>

                                <?php if(isset($_SESSION['utente_loggato']) && $_SESSION['utente_loggato'] === true): ?>
                                    <button class="btn btn-order" onclick="addToCart(this)">Acquista</button>
                                    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#trama1" aria-expanded="false" aria-controls="trama1">
                                        Mostra Trama
                                    </button>
                                    <div class="collapse mt-2" id="trama1">
                                        <div class="card card-body">
                                            Un assicuratore inizia a sospettare che la sua vita sia in realtà una specie di reality show.
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-secondary" onclick="showLoginNotification('trama')">Mostra Trama</button>
                                    <button class="btn btn-order" onclick="showLoginNotification('cart')">Acquista</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <img src="assets/img/soundofmetal.jpg" class="card-img-top" alt="Sound Of Metal">
                            <div class="card-body">
                                <h5 class="card-title">Sound Of Metal</h5>
                                <p class="card-text">Drammatico/Musica</p>

                                <?php if(isset($_SESSION['utente_loggato']) && $_SESSION['utente_loggato'] === true): ?>
                                    <button class="btn btn-order" onclick="addToCart(this)">Acquista</button>
                                    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#trama2" aria-expanded="false" aria-controls="trama2">
                                        Mostra Trama
                                    </button>
                                    <div class="collapse mt-2" id="trama2">
                                        <div class="card card-body">
                                            Il batterista metal Ruben inizia a perdere l'udito. Quando un medico gli dice che le sue condizioni peggioreranno, pensa che la sua carriera e la sua vita siano finite. La sua ragazza Lou lo fa ricoverare in un centro di riabilitazione per sordi.                                        </div>
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-secondary" onclick="showLoginNotification('trama')">Mostra Trama</button>
                                    <button class="btn btn-order" onclick="showLoginNotification('cart')">Acquista</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <img src="assets/img/lahaine.jpg" class="card-img-top" alt="La Haine">
                            <div class="card-body">
                                <h5 class="card-title">La Haine</h5>
                                <p class="card-text">Drammatico</p>

                                <?php if(isset($_SESSION['utente_loggato']) && $_SESSION['utente_loggato'] === true): ?>
                                    <button class="btn btn-order" onclick="addToCart(this)">Acquista</button>
                                    <button class="btn btn-secondary " type="button" data-bs-toggle="collapse" data-bs-target="#trama3" aria-expanded="false" aria-controls="trama3">
                                        Mostra Trama
                                    </button>
                                    <div class="collapse mt-2" id="trama3">
                                        <div class="card card-body">
                                            Dopo una caotica notte di rivolte in una periferia periferica di Parigi, tre giovani amici, Vinz, Hubert e Saïd, vagano indisturbati in attesa di notizie sullo stato di salute di un amico comune, rimasto gravemente ferito durante uno scontro con la polizia.
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-secondary" onclick="showLoginNotification('trama')">Mostra Trama</button>
                                    <button class="btn btn-order" onclick="showLoginNotification('cart')">Acquista</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <img src="assets/img/dallasbuyersclub.jpg" class="card-img-top" alt="Dallas Buyers Club">
                            <div class="card-body">
                                <h5 class="card-title">Dallas Buyers Club</h5>
                                <p class="card-text">Storico/Drammatico</p>

                                <?php if(isset($_SESSION['utente_loggato']) && $_SESSION['utente_loggato'] === true): ?>
                                    <button class="btn btn-order" onclick="addToCart(this)">Acquista</button>
                                    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#trama4" aria-expanded="false" aria-controls="trama4">
                                        Mostra Trama
                                    </button>
                                    <div class="collapse mt-2" id="trama4">
                                        <div class="card card-body">
                                            Liberamente ispirato alla storia vera di Ron Woodroof, un uomo tossicodipendente, amante delle donne e omofobo a cui nel 1986 fu diagnosticato l'HIV/AIDS e gli furono dati trenta giorni di vita.
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-secondary" onclick="showLoginNotification('trama')">Mostra Trama</button>
                                    <button class="btn btn-order" onclick="showLoginNotification('cart')">Acquista</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <img src="assets/img/oppenheimer.jpg" class="card-img-top" alt="Oppenheimer">
                            <div class="card-body">
                                <h5 class="card-title">Oppenheimer</h5>
                                <p class="card-text">Drammatico/Storico</p>

                                <?php if(isset($_SESSION['utente_loggato']) && $_SESSION['utente_loggato'] === true): ?>
                                    <button class="btn btn-order" onclick="addToCart(this)">Acquista</button>
                                    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#trama5" aria-expanded="false" aria-controls="trama5">
                                        Mostra Trama
                                    </button>
                                    <div class="collapse mt-2" id="trama5">
                                        <div class="card card-body">
                                            La storia del ruolo di J. Robert Oppenheimer nello sviluppo della bomba atomica durante la seconda guerra mondiale.
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-secondary" onclick="showLoginNotification('trama')">Mostra Trama</button>
                                    <button class="btn btn-order" onclick="showLoginNotification('cart')">Acquista</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <img src="assets/img/requiemforadream.jpg" class="card-img-top" alt="Requiem For A Dream">
                            <div class="card-body">
                                <h5 class="card-title">Requiem For A Dream</h5>
                                <p class="card-text">Drammatico/Crime</p>

                                <?php if(isset($_SESSION['utente_loggato']) && $_SESSION['utente_loggato'] === true): ?>
                                    <button class="btn btn-order" onclick="addToCart(this)">Acquista</button>
                                    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#trama6" aria-expanded="false" aria-controls="trama6">
                                        Mostra Trama
                                    </button>
                                    <div class="collapse mt-2" id="trama6">
                                        <div class="card card-body">
                                            Le utopie indotte dalla droga di quattro residenti di Coney Island vanno in frantumi quando la loro dipendenza diventa profonda.
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-secondary" onclick="showLoginNotification('trama')">Mostra Trama</button>
                                    <button class="btn btn-order" onclick="showLoginNotification('cart')">Acquista</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <img src="assets/img/memento.jpg" class="card-img-top" alt="Memento">
                            <div class="card-body">
                                <h5 class="card-title">Memento</h5>
                                <p class="card-text">Thriller/Giallo</p>

                                <?php if(isset($_SESSION['utente_loggato']) && $_SESSION['utente_loggato'] === true): ?>
                                    <button class="btn btn-order" onclick="addToCart(this)">Acquista</button>
                                    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#trama7" aria-expanded="false" aria-controls="trama7">
                                        Mostra Trama
                                    </button>
                                    <div class="collapse mt-2" id="trama7">
                                        <div class="card card-body">
                                            Leonard Shelby è sulle tracce dell'uomo che ha violentato e ucciso sua moglie. La difficoltà di localizzare l'assassino della moglie, tuttavia, è aggravata dal fatto che l'uomo soffre di una rara e incurabile forma di perdita di memoria a breve termine.
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-secondary" onclick="showLoginNotification('trama')">Mostra Trama</button>
                                    <button class="btn btn-order" onclick="showLoginNotification('cart')">Acquista</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <img src="assets/img/oneflewoverthecuckoosnest.jpg" class="card-img-top" alt="One Flew Over The Cuckoo's Nest">
                            <div class="card-body">
                                <h5 class="card-title">One Flew Over The Cuckoo's Nest</h5>
                                <p class="card-text">Drammatico/Commedia</p>

                                <?php if(isset($_SESSION['utente_loggato']) && $_SESSION['utente_loggato'] === true): ?>
                                    <button class="btn btn-order" onclick="addToCart(this)">Acquista</button>
                                    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#trama8" aria-expanded="false" aria-controls="trama8">
                                        Mostra Trama
                                    </button>
                                    <div class="collapse mt-2" id="trama8">
                                        <div class="card card-body">
                                            Un piccolo criminale finge di essere pazzo per scontare la pena in un reparto psichiatrico anziché in prigione. Ben presto si ritrova a essere un leader per gli altri pazienti e un nemico per l'infermiera crudele e autoritaria che gestisce il reparto.
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-secondary" onclick="showLoginNotification('trama')">Mostra Trama</button>
                                    <button class="btn btn-order" onclick="showLoginNotification('cart')">Acquista</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </section>
    </main>

    <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
        <div class="text-center">
            <a href="gestisci.php" class="btn btn-warning btn-lg">Gestisci Utenti</a>
        </div>
    <?php endif; ?>

    <br>
    <?php include 'footer.html' ?>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    

    <script src="assets/js/script.js"></script>


</body>
</html>