# 🛒 QuickBuy APP

**QuickBuy** is a robust, scalable **RESTful API** built with **Laravel 11** for powering a multi-store e-commerce platform. It provides full backend functionality for user management, store/product control, shopping cart operations, order processing, and user favorites — all secured with token-based authentication using Laravel Sanctum.

> **Note:** This is a backend-only project. It does not include frontend or payment/delivery integration.

---

## 🔑 Key Features

### 🔐 Authentication & User Management
- Register, login, and logout via **Laravel Sanctum**
- Edit profile (location, password, profile image)
- Delete account
- Secure image uploads with public access

### 🧑‍💼 User Roles & Access Control
- **Admins**: Manage stores, products, and review orders
- **Customers**: Browse, shop, manage cart, place orders, and use favorites
- Role-based middleware to protect and restrict routes

### 🏬 Store & Product Management
- Admins can:
  - Create and delete stores
  - Manage products per category and store
  - View detailed product information

### 🛒 Cart Functionality
- Add, update, and remove items in cart
- Supports multi-item checkout
- Cart persistence across user sessions

### 📦 Order Handling
- Orders are created upon checkout
- Customers can view their order history
- Admins can approve ✅ or reject ❌ orders

### 💖 Favorites System
- Mark/unmark products as favorites
- Prevents duplicate entries
- Retrieve favorite list with clean API

### 🧪 API Structure & Testing
- Fully RESTful API
- Routes are tested and documented in **Postman**
- Input validation via Laravel Form Requests
- Clean and consistent JSON responses using API Resources
- **Swagger (OpenAPI)** documentation available

---

## 🧠 Technologies Used

### 🧾 Languages
- PHP 8.3+

### 🔹 Backend Framework
- Laravel 11 (MVC Architecture)

### 🛢️ Database
- MySQL

### 🔒 Authentication & Security
- Laravel Sanctum (token-based auth)
- Role-based middleware access control
- Secure file uploads

### 🧰 Dev & Documentation Tools
- Swagger/OpenAPI – API Documentation
- Postman – Route testing
- Laravel Artisan – Command line utilities

---

## 📁 Folder Structure (Simplified)

