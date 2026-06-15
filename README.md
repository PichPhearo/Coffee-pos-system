# ☕ Coffee Shop POS System

A modern, real-time Point of Sale (POS) system designed specifically for coffee shops. Built using **Laravel 12**, **TailwindCSS v4**, **AlpineJS**, and **Laravel Reverb** (WebSockets) for real-time order status updates between cashiers and baristas.

---

## 🌟 Key Features

*   **Multi-Role Dashboards**: Customized workspaces for Admin, Cashier, and Barista roles.
*   **Real-Time synchronization**: Orders placed by the Cashier appear instantly on the Barista's screen via Laravel Reverb (WebSockets).
*   **Interactive POS Interface**: Dynamic product grid, category filtering, cart management, discount/tax calculations, and instant order generation.
*   **Barista Kitchen View**: Live interface to view pending orders, track preparation status, and mark items as completed.
*   **Admin Management Hub**:
    *   Sales statistics and analytics dashboard.
    *   Product management (CRUD with image uploads).
    *   Category management (CRUD).
    *   Staff account management (Create staff, assign roles, toggle active status).
    *   Complete order history and detailed reports.

---

## 🛠️ Technology Stack

*   **Backend Framework**: Laravel 12 (PHP ^8.2)
*   **Frontend build tool**: Vite 7
*   **Styling & UI**: TailwindCSS v4 + Alpine.js
*   **Real-time engine**: Laravel Reverb (WebSocket server)
*   **Database**: SQLite (default/fallback) or MySQL/MariaDB (configured for XAMPP)

---

## 🚀 Getting Started & Installation

Follow these steps to clone, set up, and test the project locally.

### 1. Prerequisites
Ensure you have the following installed on your machine:
*   [PHP >= 8.2](https://www.php.net/downloads.php) (with extensions enabled: `pdo_sqlite`, `bcmath`, `sqlite3`, etc.)
*   [Composer](https://getcomposer.org/)
*   [Node.js & NPM](https://nodejs.org/)
*   A database engine: **SQLite** (included in PHP) or **MySQL/MariaDB** (via XAMPP, Laragon, Docker, etc.)

---

### 2. Clone the Repository
Clone the repository and navigate into the project directory:
```bash
git clone https://github.com/PichPhearo/Coffee-pos-system.git
cd Coffee-pos-system
```

---

### 3. Database Configuration
By default, the application is configured to run on **SQLite** for zero-configuration, but it supports **MySQL** (recommended for production/XAMPP environment).

Open the copied `.env` file and configure your database settings:

#### Option A: SQLite Setup (Default & Simplest)
1. Create a blank database file:
   * **Windows (PowerShell)**: `New-Item -ItemType File database/database.sqlite`
   * **Linux / macOS**: `touch database/database.sqlite`
2. Update your `.env`:
   ```env
   DB_CONNECTION=sqlite
   ```

#### Option B: MySQL Setup (XAMPP / Laragon)
1. Open phpMyAdmin or your MySQL client and create a database named `coffee_pos_db`.
2. Update your `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=coffee_pos_db
   DB_USERNAME=root
   DB_PASSWORD=YOUR_PASSWORD
   ```

---

### 4. Automatic Quick Setup
The project includes a convenient composer setup script that installs dependencies, generates keys, creates database tables, seeds default users, and builds frontend assets automatically.

Run the following command:
```bash
composer setup
```

*Note: Under the hood, this script executes:*
1. `composer install`
2. Copies `.env.example` to `.env` (if not already present)
3. `php artisan key:generate`
4. `php artisan migrate --force`
5. `npm install`
6. `npm run build`

---

## 💻 Running the Application Locally

The project uses `npx concurrently` to launch the web server, WebSocket server, asset compiler, and background queue workers simultaneously with a single command:

```bash
composer dev
```

This starts the following services:
*   **Laravel Server**: [http://localhost:8000](http://localhost:8000)
*   **Vite Assets**: Dynamic compilation and Hot Module Replacement (HMR)
*   **Laravel Reverb**: WebSocket server running on `127.0.0.1:8080` for real-time sync
*   **Queue Worker**: Processes background jobs
*   **Laravel Pail**: Local developer request/log streaming

Open your browser and navigate to **[http://localhost:8000](http://localhost:8000)**.

---

## 👤 Test User Credentials

After running `composer setup` (or seeding with `php artisan db:seed`), the database will be populated with the following default accounts. You can log in using these roles to test the different user experiences:

| Role | Email | Password | Interface Redirected | Description |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | `admin@pos.com` | `123456` | `/admin` | Can manage products, categories, staff, and view sales reports. |
| **Cashier** | `cashier@pos.com` | `123456` | `/pos` | Opens the sales POS dashboard to take orders. |
| **Barista** | `barista@pos.com` | `123456` | `/kitchen` | Opens the kitchen screen to view and prepare pending orders in real-time. |

---

## 🧪 Running Automated Tests

The project comes with a PHPUnit test suite to check authentication, roles routing, registration restrictions, and profile modifications.

To run the tests, execute:
```bash
composer test
```
*(All 27 custom and framework tests are configured and passing)*

---

## 📁 Key Directory Structure

*   `app/Http/Controllers/Admin` - Handles products, categories, staff management, and reporting logic.
*   `app/Http/Controllers/POS` - Logic for sales dashboard, cart orders, and transactions.
*   `app/Http/Controllers/Kitchen` - Logic for order state transitions (Preparing, Completed, etc.).
*   `routes/web.php` - Custom role-based middleware routing (`role:admin`, `role:cashier`, `role:barista`).
*   `database/seeders/UserSeeder.php` - Default user accounts definitions.
