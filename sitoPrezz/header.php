<div class="container-fluid bg-body-secondary py-3">
    <div class="row align-items-center text-center text-md-start flex-column flex-md-row">

        <!-- LOGO -->
        <div class="col-md-3 mb-3 mb-md-0">
            <a href="index.php">
                <img src="assets/img/logo.png" class="img-fluid rounded mx-auto d-block" alt="Logo" style="max-height: 80px;">
            </a>
        </div>

        <!-- INFO -->
        <div class=" col-md-6 mb-3 mb-md-0">
            <a href="https://fivefourfive.it/" class="text-decoration-none text-dark">
                <h4 class="m-0 text-center text-md-end">Info</h4>
            </a>
        </div>

        <!-- BUTTON -->
        <div class="col-md-3 text-center text-md-end">
            <?php if (isset($_COOKIE['email'])): ?>
                <div class="d-flex justify-content-center justify-content-md-end align-items-center gap-2">
                    <span class="text-muted">Ciao, <?php echo explode('@', $_COOKIE['email'])[0]; ?></span>
                    <a href="profilo.php" class="btn btn-primary btn-sm px-3">Profilo</a>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm px-3">Logout</a>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary px-4">Log In</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="border-top bg-secondary" style="height: 3px;"></div>