# SwiftPOS — Laravel Point of Sale System

A clean, full-featured POS system built with Laravel. Dark-themed UI with product management, order creation, and printable invoices.

---

## Features

- **Product Management** — Add, edit, delete products with image upload, price, quantity, and category
- **POS Terminal** — Visual product grid with real-time cart, search filter, qty controls
- **Order Management** — List, view, and update order status
- **Invoice** — Print-ready invoice page per order
- **Stock Control** — Inventory decrements automatically on order; restores on cancellation
- **Tax & Discount** — 10% tax auto-applied, per-order discount support

---

## Requirements

- PHP 8.2+
- Composer
- Laravel 11
- SQLite / MySQL / PostgreSQL
- Node.js (optional, only if you add Vite assets)

---

## Installation

```bash
# 1. Clone or unzip this project
cd pos-laravel

# 2. Install PHP dependencies
composer install

# 3. Copy and configure environment
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env (SQLite quickstart below)
# DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database/database.sqlite
touch database/database.sqlite

# 5. Run migrations
php artisan migrate

# 6. Seed sample products (optional)
php artisan db:seed

# 7. Create storage symlink for product images
php artisan storage:link

# 8. Start dev server
php artisan serve
```

Open **http://localhost:8000** in your browser.

---

## File Structure

```
app/
  Http/Controllers/
    ProductController.php   — CRUD for products (with image upload)
    OrderController.php     — Create orders, view, update status, invoice
  Models/
    Product.php
    Order.php
    OrderItem.php

database/
  migrations/
    *_create_products_table.php
    *_create_orders_table.php
    *_create_order_items_table.php
  seeders/
    ProductSeeder.php       — 12 sample products

resources/views/
  layouts/app.blade.php     — Main dark layout with nav
  products/
    index.blade.php         — Product grid with search/filter
    create.blade.php        — Add product form
    edit.blade.php          — Edit product form
  orders/
    create.blade.php        — POS terminal (live cart)
    index.blade.php         — Order list with filters
    show.blade.php          — Order detail + status update
    invoice.blade.php       — Print-ready invoice

routes/web.php              — All routes
```

---

## Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | /products | products.index | Product grid |
| GET | /products/create | products.create | Add form |
| POST | /products | products.store | Save product |
| GET | /products/{id}/edit | products.edit | Edit form |
| PUT | /products/{id} | products.update | Update product |
| DELETE | /products/{id} | products.destroy | Delete product |
| GET | /orders | orders.index | Order list |
| GET | /orders/create | orders.create | POS terminal |
| POST | /orders | orders.store | Place order |
| GET | /orders/{id} | orders.show | Order detail |
| PATCH | /orders/{id}/status | orders.status | Update status |
| GET | /orders/{id}/invoice | orders.invoice | Print invoice |

---

## Customization

### Change Tax Rate
In `OrderController.php`, find:
```php
$tax = round($subtotal * 0.10, 2);
```
Change `0.10` to your desired rate (e.g. `0.08` for 8%).

### Currency
Search for `$` in the Blade views and replace with your currency symbol.

### Logo / Branding
Search for `SwiftPOS` in the layout and invoice files to rebrand.

---

## Notes

- Product images are stored in `storage/app/public/products/` and served via the `storage` symlink.
- The POS terminal runs entirely in-browser JavaScript — no page reload until order submission.
- Cancelling an order automatically restores stock quantities.
