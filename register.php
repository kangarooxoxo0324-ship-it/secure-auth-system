<?php
require_once __DIR__ . '/config.php';
require_auth();

$flash = get_flash();
$database = ensure_database();
$registrations = $database['registrations'] ?? [];
$registrations = array_reverse($registrations);
$currentUser = $_SESSION['user']['username'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="page-shell">
        <nav class="navbar navbar-expand-lg topbar">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <span class="brand-badge">S</span>
                    <span class="ms-2">Secure Portal</span>
                </a>
                <div class="ms-auto d-flex align-items-center gap-3">
                    <span class="logged-user">Welcome, <?= htmlspecialchars($currentUser, ENT_QUOTES, 'UTF-8'); ?></span>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
                </div>
            </div>
        </nav>

        <main class="container py-5">
            <?php if ($flash): ?>
                <div class="alert alert-<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8'); ?> alert-dismissible fade show" role="alert">
                    <?= $flash['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="card form-card shadow-lg">
                        <div class="card-header border-0 pb-0 pt-4 px-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <p class="eyebrow mb-1">Internal Registration</p>
                                    <h2 class="h3 mb-0">User Registration</h2>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4 p-lg-5">
                            <form action="process_register.php" method="POST" novalidate>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Juan" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="Optional">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Dela Cruz" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="contact_number" class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="09123456789" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                    </div>

                                    <div class="col-12">
                                        <label for="website_url" class="form-label">Website URL</label>
                                        <input type="url" class="form-control" id="website_url" name="website_url" placeholder="https://example.com" required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-4">Submit Registration</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <div class="card table-card shadow-sm">
                        <div class="card-header border-0 bg-transparent px-4 pt-4">
                            <h3 class="h5 mb-0">Saved Registration Records</h3>
                        </div>
                        <div class="card-body p-4">
                            <?php if (empty($registrations)): ?>
                                <div class="empty-state text-center py-4">
                                    <p class="mb-0 text-muted">No registration records yet.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-dark table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Full Name</th>
                                                <th>Contact</th>
                                                <th>Email</th>
                                                <th>Website</th>
                                                <th>Submitted By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($registrations as $record): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars(($record['first_name'] ?? '') . ' ' . ($record['middle_name'] ?? '') . ' ' . ($record['last_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?= htmlspecialchars($record['contact_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?= htmlspecialchars($record['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?= htmlspecialchars($record['website_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?= htmlspecialchars($record['submitted_by'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
