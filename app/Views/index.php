<?= $this->extend('templates/home') ?>

<?= $this->section('content') ?>
  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <div class="container d-flex flex-column align-items-center">
        <h2 data-aos="fade-up" data-aos-delay="100">EXPRESS. SHARE. CONNECT.</h2>
        <p data-aos="fade-up" data-aos-delay="200">Your personal platform to create beautiful blogs and connect with readers</p>
        <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
          <a href="#about" class="btn-get-started">CREATE YOUR BLOG</a>
          <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox btn-explore-blog d-flex align-items-center"><span>EXPLORE &amp; READ BLOGS</span></a>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container">

        <div class="row gy-4">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <h3>About BlogHub</h3>
            <img src="<?= base_url('assets/img/blog/neel.jpg') ?>" class="img-fluid rounded-4 mb-4" alt="">
            <p>Welcome to <b>BlogHub</b>, a dedicated space for writers, thinkers, and storytellers to share their ideas with the world. Our mission is to provide a simple, intuitive, and engaging platform where creativity thrives and voices are heard.</p>
            <p>We believe everyone has a unique story to tell, knowledge to share, or perspective to offer. Personal Blogging Hub was created to empower individuals by giving them the tools to express themselves freely while connecting with readers who appreciate their content.</p>

          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            <div class="content ps-0 ps-lg-5">
              <p class="fst-italic">
                What can you do on our platform?
              </p>
              <ul>
                <li><i class="bi bi-check-circle-fill"></i> <span><b>Write &amp; Publish</b> &hyphen; Create a posts in minutes with the simple editor</span></li>
                <li><i class="bi bi-check-circle-fill"></i> <span><b>Connect with Readers</b> &hyphen; Get feedback through comments and find other writers.</span></li>
                <li><i class="bi bi-check-circle-fill"></i> <span><b>Keep It Friendly</b> &hyphen; Our small team helps maintain a respectful community.</span></li>
              </ul>
              <p>
                No fancy features, no complicated setup—just writing. Start your blog today and share your voice!
              </p>

              <div class="position-relative mt-4">
              <img src="<?= base_url('assets/img/about-2.jpg') ?>" class="img-fluid rounded-4" alt="">
                <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox pulsating-play-btn"></a>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Services Section -->
    <section id="services" class="services section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Features</h2>
        <p>BlogHub Features<br></p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-5">

          <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <div class="img">
              <img src="<?= base_url('assets/img/services-1.jpg') ?>" class="img-fluid" alt="">
              </div>
              <div class="details position-relative">
                <div class="icon">
                  <i class="bi bi-journal-arrow-up"></i>
                </div>
                <a href="service-details.html" class="stretched-link">
                  <h3>Easy Writing &amp; Publishing</h3>
                </a>
                <p>A simple editor to write posts and a one-click publishing so your posts go live instantly.</p>
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="300">
            <div class="service-item">
              <div class="img">
              <img src="<?= base_url('assets/img/services-2.jpg') ?>" class="img-fluid" alt="">
              </div>
              <div class="details position-relative">
                <div class="icon">
                  <i class="bi bi-gear"></i>
                </div>
                <a href="service-details.html" class="stretched-link">
                  <h3>Simple Blog Customization</h3>
                </a>
                <p>Change colors, font size, and headings to personalize your blog. No coding needed.</p>
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="400">
            <div class="service-item">
              <div class="img">
              <img src="<?= base_url('assets/img/services-3.jpg') ?>" class="img-fluid" alt="">
              </div>
              <div class="details position-relative">
                <div class="icon">
                  <i class="bi bi-chat-square-dots"></i>
                </div>
                <a href="service-details.html" class="stretched-link">
                  <h3>Reader Interaction</h3>
                </a>
                <p>A comment section for feedback and discussions, and a simple search to find post.</p>
              </div>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Services Section -->

    <!-- Categories Section -->
    <section id="categories" class="categories section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Categories</h2>
        <p>Find BLOG BY CATEGORY</p>
      </div><!-- End Section Title -->

      <div class="container">
            <?php foreach($categories as $category): ?>
                <a class="category-badge d-inline-flex align-items-center m-2">
                    <span><?= $category['name'] ?></span>
                </a>
            <?php endforeach ?>
      </div>

    </section><!-- /Portfolio Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contact</h2>
        <p>Necessitatibus eius consequatur</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
          <div class="col-lg-6 ">
            <div class="row gy-4">

              <div class="col-lg-12">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
                  <i class="bi bi-geo-alt"></i>
                  <h3>Address</h3>
                  <p>A108 Adam Street, New York, NY 535022</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
                  <i class="bi bi-telephone"></i>
                  <h3>Call Us</h3>
                  <p>+1 5589 55488 55</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
                  <i class="bi bi-envelope"></i>
                  <h3>Email Us</h3>
                  <p>info@example.com</p>
                </div>
              </div><!-- End Info Item -->

            </div>
          </div>

          <div class="col-lg-6">
            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="500">
              <div class="row gy-4">

                <div class="col-md-6">
                  <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
                </div>

                <div class="col-md-6 ">
                  <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
                </div>

                <div class="col-md-12">
                  <textarea class="form-control" name="message" rows="4" placeholder="Message" required=""></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>

                  <button type="submit">Send Message</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->

  </main>
<?= $this->endSection() ?>
