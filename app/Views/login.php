<?= $this->extend('templates/home') ?>

<?= $this->section('content') ?>
  <main class="main">
    <section id="userForm" class="user-form section d-flex flex-column align-items-center justify-content-center dark-background">
        <h2 class="fw-bold fs-3">BlogHub</h2>
        <div class="form-container d-flex flex-column align-items-center justify-content-center rounded p-4">
            <h3 class="text-primary mb-4 fs-3 fw-bold">Login Account</h3>
            <form>
                <div class="form-group mb-3" style="min-width: 300px;">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" class="form-control" placeholder="Enter Email">
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="Enter Password">
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-1">Login</button>
                <a href="<?= base_url('register') ?>" class="btn btn-success w-100 mb-2">Create Account</a>
            </form>
        </div>
    </section>
  </main>
<?= $this->endSection() ?>
