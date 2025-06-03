<?= $this->extend('templates/home') ?>

<?= $this->section('content') ?>
<main class="main">
    <section id="userForm" class="user-form section d-flex flex-column align-items-center justify-content-center dark-background">
        <h2 class="fw-bold fs-3">BlogHub</h2>
        <div class="form-container d-flex flex-column align-items-center justify-content-center rounded p-4">
            <h3 class="text-primary mb-4 fs-3 fw-bold">Email Verification</h3>

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

            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <p class="mb-0"><?= esc($success) ?></p>
                </div>
            <?php endif; ?>

            <div class="text-center mb-4">
                <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                <p class="mb-0">
                    We've sent a verification email to:<br>
                    <strong class="text-primary"><?= esc(session('temp_email')) ?></strong>
                </p>
            </div>

            <div class="alert alert-info mb-4">
                <p class="mb-0">
                    Please check your email and click the verification link to activate your account.
                    If you don't see the email, please check your spam folder.
                </p>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <a href="<?= base_url('verification/resend') ?>" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane me-2"></i>
                        Resend Verification Email
                    </a>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <a href="<?= base_url('login') ?>" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Back to Login
                    </a>
                </div>
            </div>

            <p class="text-center text-muted mb-0">
                <small>The verification link will expire in 24 hours.</small>
            </p>
        </div>
    </section>
</main>
<?= $this->endSection() ?> 