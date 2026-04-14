Laravel-Based E-Commerce System for Sports Shoes

This repository provides a full-stack web application for an online sports shoe store,
built with Laravel (PHP) and designed with a focus on:

User-friendly shopping experience
Dynamic product filtering (brand, size, price)
Admin & staff management system
Scalable MVC architecture
Real-world e-commerce workflow
🔍 Background & Motivation

Modern e-commerce platforms require:

Fast product browsing
Flexible filtering and searching
Clear UI/UX for customers
Efficient management for administrators

This project aims to simulate a real-world shoe store system, supporting:

Customers browsing and purchasing products
Admin managing inventory and promotions
Staff handling product operations
✨ Key Features
✅ Product listing with search & filter (brand, size, price)
✅ Dynamic UI with auto-submit filtering (AJAX-like behavior)
✅ Product categories (Nike, Adidas, Mizuno)
✅ Sale & Featured product sections
✅ Product detail modal (quick view)
✅ Shopping cart system
✅ Admin CRUD (Create, Read, Update, Delete)
✅ Promotion system (discount handling)
✅ Authentication (Login / Logout)
🧠 System Overview
Architecture (MVC - Laravel)
User (Browser)
   ↓
Routes (web.php)
   ↓
Controllers
   ↓
Models (Eloquent ORM)
   ↓
Database (MySQL)
   ↓
Blade Views (UI)
Main Flow
User Request
   ↓
ProductController
   ↓
Filter Logic (Search / Size / Brand / Price)
   ↓
Database Query
   ↓
Blade Rendering (UI)
   ↓
User Interface
📊 Example Features
🔎 Product Filtering
Search by name or brand
Filter by:
Brand (Nike, Adidas, Mizuno)
Size (38 → 43)
Price range

👉 Auto-submit when user changes filter input

🛍️ Product Display
Sale badge (SALE)
Featured products section
Hover overlay:
👁 Quick view (modal)
🛒 Add to cart
📦 Example Data Structure
products
- id
- name
- brand
- size (e.g., "38,39,42")
- price
- image
- is_sale
- is_featured
📁 Project Structure
Shoes_Sport/
│
├── app/
│   ├── Http/Controllers/
│   │   ├── HomeController.php
│   │   ├── ProductController.php
│   │   └── OrderController.php
│   │
│   ├── Models/
│   │   └── Product.php
│
├── resources/views/
│   ├── user/
│   │   ├── index.blade.php
│   │   └── products.blade.php
│   │
│   ├── products/
│   │   └── index.blade.php
│
├── routes/
│   └── web.php
│
├── public/
│   └── images/
│
├── database/
│   └── migrations/
│
└── README.md
⚙️ Requirements
PHP ≥ 8.x
Laravel ≥ 10
MySQL
Composer
XAMPP / Laragon / Docker
▶️ How to Run
1. Clone project
git clone <your-repo-url>
cd Shoes_Sport
2. Install dependencies
composer install
3. Setup environment
cp .env.example .env
php artisan key:generate
4. Configure database

Edit .env:

DB_DATABASE=shoes_store
DB_USERNAME=root
DB_PASSWORD=
5. Run migration
php artisan migrate
6. Start server
php artisan serve

👉 Open:
http://localhost:8000

🧪 Implemented Modules
Module 1: Product Management (CRUD)
Module 2: Product Filtering System
Module 3: Shopping Cart
Module 4: Promotion Handling
Module 5: User Authentication
Module 6: UI/UX with Bootstrap
📖 Technical Highlights
Laravel MVC architecture
Blade templating engine
Eloquent ORM for database interaction
Dynamic filtering with GET parameters
Pagination with query preservation
🚧 Limitations
Size stored as string (comma-separated) → not optimal
No online payment integration
No real-time inventory sync
No API (RESTful) yet
🔮 Future Work
Normalize database (product_sizes table)
Add payment gateway (VNPay / Momo)
Build REST API (for mobile app)
Add recommendation system
Improve UI with React / Vue
📜 License

This project is released under the MIT License.

🎓 Academic Use

This project is suitable for:

Web programming assignments
Laravel practice projects
E-commerce system demonstrations
Graduation thesis (basic level)