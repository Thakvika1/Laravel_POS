# SwiftPOS — Laravel POS and Admin Dashboard

SwiftPOS is a Laravel-based point-of-sale and inventory management application with a dark, modern admin UI. It lets you manage products, create orders, print invoices, authenticate admins, and monitor activity in real time.

This project was built as a practical example of a small but complete business application using Laravel, Blade templates, Eloquent, migrations, and a session-based authentication system.

---

## What this project does

SwiftPOS combines three main areas:

- Product management for inventory and catalog control
- Order creation and invoice generation for sales operations
- Admin access control with notifications and account management

It is designed for a shop or small store where an admin can add products, sell them, track orders, and monitor activity from a single dashboard.

---

## Main features

### 1. Authentication and access control
- Guests are redirected to the login page before using protected screens.
- Admin users can log in with session-based authentication.
- The app supports a system admin role and regular admin users.
- System admins can log out or delete other admin accounts.

### 2. Product management
- Create, edit, view, and delete products.
- Add product images.
- Manage price, stock quantity, category, and active/inactive status.
- Products can be searched and filtered by category.
- Product creation triggers an activity notification for other logged-in users.

### 3. Order management
- Create orders from available in-stock products.
- Automatically calculate subtotal, tax, discount, and total.
- Reduce product stock when an order is placed.
- Restore stock if an order is cancelled or deleted.
- View orders, update their status, and open printable invoice pages.

### 4. Notifications and live activity
- The app stores notifications in the database when important actions happen.
- Recent notifications are fetched asynchronously and shown in a live activity panel.
- Product creation and order creation both create visible activity updates.

### 5. Admin management
- Admins can create new admin accounts.
- The system admin can view the full admin list.
- The system admin can force logout other admins and remove their accounts.

### 6. Modern UI
- Dark-themed layout with cards, badges, filters, forms, and product grids.
- Responsive layout for desktop and tablet screens.
- Flash messages for success and error states.

---

## Tech stack

- PHP 8.2+
- Laravel 12
- Blade templates
- Eloquent ORM
- SQLite (default local setup) or other Laravel-supported databases
- PHPUnit for automated tests

---

## Project structure

```text
app/
  Http/
    Controllers/
      AuthController.php         - login/logout flow
      ProductController.php      - product CRUD + notifications
      OrderController.php        - order CRUD + stock handling
      AdminUserController.php    - admin creation + admin management
      NotificationController.php - recent notifications API
    Middleware/
      EnsureAuthenticated.php    - guest redirection guard
  Models/
    User.php                    - admin/auth model
    Product.php                 - product inventory model
    Order.php                   - sales order model
    OrderItem.php               - order line item model
    Notification.php            - live activity feed model

database/
  migrations/                  - schema for users, products, orders, notifications
  seeders/
    AdminUserSeeder.php       - default admin account
    ProductSeeder.php         - demo product data

resources/views/
  auth/login.blade.php        - login form
  products/                   - product pages
  orders/                     - order and invoice pages
  admin/users.blade.php       - admin list and admin creation form
  layouts/app.blade.php       - shared layout and live activity UI

routes/web.php                - application routes

tests/
  Feature/                    - regression tests for auth, orders, admin features
```

---

## Installation and setup

### 1. Clone the project

```bash
git clone <your-repo-url>
cd pos-laravel
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure environment

Laravel usually uses a `.env` file. If you do not have one yet:

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Create a database

This project works well with SQLite for local development:

```bash
touch database/database.sqlite
```

Then update `.env` with:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

### 5. Run migrations and seed data

```bash
php artisan migrate --seed
```

### 6. Create the storage symlink for product images

```bash
php artisan storage:link
```

### 7. Start the app

```bash
php artisan serve
```

Open your browser at:

```text
http://localhost:8000
```

---

## Default login credentials

After seeding, you can sign in with:

- Email: `admin@example.com`
- Password: `password123`

---

## How the main modules work

### Authentication flow
The login form posts to the authentication controller. If the user is valid, Laravel creates an authenticated session and redirects the user to the product page. If the database has no users yet, the login flow will bootstrap a default system admin account so the app can still be used immediately.

### Product flow
Products are managed through the product controller. When a product is created, the app stores it in the database and also creates a notification entry. The product index page can refresh periodically so other logged-in users see new products without manually reloading the page.

### Order flow
Orders are created from the order screen. The controller validates the order data, checks stock availability, creates the order record, stores each line item, decrements inventory, and creates a notification for the activity feed.

### Notification flow
Notifications are stored in their own table and exposed through a JSON endpoint. The shared layout fetches these notifications periodically and displays them in the live activity card.

### Admin management flow
System admins can open the admin page to manage other admin accounts. From there they can create new admins, log out other admins, or permanently delete them.

---

## Main routes

The app currently exposes these important routes:

- `/login` — login form and login POST
- `/logout` — logout action
- `/products` — list products
- `/products/create` — create product page
- `/products/{id}/edit` — edit product page
- `/orders` — order list
- `/orders/create` — order creation screen
- `/orders/{id}` — order detail
- `/orders/{id}/invoice` — printable invoice
- `/admin/users` — admin list and management page

---

## Testing

The project includes feature tests for:

- guest access protection
- login and authentication flow
- product creation notifications
- admin management actions
- product deletion rules

Run the tests with:

```bash
php artisan test
```

---

## Notes for developers

- Product images are stored under the public storage disk and should be served through the storage symlink.
- Stock is updated during order creation and restored when an order is cancelled or removed.
- The UI uses Blade views and custom CSS in the shared layout rather than a frontend framework.
- The app is intentionally simple and easy to extend for new modules such as suppliers, reports, or user permissions.

---

## Summary

SwiftPOS is a compact Laravel POS application with real admin controls, inventory management, sales processing, invoices, and a live activity feed. It is suitable as a starting point for a retail or small-store management system and can be extended with more advanced features over time.
