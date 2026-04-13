# 🏥 E-Medicine Premium Web Platform

![Project Status](https://img.shields.io/badge/Status-Production_Ready-success)
![Frontend Stack](https://img.shields.io/badge/Frontend-HTML%20%7C%20CSS%20%7C%20JS-blue)
![Backend Stack](https://img.shields.io/badge/Backend-PHP-indigo)

Welcome to the **E-Medicine Web Platform**, a modern, premium online pharmacy and healthcare management system. This platform is designed to provide users with a secure, highly visual, and completely seamless experience for browsing and purchasing medical supplies online.

---

##  Features

### 🛍️ Customer Experience
- **Premium User Interface**: A modern aesthetic featuring glassmorphism, responsive grid layouts, and custom micro-animations built completely with modern CSS (Zero framework dependencies).
- **Product Discovery**: Browse through intelligently categorized medical supplies including Medications, Supplements, Personal Care, and Medical Equipment.
- **Dynamic Cart & Checkout**: Real-time cart updates, beautiful checkout modals, and a dedicated Order Confirmation workflow complete with PDF invoice generation.
- **Secure Authentication**: Split-screen, visually stunning customer registration and login flows with password recovery.

###  Admin & Subadmin Portals
- **Secure Staff Access**: Master Admins and Subadmins have dedicated login portals featuring a distinct "Dark Slate" secure aesthetic.
- **Master Dashboard**: View total customer metrics, analytics, and instant health checks of the platform.
- **Content Management**: Manage inventory, approve or delete products, oversee user feedback, and directly manage system users.
- **Order Processing**: Subadmin dashboards dedicated entirely to processing user orders and tracking shipments.

---

##  Technology Stack

*   **Frontend**: HTML5, Vanilla CSS3 (Custom `global.css` design system), Vanilla JavaScript, jQuery (for smooth AJAX requests).
*   **Backend**: PHP Controllers handling business logic and API endpoints. 
*   **Database**: Designed for MySQL architecture.
*   **Architecture**: Client-server architecture with the frontend totally decoupled, communicating with the backend over RESTful API endpoints via AJAX.

---

##  Getting Started

To run this project locally, you will need a PHP server environment like XAMPP, WAMP, or `php -S`.

### 1. Clone the Repository
```bash
git clone https://github.com/your-username/misbah_e_medicine_website_2025.git
cd misbah_e_medicine_website_2025
```

### 2. Configure the Backend Environment
The frontend expects to communicate with the PHP backend at `http://localhost:8000/`.

Start the PHP development server routing to the `backend` directory:
```bash
php -S localhost:8000 -t .
```
*(Alternatively, configure your Apache/XAMPP server to serve this directory at port 8000).*

### 3. Initialize the Database
1. Import the necessary `.sql` files (if provided) into your MySQL database.
2. Verify that the database connection configuration inside your PHP controllers matches your local MySQL credentials.

### 4. Run the Application
Simply open `frontend/index.html` in your modern web browser, or navigate to:
```text
http://localhost:8000/frontend/index.html
```

---

##  Design System

This project implements a proprietary CSS architecture found in `frontend/assets/css/global.css`. 
It utilizes:
- **Color Palette**: Deep Blue (`#1e3a8a`) and Teals (`#0d9488`) for high-trust user flows, with a professional dark Slate (`#0f172a`) strictly reserved for Admin interfaces.
- **Typography**: `Inter` from Google Fonts.
- **Layouts**: Heavy use of modern CSS Grid/Flexbox and `backdrop-filter` for glass-panel effects.

---

##  Contribution
Feel free to fork this project, submit issues, or open pull requests to help improve the system.

*Built with  for better healthcare access.*
