# 🛒 ShopEase — Full-Stack Capstone E-Commerce Web App

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

> 🚀 A fully functional, production-ready Full-Stack e-commerce application featuring a custom PHP API, MySQL database, and a robust Admin Dashboard. Built as the **Final Capstone Project** during my internship at **ApexPlanet Software Pvt Ltd**.

---

## 📖 Table of Contents
- [✨ Key Features](#-key-features)
- [🛠️ Tech Stack](#%EF%B8%8F-tech-stack)
- [📊 Admin Dashboard](#-admin-dashboard)
- [⚙️ Installation & Setup](#%EF%B8%8F-installation--setup)
- [📁 Project Structure](#-project-structure)
- [🚀 Performance Optimizations](#-performance-optimizations)

---

## ✨ Key Features

### 🛍️ Customer Experience
- **Dynamic Product Engine**: Fetches real-time product data from MySQL via a custom PHP REST API.
- **Advanced Filters**: Search by name or filter by category (Electronics, Fashion, Home, Books).
- **Interactive UI**: Product quick-view, wishlist, and dynamic cart management using LocalStorage.
- **Full-Stack Checkout**: Real-time order placement that updates stock inventory in the database.
- **Modern Aesthetics**: Glassmorphism UI, smooth animations, and a persistent Dark Mode.

### 🔐 Security & Auth
- **User Authentication**: Secure Login and Registration system with password hashing.
- **Role-Based Access**: Specialized views for Customers and Administrators.
- **Session Management**: Persistent sessions to track user state across the platform.

### 📊 Admin Dashboard
- **Site Statistics**: Real-time analytics for total sales, orders, users, and products.
- **Inventory Management**: Full CRUD (Create, Read, Update, Delete) for the product catalog.
- **Order Tracking**: Monitor customer orders and update shipment status.
- **User Management**: Overview of registered platform users.

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| **Frontend** | HTML5, CSS3 (Optimized), JavaScript (ES6+) |
| **Backend** | PHP 7.4+ |
| **Database** | MySQL |
| **Server** | Apache (XAMPP/WAMP) |
| **Security** | Session Auth, PDO Prepared Statements |

---

## ⚙️ Installation & Setup

1. **Clone the Repo**:
   ```bash
   git clone https://github.com/Thakurji890/shopease-capstone.git
   ```

2. **Server Setup**:
   - Move the project folder to your local server directory (e.g., `C:/xampp/htdocs/`).
   - Start **Apache** and **MySQL** via the XAMPP Control Panel.

3. **Database Configuration**:
   - Open your browser and go to `http://localhost/phpmyadmin/`.
   - Create a new database named `shopease_db`.
   - Run the setup script by visiting: `http://localhost/shopease-capstone/api/setup_db.php`.
   - *The script will automatically create tables and seed initial data.*

4. **Default Admin Credentials**:
   - **Email**: `admin@shopease.com`
   - **Password**: `admin123`

---

## 📁 Project Structure

```
shopease-capstone/
├── admin/               # Admin Dashboard (Orders, Products, Users)
├── api/                 # Backend REST API (Auth, Orders, CRUD logic)
├── css/                 # Highly optimized global styles
├── includes/            # Reusable PHP layout templates (Header, Footer)
├── js/                  # Frontend logic & Component injectors
├── index.php            # Home Page
├── products.php         # Product Catalog
├── cart.php             # Shopping Cart
└── README.md            # Documentation
```

---

## 🚀 Performance Optimizations
- **CSS Architecture**: Reduced CSS boilerplate by **87%** through selector grouping and utility refactoring.
- **Event Delegation**: Improved JavaScript performance by using centralized listeners for dynamic elements.
- **API Efficiency**: Implemented a dry, modular API structure with centralized database configuration.

---

### ⭐ If you find this project helpful, please give it a star! ⭐