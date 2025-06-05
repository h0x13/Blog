<!doctype html>
<html lang="en">
    <!--begin::Head-->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Blog | <?= $this->renderSection('title') ?></title>
        <!--begin::Primary Meta Tags-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="title" content="Blog" />
        <meta name="author" content="0x13" />
        <meta
            name="description"
            content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
        />
        <meta
            name="keywords"
            content="blog, post, article"
        />
        <!--end::Primary Meta Tags-->

        <!-- Favicons -->
        <link href="/assets/img/case-logo.png" rel="icon">
        <link href="/assets/img/case-logo.png" rel="apple-touch-icon">

        <?= $this->include('components/styles') ?>
        <?= $this->renderSection('styles') ?>
    </head>
    <!--end::Head-->
    <!--begin::Body-->
    <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
        <!--begin::App Wrapper-->
        <div class="app-wrapper">

            <?php if (session()->get('user_role') === 'admin'): ?>
            <?= $this->include('components/navbar') ?>
            <?= $this->include('components/sidebar') ?>
            <?php else: ?>
                <?= $this->include('components/regular_user/navbar') ?>
                <?= $this->include('components/regular_user/sidebar') ?>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>

            <?= $this->include('components/scripts') ?>

        </div>
        <!--end::App Wrapper-->
        <?= $this->renderSection('scripts') ?>
        <script>
            const allImages = document.querySelectorAll('img');
            allImages.forEach(img => {
                img.addEventListener('error', () => {
                img.src = '<?= base_url("assets/img/no-image.png") ?>';
                img.alt = 'Image not found';
                });
            });
        </script>
    </body>
    <!--end::Body-->
</html>
