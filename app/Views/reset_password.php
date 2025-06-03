<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Reset Password - BlogHub</title>

    <!-- Favicons -->
    <link href="<?= base_url('assets/img/favicon.png') ?>" rel="icon">
    <link href="<?= base_url('assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?= base_url('plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('plugins/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="<?= base_url('assets/css/main.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">
</head>

<body>
    <main class="main">
        <section id="userForm" class="user-form section d-flex flex-column align-items-center justify-content-center dark-background">
            <h2 class="fw-bold fs-3">BlogHub</h2>
            <div class="form-container d-flex flex-column align-items-center justify-content-center rounded p-4">
                <h3 class="text-primary mb-4 fs-3 fw-bold">Set New Password</h3>
                
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger">
                        <?php if (is_array(session('error'))): ?>
                            <?php foreach (session('error') as $error): ?>
                                <p class="mb-0"><?= esc($error) ?></p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="mb-0"><?= esc(session('error')) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('reset-password') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="token" value="<?= esc($token) ?>">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="password_confirm" class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Confirm new password" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <!-- Vendor JS Files -->
    <script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html> 