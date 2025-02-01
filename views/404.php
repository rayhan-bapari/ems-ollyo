<?php
$pageTitle = "404 Not Found";
require_once ROOT_PATH . '/views/includes/header.php';
?>

<div id="layoutError">
    <div id="layoutError_content">
        <main>
            <div class="container">
                <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
                    <div class="col-lg-6">
                        <div class="text-center mt-4">
                            <img class="mb-4 img-error" src="<?php echo BASE_URL; ?>/img/error-404-monochrome.svg" />
                            <h1>404 - Page Not Found</h1>
                            <p class="lead">The page you are looking for does not exist.</p>
                            <a href="<?php echo BASE_URL; ?>">
                                <i class="fas fa-arrow-left me-1"></i>
                                Return to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/includes/footer.php'; ?>
