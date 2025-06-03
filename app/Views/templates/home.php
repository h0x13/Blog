<?php
function isActivePage($page) {
    $currentPage = uri_string();
    if ($page === 'home' && $currentPage === '') {
        return 'active';
    }
    if ($page === 'reset' && strpos($currentPage, 'reset-password') !== false) {
        return 'active';
    }
    return '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= $title ?? "BlogHub" ?></title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="/assets/img/case-logo.png" rel="icon">
  <link href="/assets/img/case-logo.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?= base_url('plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('plugins/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
  <link href="<?= base_url('plugins/aos/aos.css" rel="stylesheet') ?>">
  <link href="<?= base_url('plugins/glightbox/css/glightbox.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('plugins/swiper/swiper-bundle.min.css') ?>" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="<?= base_url('assets/css/main.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet" >

</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.html" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="<?= base_url('assets/img/case-logo.png') ?>" alt="logo">
        <h1 class="sitename">BlogHub</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="<?= base_url('/#hero') ?>" class="<?= isActivePage('home') ?>">Home</a></li>
          <li><a href="<?= base_url('/#about') ?>">About</a></li>
          <li><a href="<?= base_url('/#services') ?>">Features</a></li>
          <li><a href="<?= base_url('/#categories') ?>">Categories</a></li>
          <li><a href="<?= base_url('/#contact') ?>">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="cta-btn" href="<?= base_url('login') ?>">Sign In</a>

    </div>
  </header>

  <?= $this->renderSection('content') ?>

  <footer id="footer" class="footer dark-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-12 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">BlogHub</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Eastern Visayas State University</p>
            <p><strong>Email:</strong> <span>florantejr.benitez@evsu.edu.ph</span></p>
          </div>
        </div>

        <div class="col-12 col-md-6 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="<?= base_url('/#hero') ?>" ">Home</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="<?= base_url('/#about') ?>"">About</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="<?= base_url('/#services') ?>">Features</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="<?= base_url('/#categories') ?>">Categories</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="<?= base_url('/#contact') ?>">Contact</a></li>
          </ul>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">BlogHub</strong> <span>All Rights Reserved</span></p>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('plugins/php-email-form/validate.js') ?>"></script>
  <script src="<?= base_url('plugins/aos/aos.js') ?>"></script>
  <script src="<?= base_url('plugins/glightbox/js/glightbox.min.js') ?>"></script>
  <script src="<?= base_url('plugins/purecounter/purecounter_vanilla.js') ?>"></script>
  <script src="<?= base_url('plugins/swiper/swiper-bundle.min.js') ?>"></script>
  <script src="<?= base_url('plugins/imagesloaded/imagesloaded.pkgd.min.js') ?>"></script>
  <script src="<?= base_url('plugins/isotope-layout/isotope.pkgd.min.js') ?>"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>

