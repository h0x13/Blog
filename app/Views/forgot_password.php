<?= $this->extend('templates/home') ?>

<?= $this->section('content') ?>
  <main class="main">
    <section id="userForm" class="user-form section d-flex flex-column align-items-center justify-content-center dark-background">
        <h2 class="fw-bold fs-3">BlogHub</h2>
        <div class="form-container d-flex flex-column align-items-center justify-content-center rounded p-4">
            <h3 class="text-primary mb-4 fs-3 fw-bold">Reset Password</h3>
            
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

            <?php if (session()->has('success')): ?>
                <div class="alert alert-success">
                    <p class="mb-0"><?= esc(session('success')) ?></p>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('forgot-password') ?>" method="post">
                <?= csrf_field() ?>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your registered email" required>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                    </div>
                </div>
                <p class="text-center">
                    Remember your password? <a href="<?= base_url('login') ?>" class="btn-link">Sign In</a>
                </p>
            </form>
        </div>
    </section>
  </main>
<?= $this->endSection() ?> 