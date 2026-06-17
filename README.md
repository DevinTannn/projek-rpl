# SELUNA (SumselPeduli) 

SELUNA (SumselPeduli) is a modern crowdfunding and online donation platform specifically designed for South Sumatra (Sumatera Selatan). The system is built with a **shared-database multi-application architecture**, split into two separate Laravel projects:

1. **SELUNA Main Web Application (`/seluna`)**: The public portal where donators can browse active campaigns, follow campaigns, search, make donations using Midtrans or manual transfer, request fundraiser verification, and manage profiles.
2. **SELUNA Admin Panel (`/selunaadminpanel/admin`)**: The management dashboard where administrators can verify campaigns, fundraisers (accounts), manual bank transfers, and handle reported campaigns.

---

## 🏗️ Architecture Overview

Both projects connect to the same MySQL database (`sumselpeduli`). This ensures that actions taken by users on the main site (such as submitting a campaign, donation, or fundraiser request) are immediately visible to the admin panel for moderation and approval.

```
                  ┌─────────────────────────────────────────┐
                  │          SELUNA Main App (Port 8000)    │
                  └────────────────────┬────────────────────┘
                                       │
                                       ▼
                   ┌──────────────────────────────────────┐
                   │    MySQL Database (sumselpeduli)     │
                   └───────────────────▲──────────────────┘
                                       │
                                       ▼
                  ┌─────────────────────────────────────────┐
                  │        SELUNA Admin Panel (Port 8001)   │
                  └─────────────────────────────────────────┘
```

---

## 🛠️ Prerequisites

Ensure you have the following installed on your system:
- **PHP**: `^8.3` (with standard extensions: PDO, OpenSSL, Mbstring, XML, etc.)
- **Composer**: `^2.x`
- **Node.js & NPM**: `^20.x` or later
- **Database**: MySQL

---

## 🚀 Quick Start Guide

Both projects are equipped with helper composer scripts to automate the installation and running procedures. 

### 1. Database Setup
Create a new MySQL database named `sumselpeduli` (or any name you prefer) in your database manager (e.g., phpMyAdmin, TablePlus, or via CLI):
```sql
CREATE DATABASE sumselpeduli;
```

### 2. Main Application Setup (`seluna`)
Navigate to the `seluna` directory and run the helper setup script.
```bash
cd seluna
composer run setup
```
> [!IMPORTANT]
> **Database migrations must be run in `seluna` first** as it contains the full schema, including user roles, campaign verification tables, and transaction logs. Running the command above will copy `.env.example` to `.env`, generate your app key, run the migrations, install NPM packages, and compile frontend assets.
> 
> Once finished, update the database credentials in the newly generated `seluna/.env` file:
> ```env
> DB_CONNECTION=mysql
> DB_HOST=127.0.0.1
> DB_PORT=3306
> DB_DATABASE=sumselpeduli
> DB_USERNAME=root
> DB_PASSWORD=your_password
> ```

### 3. Database Seeding
To populate the database with dummy campaigns, fundraisers, and milestones:
```bash
php artisan db:seed
```

### 4. Admin Panel Setup (`selunaadminpanel`)
Navigate to the `selunaadminpanel` directory and set it up.
```bash
cd ../selunaadminpanel
composer run setup
```
Update its `.env` file to connect to the **same** database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sumselpeduli
DB_USERNAME=root
DB_PASSWORD=your_password

# Link back to main portal
SELUNA_MAIN_URL=http://127.0.0.1:8000
APP_URL=http://127.0.0.1:8001
```

---

## 💻 Running the Applications

To run the applications locally, open two separate terminal windows and run:

### Start SELUNA Main Web App (Port 8000)
```bash
cd seluna
composer run dev
```
*This command runs the local PHP server, Vite hot-reload compiler, and Laravel queue listeners concurrently.*
- Access URL: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

### Start SELUNA Admin Panel (Port 8001)
```bash
cd selunaadminpanel
composer run dev
```
*This command runs the admin PHP server, Vite compiler, queue listener, and Laravel Pail for logging.*
- Access URL: **[http://127.0.0.1:8001/admin](http://127.0.0.1:8001/admin)**

---

## 🔑 Default Test Accounts

You can log in to test the application flows using these pre-seeded accounts:

### 1. Fundraiser / Donator Account (Main Site)
- **Email**: `testuser@gmail.com`
- **Password**: `password`
- **Role**: Fundraiser

### 2. Admin Account (Admin Panel)
- **Username**: `admin`
- **Password**: `password`
- **Role**: Administrator
> [!NOTE]
> When you log in with `admin` and `password` on the Admin Panel for the first time, the application will automatically seed the Administrator user into the shared database if it does not already exist.

---

## ⚙️ Key Configurations & Services

### Midtrans Payment
The main app is configured with:
- `MIDTRANS_MERCHANT_ID=your_merchant_id_here`
- `MIDTRANS_CLIENT_KEY=your_client_key_here`
- `MIDTRANS_SERVER_KEY=your_server_key_here`

### Mail Configurations
- **Main App (`seluna`)**: Uses SMTP (preconfigured to a Gmail SMTP sandbox account in `.env`).
- **Admin Panel (`selunaadminpanel`)**: Uses PHPMailer to send emails when donations, campaigns, or accounts are verified. Update the following SMTP parameters in `selunaadminpanel/.env` to receive emails (e.g., Mailtrap, Mailgun):
  ```env
  MAIL_HOST=sandbox.smtp.mailtrap.io
  MAIL_PORT=2525
  MAIL_USERNAME=your_username
  MAIL_PASSWORD=your_password
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS="no-reply@sumselpeduli.org"
  MAIL_FROM_NAME="SumselPeduli"
  ```
