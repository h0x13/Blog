<?= $this->extend('templates/home') ?>

<?= $this->section('content') ?>
  <main class="main">
    <section id="userForm" class="user-form section d-flex flex-column align-items-center justify-content-center dark-background">
        <h2 class="fw-bold fs-3">BlogHub</h2>
        <div class="form-container d-flex flex-column align-items-center justify-content-center rounded p-4">
            <h3 class="text-primary mb-4 fs-3 fw-bold">Create an Account</h3>
            
            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger">
                    <?php 
                    if (is_array(session('error'))) {
                        foreach (session('error') as $error) {
                            echo $error . '<br>';
                        }
                    } else {
                        echo session('error');
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php if (session()->has('success')): ?>
                <div class="alert alert-success">
                    <?= session('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('register') ?>" method="post">
                <?= csrf_field() ?>
                <div class="row mb-3">
                    <div class="col">
                        <div class="form-group">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" name="first_name" id="firstName" class="form-control" placeholder="Enter First Name" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="lastName" class="form-control" placeholder="Enter Last Name" required>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <div class="form-group">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-select">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="birthday" class="form-label">Birthday</label>
                            <input type="date" name="birthdate" id="birthday" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </div>
                </div>
                <p class="text-center">
                    Already have an account&quest; <a href="<?= base_url('login') ?>" class="btn-link">Sign in</a>
                </p>
            </form>
        </div>
    </section>
  </main>
<?= $this->endSection() ?>
