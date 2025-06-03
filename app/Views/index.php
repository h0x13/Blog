<?= $this->extend('templates/home') ?>

<?= $this->section('content') ?>
  <main class="main">
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
      <div class="container d-flex flex-column align-items-center">
        <h2 data-aos="fade-up" data-aos-delay="100" class="display-4 fw-bold">EXPRESS. SHARE. CONNECT.</h2>
        <p data-aos="fade-up" data-aos-delay="200" class="lead text-center my-4">Your personal platform to create beautiful blogs and connect with readers</p>
        <div class="d-flex gap-3 mt-4" data-aos="fade-up" data-aos-delay="300">
          <a href="<?= base_url('blogs/add') ?>" class="btn btn-primary btn-lg px-4 py-2">CREATE YOUR BLOG</a>
          <a href="<?= base_url('blogs') ?>" class="btn btn-outline-light btn-lg px-4 py-2">EXPLORE BLOGS</a>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about section py-5">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <h3 class="display-6 mb-4">About BlogHub</h3>
            <div class="card border-0 shadow-sm p-4 mb-4">
              <p class="mb-3">Welcome to <b>BlogHub</b>, a dedicated space for writers, thinkers, and storytellers to share their ideas with the world. Our mission is to provide a simple, intuitive, and engaging platform where creativity thrives and voices are heard.</p>
              <p>We believe everyone has a unique story to tell, knowledge to share, or perspective to offer. Personal Blogging Hub was created to empower individuals by giving them the tools to express themselves freely while connecting with readers who appreciate their content.</p>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            <div class="content ps-0 ps-lg-5">
              <p class="fst-italic h5 mb-4">
                What can you do on our platform?
              </p>
              <div class="card border-0 shadow-sm p-4">
                <ul class="list-unstyled">
                  <li class="mb-3 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill text-primary me-2"></i>
                    <span><b>Write &amp; Publish</b> &hyphen; Create posts in minutes with our simple editor</span>
                  </li>
                  <li class="mb-3 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill text-primary me-2"></i>
                    <span><b>Connect with Readers</b> &hyphen; Get feedback through comments and find other writers</span>
                  </li>
                  <li class="mb-3 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill text-primary me-2"></i>
                    <span><b>Keep It Friendly</b> &hyphen; Our small team helps maintain a respectful community</span>
                  </li>
                </ul>
                <p class="mt-4 mb-0">
                  No fancy features, no complicated setup—just writing. Start your blog today and share your voice!
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services section light-background py-5">
      <div class="container section-title" data-aos="fade-up">
        <h2 class="display-6 mb-3">Features</h2>
        <p class="lead text-muted">Discover what makes BlogHub special</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="200">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                <div class="icon-box mb-3">
                  <i class="bi bi-journal-arrow-up display-4 text-primary"></i>
                </div>
                <h3 class="h4 mb-3">Easy Writing &amp; Publishing</h3>
                <p class="mb-0">A simple editor to write posts and one-click publishing so your posts go live instantly.</p>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="300">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                <div class="icon-box mb-3">
                  <i class="bi bi-gear display-4 text-primary"></i>
                </div>
                <h3 class="h4 mb-3">Simple Blog Customization</h3>
                <p class="mb-0">Change colors, font size, and headings to personalize your blog. No coding needed.</p>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="400">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                <div class="icon-box mb-3">
                  <i class="bi bi-chat-square-dots display-4 text-primary"></i>
                </div>
                <h3 class="h4 mb-3">Reader Interaction</h3>
                <p class="mb-0">A comment section for feedback and discussions, and a simple search to find posts.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="categories section py-5">
      <div class="container section-title" data-aos="fade-up">
        <h2 class="display-6 mb-3">Categories</h2>
        <p class="lead text-muted">Find BLOG BY CATEGORY</p>
      </div>

      <div class="container">
        <div class="d-flex flex-wrap justify-content-center gap-2" data-aos="fade-up" data-aos-delay="100">
          <?php foreach($categories as $category): ?>
            <a href="<?= base_url('blogs/category/'.$category['name']) ?>" class="category-badge text-decoration-none px-4 py-2 rounded-pill bg-light text-dark hover-shadow">
              <span><?= $category['name'] ?></span>
            </a>
          <?php endforeach ?>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact section light-background py-5">
      <div class="container section-title" data-aos="fade-up">
        <h2 class="display-6 mb-3">Contact</h2>
        <p class="lead text-muted">Get in touch with us</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="row gy-4">
              <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4" data-aos="fade-up" data-aos-delay="200">
                  <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-geo-alt text-primary fs-1 me-3"></i>
                    <div>
                      <h3 class="h5 mb-1">Address</h3>
                      <p class="mb-0">Eastern Visayas State University Main Campus</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4" data-aos="fade-up" data-aos-delay="400">
                  <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-envelope text-primary fs-1 me-3"></i>
                    <div>
                      <h3 class="h5 mb-1">Email Us</h3>
                      <p class="mb-0">florantejr.benitez@evsu.edu.ph</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
<?= $this->endSection() ?>
