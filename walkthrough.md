# Migration Walkthrough: MERN Stack → Core PHP

The codebase migration for **Prayogbharti Foundation** has been successfully completed. Below is a summary of the files created and configurations established.

---

## 1. Database & Configuration Settings
- **`php-app/config/config.php`**: Contains DB credentials, root path constants, and initializes native session handling.
- **`php-app/config/db.php`**: Implements a secure PDO MySQL database abstraction singleton.
- **`php-app/database.sql`**: Full MySQL DDL file including indices, tables, foreign keys, and seed data for:
  - Users (Administrator role seed)
  - Blog Posts (3 sample items)
  - Services (4 sample items)

---

## 2. Reusable Modular Layouts (Includes)
- **`php-app/includes/header.php`**: Standardized `<head>` template with Bootstrap 5, Bootstrap Icons, custom font sheets, and Open Graph tag injections.
- **`php-app/includes/navbar.php`**: Retains the top orange info-strip and responsive navigation header.
- **`php-app/includes/footer.php`**: Houses the newsletter signup inline form and footer layout mapping.
- **`php-app/includes/auth_check.php`**: Simple procedural session guard to secure backend page routes.

---

## 3. Public Frontend Structure
- **`php-app/index.php`**: Replaces the React frontend (`App.tsx`). This page server-renders services and blog posts fetched from the MySQL database with static arrays as safe fallbacks.
- **`php-app/assets/css/style.css`**: Completely encapsulates Tailwind variables, layout specs, dark modes, animations, and typography rules in native vanilla CSS.
- **`php-app/assets/js/main.js`**: Replaces React components' internal states (`useState`/`useEffect`), handling scroll bindings, carousels, responsive mobile menu toggling, and asynchronous REST AJAX requests.

---

## 4. REST JSON API Endpoints
All API actions are fully modularized under `php-app/api/`:
- **Auth**: `login.php`, `register.php`, `me.php`
- **Leads**: `index.php` (POST submit / GET list), `status.php` (PATCH update), `delete.php` (DELETE lead)
- **Blogs**: `index.php` (GET published / POST create), `single.php` (GET by slug), `admin.php` (role-filtered list), `update.php`, `delete.php`
- **Services**: `index.php` (GET active / POST create), `single.php`, `admin.php`
- **Newsletter**: `subscribe.php`, `unsubscribe.php`, `subscribers.php`
- **Dashboard**: `index.php` (aggregated overview counts)

---

## 5. Administrative Dashboard Interface
Fully self-contained administration area under `php-app/admin/`:
- **`login.php` / `logout.php`**: Form processing page to log in administrators/authors.
- **`index.php`**: Displays high-level status count cards and tables showing recent leads and posts.
- **`leads.php`**: List all submissions with quick dropdown selectors to update lead statuses.
- **`blogs.php` / `blog_edit.php`**: Manage publishing drafts or writing articles.
- **`services.php` / `service_edit.php`**: Complete CRUD actions for services.
- **`subscribers.php`**: Manage newsletter subscriber email lists.
