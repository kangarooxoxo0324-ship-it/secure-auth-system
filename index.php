<?php
require_once __DIR__ . '/config.php';

if (is_authenticated()) {
    header('Location: register.php');
    exit;
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-shell">
        <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
            <div class="row w-100 justify-content-center">
                <div class="col-12 col-md-8 col-lg-5">
                    <div class="auth-card">
                        <div class="card-header text-center py-4">
                            <span class="brand-badge">S</span>
                            <h1 class="h3 mb-0 mt-3">Secure Access</h1>
                            <p class="text-muted mb-0">Login to continue</p>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            <?php if ($flash): ?>
                                <div class="alert alert-<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8'); ?> alert-dismissible fade show" role="alert">
                                    <?= nl2br(htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8')); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form action="process_login.php" method="POST" novalidate>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Enter your username" required>
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Enter your password" required>
                                </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" value="1" id="remember_me" name="remember_me">
                                    <label class="form-check-label" for="remember_me">
                                        Keep me signed in
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content dark-modal">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="errorModalLabel">Authentication Error</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if ($flash): ?>
                        <?= nl2br(htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8')); ?>
                    <?php else: ?>
                        Invalid username or password.
                    <?php endif; ?>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Try Again</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if ($flash): ?>
                const modal = new bootstrap.Modal(document.getElementById('errorModal'));
                modal.show();
            <?php endif; ?>
        });
    </script>
</body>
</html>
