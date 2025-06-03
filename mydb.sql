DROP DATABASE IF EXISTS blogdb;
CREATE DATABASE blogdb;
USE blogdb;

CREATE TABLE users (
    user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    is_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    image VARCHAR(255) DEFAULT NULL,
    birthdate DATE,
    gender VARCHAR(50),
    theme ENUM('dark', 'light'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE blogs (
    blog_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    thumbnail VARCHAR(255),
    visibility ENUM('private', 'public') NOT NULL DEFAULT 'private',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL COLLATE utf8_general_ci UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE blog_categories (
    blog_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (blog_id, category_id),
    FOREIGN KEY (blog_id) REFERENCES blogs(blog_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

CREATE TABLE email_verifications (
    verification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE password_resets (
    reset_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    blog_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (blog_id) REFERENCES blogs(blog_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE comment_replies (
    reply_id INT AUTO_INCREMENT PRIMARY KEY,
    comment_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (comment_id) REFERENCES comments(comment_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE blog_reactions (
    reaction_id INT AUTO_INCREMENT PRIMARY KEY,
    blog_id INT NOT NULL,
    user_id INT NOT NULL,
    reaction_type ENUM('like', 'dislike') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (blog_id) REFERENCES blogs(blog_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_blog_user_reaction (blog_id, user_id)
);

CREATE TABLE comment_reactions (
    reaction_id INT AUTO_INCREMENT PRIMARY KEY,
    comment_id INT NOT NULL,
    user_id INT NOT NULL,
    reaction_type ENUM('like', 'dislike') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (comment_id) REFERENCES comments(comment_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_comment_user_reaction (comment_id, user_id)
);

CREATE TABLE notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    reference_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE audit_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

INSERT INTO users (first_name, last_name, middle_name, email, password, role)
VALUES 
('admin', 'admin', NULL, 'admin@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'admin'),
('Alex', 'Johnson', NULL, 'alex.johnson@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'user'),
('Jordan', 'Lee', NULL, 'jordan.lee@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'user'),
('Taylor', 'Smith', 'Morgan', 'taylor.smith@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'user'),
('Morgan', 'Davis', NULL, 'morgan.davis@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'user'),
('Riley', 'Thompson', NULL, 'riley.thompson@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'user'),
('Skyler', 'Reed', NULL, 'skyler.reed@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'user');

INSERT INTO categories (name)
VALUES
('technology'),
('productivity'),
('design'),
('wellness'),
('travel'),
('lifestyle'),
('culture'),
('education'),
('career'),
('marketing'),
('photography'),
('writing'),
('entrepreneurship'),
('personal finance'),
('startups'),
('remote work'),
('freelancing'),
('artificial intelligence'),
('cybersecurity'),
('frontend development'),
('backend development'),
('mobile apps'),
('user experience'),
('cloud computing'),
('e-commerce'),
('data science'),
('machine learning'),
('devops'),
('blockchain'),
('open source');

INSERT INTO blogs (user_id, title, slug, content, thumbnail, visibility) VALUES
(2, 'YouTube Tutorial: Database Design with MySQL Workbench', 'youtube-tutorial-database-design-with-mysql-workbench-1', '<p>This video guides you through creating an Entity-Relationship Diagram (ERD) for a blog platform, covering essential components like users, posts, and categories.</p><p>For a visual walkthrough of designing a blog database schema using MySQL Workbench, consider watching this tutorial:</p><p><iframe frameborder=\"0\" src=\"//www.youtube.com/embed/u382hjH_cGo\" width=\"640\" height=\"360\" class=\"note-video-clip\"></iframe></p><h4 class=\"\">📘 <b>Comprehensive Guide: Designing a Blog Database Schema</b></h4><p data-start=\"399\" data-end=\"477\" class=\"\"><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">For a detailed, step-by-step guide on structuring a blog database, the DragonflyDB article provides in-depth explanations and examples:</span></p><ul><li><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">This resource delves into key entities such as users, posts, comments, and categories, offering best practices for schema design and optimization.</span></li></ul><h4 data-start=\"600\" data-end=\"656\" class=\"\">🛠️ GitHub Repository: Blog Database Schema in MySQL</h4><p data-start=\"658\" data-end=\"736\" class=\"\"><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">If you\'re looking for a practical example with SQL scripts, this GitHub repository offers a complete blog database schema:</span></p><ul><li><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">It includes SQL files and visual representations to help you implement and understand the database structure effectively.</span></li><li><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">These resources should provide you with a comprehensive understanding of blog database schemas, enhancing both your theoretical knowledge and practical skills.</span></li></ul><h4 class=\"\"><br></h4>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(2, 'Mastering Morning Routines: 3 Habits to Transform Your Day', 'mastering-morning-routines-3-habits-to-transform-your-day-1', '<h1 class=\"\"><b>Introduction</b></h1><p class=\"\">Have you ever felt like your mornings are chaotic and set a negative tone for the rest of the day? You\'re not alone. The way we start our mornings can deeply affect our productivity, mood, and mindset. In this post, we\'ll explore three powerful habits that can help you master your morning routine and set yourself up for success.</p><h4 data-start=\"601\" data-end=\"654\" class=\"\"><strong data-start=\"606\" data-end=\"652\">1. Wake Up with Intention (Not Your Phone)</strong></h4><p data-start=\"655\" data-end=\"829\" class=\"\">Most of us are guilty of checking our phones first thing in the morning. Instead, try starting your day with 5 minutes of deep breathing or journaling to set your intentions.</p><p data-start=\"831\" data-end=\"997\" class=\"\"><strong data-start=\"831\" data-end=\"893\">Watch this video for a guided 5-minute morning meditation:</strong></p><p data-start=\"831\" data-end=\"997\" class=\"\"><iframe frameborder=\"0\" src=\"//www.youtube.com/embed/inpok4MKVLM\" width=\"640\" height=\"360\" class=\"note-video-clip\"></iframe><strong data-start=\"831\" data-end=\"893\"><br></strong></p><h4 data-start=\"1004\" data-end=\"1044\" class=\"\"><strong data-start=\"1009\" data-end=\"1042\">2. Hydrate and Move Your Body</strong></h4><p data-start=\"1045\" data-end=\"1199\" class=\"\">Your body becomes dehydrated overnight. Drinking a glass of water and doing light stretching or a short workout gets your blood flowing and boosts energy.</p><p data-start=\"1201\" data-end=\"1334\" class=\"\"><strong data-start=\"1201\" data-end=\"1245\">Try this 10-minute morning yoga session:</strong></p><p data-start=\"1201\" data-end=\"1334\" class=\"\"><iframe frameborder=\"0\" src=\"//www.youtube.com/embed/4pKly2JojMw\" width=\"640\" height=\"360\" class=\"note-video-clip\"></iframe><strong data-start=\"1201\" data-end=\"1245\"><br></strong></p><h4 data-start=\"1341\" data-end=\"1376\" class=\"\"><strong data-start=\"1346\" data-end=\"1374\">3. Set Top 3 Daily Goals</strong></h4><p data-start=\"1377\" data-end=\"1521\" class=\"\">Rather than diving into a to-do list, focus on identifying your top three priorities for the day. This keeps you focused and prevents overwhelm.</p><p data-start=\"1523\" data-end=\"1689\" class=\"\"><strong data-start=\"1523\" data-end=\"1590\">Watch this productivity coach explain the \"Top 3 Goals\" method:</strong></p><p data-start=\"1523\" data-end=\"1689\" class=\"\"><iframe frameborder=\"0\" src=\"//www.youtube.com/embed/c-ZdxJtxfAo\" width=\"640\" height=\"360\" class=\"note-video-clip\"></iframe><strong data-start=\"1523\" data-end=\"1590\"><br></strong></p><h3 data-start=\"1696\" data-end=\"1720\" class=\"\"><strong data-start=\"1700\" data-end=\"1718\">Final Thoughts</strong></h3><p data-start=\"1721\" data-end=\"1915\" class=\"\">Your morning routine doesn\'t need to be long or complicated. Start with just one habit, be consistent, and gradually build up. A well-structured morning can truly transform the rest of your day.</p><p data-start=\"1523\" data-end=\"1689\" class=\"\"><br></p>', '1746769254_976c914292acd73c2fa8.jpg', 'private'),
(3, 'Boost Productivity with Time Blocking', 'boost-productivity-with-time-blocking', '<p>Learn how time blocking can help structure your day for peak productivity.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(4, 'Design Principles for Non-Designers', 'design-principles-for-non-designers', '<p>Understand basic design principles to enhance your content and presentations.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(5, 'Mindfulness Techniques to Reduce Stress', 'mindfulness-techniques-to-reduce-stress', '<p>Explore simple mindfulness practices that help manage daily stress.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(6, 'Top 5 Destinations for Remote Work', 'top-5-destinations-for-remote-work', '<p>These cities offer great internet, coworking spaces, and lifestyle for remote workers.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(7, 'Minimalist Lifestyle: How to Get Started', 'minimalist-lifestyle-how-to-get-started', '<p>Start decluttering and simplifying your life with these beginner tips.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(2, 'The Role of Culture in Marketing Strategy', 'the-role-of-culture-in-marketing-strategy', '<p>Understand how cultural values impact consumer behavior and marketing messages.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(3, 'Tips for Learning Faster and Retaining More', 'tips-for-learning-faster-and-retaining-more', '<p>Use these science-backed techniques to supercharge your learning.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(4, 'Is Freelancing Right for You?', 'is-freelancing-right-for-you', '<p>Evaluate if freelancing aligns with your work style and career goals.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(5, 'Getting Started with Personal Budgeting', 'getting-started-with-personal-budgeting', '<p>Create a monthly budget that actually works and helps you save.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(6, 'How Startups Can Leverage AI Tools', 'how-startups-can-leverage-ai-tools', '<p>Explore practical AI applications for early-stage startups.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(7, 'The Importance of UX in Mobile App Development', 'importance-of-ux-in-mobile-app-development', '<p>Learn how user experience design can make or break your mobile app.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(2, 'Best Practices for Writing Technical Blogs', 'best-practices-for-writing-technical-blogs', '<p>Write technical content that\'s clear, informative, and engaging.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(3, 'Beginner Guide to Cloud Computing', 'beginner-guide-to-cloud-computing', '<p>Understand the basics of cloud services like AWS, Azure, and GCP.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(4, 'Starting a Career in Cybersecurity', 'starting-a-career-in-cybersecurity', '<p>Explore key skills, certifications, and paths in cybersecurity.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(5, '10 Photography Tips for Beginners', '10-photography-tips-for-beginners', '<p>Improve your photography with these simple techniques.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(6, 'What Makes Great UX Writing?', 'what-makes-great-ux-writing', '<p>Discover how microcopy improves user experience and conversion.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(7, 'Launching Your First Online Store', 'launching-your-first-online-store', '<p>Follow these steps to set up and launch a successful e-commerce shop.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(2, 'Understanding Blockchain Beyond Cryptocurrency', 'understanding-blockchain-beyond-cryptocurrency', '<p>Blockchain has use cases beyond Bitcoin. Learn where it\'s headed.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(3, 'How to Contribute to Open Source Projects', 'how-to-contribute-to-open-source-projects', '<p>A practical guide to making your first contribution to open source.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(4, 'Remote Work Burnout: Warning Signs and Solutions', 'remote-work-burnout-warning-signs-and-solutions', '<p>Working remotely? Learn how to avoid burnout and stay balanced.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(3, 'Creating a Consistent Brand Aesthetic', 'creating-a-consistent-brand-aesthetic', '<p>Your brand\'s visual identity should be consistent across platforms. Learn how to maintain it.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(4, 'Why Backend Developers Should Learn DevOps', 'why-backend-developers-should-learn-devops', '<p>Integrating DevOps into backend workflows can improve deployment speed and stability.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(5, 'The Power of Networking for Career Growth', 'the-power-of-networking-for-career-growth', '<p>Building authentic connections is crucial for professional opportunities.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(6, 'Simple Home Workouts for Busy Professionals', 'simple-home-workouts-for-busy-professionals', '<p>No time for the gym? Try these quick, effective routines from home.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(7, 'Web Design Trends in 2025', 'web-design-trends-in-2025', '<p>From glassmorphism to AI integration—explore what\'s next in web design.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(2, 'Balancing Freelance Projects and Full-Time Work', 'balancing-freelance-projects-and-full-time-work', '<p>Juggling both worlds can be productive and profitable with the right habits.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(3, 'Digital Minimalism: Declutter Your Tech Life', 'digital-minimalism-declutter-your-tech-life', '<p>Cut down on digital distractions and reclaim your time and attention.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(4, 'Securing WordPress Sites Like a Pro', 'securing-wordpress-sites-like-a-pro', '<p>Practical security tips to harden your WordPress installations.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(5, 'How to Choose the Right UX Metrics', 'how-to-choose-the-right-ux-metrics', '<p>Not all metrics are created equal. Learn what matters most.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(6, 'Exploring Japan as a Digital Nomad', 'exploring-japan-as-a-digital-nomad', '<p>Japan offers high-speed internet and inspiring culture for remote workers.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(7, 'Scaling Startups with Microservices Architecture', 'scaling-startups-with-microservices-architecture', '<p>Learn how microservices help scale modern web applications.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(2, 'Public Speaking Tips for Introverts', 'public-speaking-tips-for-introverts', '<p>Yes, you can be a great speaker—learn techniques that work for quiet thinkers.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(3, 'The Rise of Headless CMS in Web Development', 'the-rise-of-headless-cms-in-web-development', '<p>Why developers are moving away from traditional CMS platforms.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(4, 'Email Marketing Strategies for Creators', 'email-marketing-strategies-for-creators', '<p>Build a loyal following with email campaigns that convert.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(5, 'Benefits of Learning a Second Language', 'benefits-of-learning-a-second-language', '<p>Being bilingual boosts your brain and your career.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(6, 'Understanding the Basics of REST APIs', 'understanding-the-basics-of-rest-apis', '<p>Learn how RESTful APIs power modern web applications.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(7, 'Introduction to Prompt Engineering', 'introduction-to-prompt-engineering', '<p>Crafting better prompts means getting better results from AI.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(2, 'How to Write Better Commit Messages', 'how-to-write-better-commit-messages', '<p>Communicate intent clearly with meaningful Git commit logs.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(3, 'Why Storytelling Matters in Marketing', 'why-storytelling-matters-in-marketing', '<p>Stories make your message stick—here\'s how to craft them.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(4, 'Organizing Your Files as a Photographer', 'organizing-your-files-as-a-photographer', '<p>Avoid digital chaos with proper file structure and backups.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(5, 'What is Ethical Design in Tech?', 'what-is-ethical-design-in-tech', '<p>Create user experiences that respect privacy and mental health.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(6, 'Choosing the Best JS Framework in 2025', 'choosing-the-best-js-framework-in-2025', '<p>React, Vue, Svelte? Here\'s how to choose wisely.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(7, 'Time Management Tips for Remote Developers', 'time-management-tips-for-remote-developers', '<p>Stay productive while working from home with these time-tested tricks.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(2, 'How to Use AI for Smarter Email Replies', 'how-to-use-ai-for-smarter-email-replies', '<p>Leverage tools like GPT for writing better, faster email responses.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(3, 'Budget Travel Tips for Creatives', 'budget-travel-tips-for-creatives', '<p>Explore the world without breaking the bank using these hacks.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(4, 'Deploying Apps with Docker and Kubernetes', 'deploying-apps-with-docker-and-kubernetes', '<p>A crash course on container orchestration for developers.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private'),
(5, 'How to Win Your First Freelance Client', 'how-to-win-your-first-freelance-client', '<p>Step-by-step guide to landing your first paying gig.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(6, 'How Cloud Services Affect Web Performance', 'how-cloud-services-affect-web-performance', '<p>The pros and cons of cloud hosting and content delivery networks.</p>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(7, 'Creating a Personal Knowledge Management System', 'creating-a-personal-knowledge-management-system', '<p>Tools and tips for organizing what you learn.</p>', '1746687003_25f2db7243e7d818080d.webp', 'private');

INSERT INTO blog_categories (blog_id, category_id)
VALUES
(1, 1),  -- Technology
(1, 21), -- Backend Development
(1, 28), -- DevOps
(1, 30), -- Open Source
(2, 4),  -- Wellness
(2, 6);  -- Lifestyle
