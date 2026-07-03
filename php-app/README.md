# Prayogbharti Foundation Website & Admin Dashboard – Core PHP Migration

This directory contains the complete port of the Prayogbharti Foundation website and its React Admin Dashboard to Core PHP (PHP + HTML5/CSS3/Bootstrap + MySQL/PDO).

## Local Development Setup (XAMPP)

Follow these steps to deploy and run the application on your local machine using XAMPP:

### 1. Copy the Project Files
Copy the entire contents of the `php-app/` directory into your XAMPP's `htdocs` directory under a new folder named `php-app`:
- **Path:** `C:\xampp\htdocs\php-app\`

### 2. Set Up the Database
1. Open XAMPP Control Panel and start **Apache** and **MySQL**.
2. Open your web browser and navigate to `http://localhost/phpmyadmin/`.
3. Create a new database named `prayogbharti_db`.
4. Select the newly created database, go to the **Import** tab, choose the `database.sql` file provided in this directory, and click **Import** (or **Go**).
   - This creates all required tables (users, leads, contacts, blog_posts, services, newsletter_subscribers) and seeds it with default admin user account and content.

### 3. Verify Configurations
Check `C:\xampp\htdocs\php-app\config\config.php`. It should contain the default database configuration matching standard local MySQL credentials:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'prayogbharti_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // Standard blank password for local XAMPP MySQL
define('BASE_URL', 'http://localhost/php-app');
```

### 4. Run the Application
Open your web browser and navigate to:
- **Public Website:** `http://localhost/php-app/`
- **Admin Dashboard:** `http://localhost/php-app/admin/` (or `http://localhost/php-app/admin/login.php`)

### 5. Default Credentials
Use the following credentials to access the admin portal:
- **Email:** `admin@prayogbharti.org`
- **Password:** `password`

*Make sure to change this password or add a new admin user once you are logged in.*

---

## Technical Highlights
- **No Node.js / React build steps:** Fully running natively in PHP.
- **Vanilla CSS styling:** Modern responsive theme layout mapping Tailwind styles.
- **PDO Database Abstraction Layer:** Secure parameterized MySQL operations protecting against SQL Injection.
- **PHP Sessions:** Session-guarded administrative dashboard endpoints.
- **REST APIs:** Full JSON API compatibility for AJAX-based form submissions.
