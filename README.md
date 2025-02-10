# 📌 Email Automation with Laravel & Filament

## 🚀 Overview
This Laravel Filament project automates report generation, email notifications, and queue management. It includes:
- **Filament Admin Panel**: Manage Customers, Products, and Orders.
- **Automated Report Generation**: Extracts reports from assigned URLs.
- **Job Queue System**: Handles report processing and email delivery asynchronously.
- **Failed Job Logging**: Logs errors and allows retrying failed jobs.
- **Google OAuth Authentication**: Allows users to log in with Google.

---


## 📂 Project Structure
- **Filament Admin Panel**: Manage customers, products, and orders from a web interface.
- **Job Queues**: Handles report generation and email sending in the background.
- **Email Automation**: Sends PDFs to customers with order details.
- **Social Authentication**: Users can log in via Google, Facebook, etc.

---

## 🔗 API Endpoints

### 🛠 Authentication via Socialite
| Method | Endpoint | Description |
|--------|---------|-------------|
| `GET` | `/auth/{provider}/redirect` | Redirects user to social login (Google, Facebook, etc.). |
| `GET` | `/auth/{provider}/callback` | Handles callback from social login. |

### 👥 Customer Management
| Method | Endpoint | Description |
|--------|---------|-------------|
| `GET` | `/customer` | Fetches all customers. |
| `POST` | `/customer` | Adds a new customer. |

### 📦 Product & Order Management (Filament)
| Method | Endpoint | Description |
|--------|---------|-------------|
| `GET` | `/product` | Accesses Filament panel for product management. |
| `GET` | `/order` | Accesses Filament panel for order management. |

### 📜 Report Generation & Email Automation
| Method | Endpoint | Description |
|--------|---------|-------------|
| `GET` | `/queue-downloads` | Processes queued PDF downloads. |
| `GET` | `/send-pdf` | Sends report PDFs to customers via email. |
| `GET` | `/process-order` | Processes an order, generates a report, and emails the customer. |

---

## ⚙️ Job Queue System
This project leverages Laravel's **Queue System** to handle long-running tasks efficiently:
- **ProcessOrderJob**: Fetches assigned URL, extracts the report, and sends an email.
- **PdfDownloadJob**: Downloads reports and stores them.
- **SendEmailJob**: Sends emails asynchronously.
- **Failed Job Handling**: All failed jobs are logged in the `job_failures` table.

### 🏃 Run queue worker:
```bash
php artisan queue:work
```

### 🛑 Kill the queue worker:
```bash
php artisan queue:restart
```

### 🔍 View failed jobs:
```bash
php artisan queue:failed
```

### 🔄 Retry a failed job:
```bash
php artisan queue:retry {id}
```

### 🔄 Automatically retry jobs 3 times before failing:
Modify the job class to include:
```php
public $tries = 3;
```

### 🧹 Clear all queued jobs:
```bash
php artisan queue:flush
```

---

## ⚡ How to Set Up MySQL Locally
1. Install MySQL Server:
   - On Ubuntu:
     ```bash
     sudo apt update
     sudo apt install mysql-server
     ```
   - On MacOS:
     ```bash
     brew install mysql
     ```

2. Start MySQL Service:
   ```bash
   sudo systemctl start mysql
   ```

3. Secure MySQL Installation:
   ```bash
   sudo mysql_secure_installation
   ```

4. Create a Database:
   ```sql
   CREATE DATABASE email_automation;
   CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'password';
   GRANT ALL PRIVILEGES ON email_automation.* TO 'laravel_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

5. Configure `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=email_automation
   DB_USERNAME=laravel_user
   DB_PASSWORD=password
   ```

6. Run Laravel Migrations:
   ```bash
   php artisan migrate
   ```

---

## 📨 Email Configuration
To send emails, configure your `.env` file:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@yourdomain.com
MAIL_FROM_NAME="Your App Name"
```

Then, clear and cache config:
```bash
php artisan config:clear
php artisan config:cache
```

---

## 🛠 How to Install Dependencies
After cloning the project:
```bash
composer install
npm install && npm run dev
```

If you face permission issues:
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 🌍 Configuring Google OAuth Redirect URI
To allow authentication, update `.env`:
```
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8088/auth/google/callback
```
Then, change your local development port:
```bash
php artisan serve --port=8088
```
Update Google Developer Console settings to:
```
Authorized redirect URIs: http://localhost:8088/auth/google/callback
```

---

## 📜 Understanding the Puppeteer `.cjs` File
The `.cjs` file format is used for **CommonJS modules** in Node.js, which is compatible with older versions of JavaScript.

### Example Puppeteer Script (`exportReport.cjs`)
```javascript
const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    
    await page.goto(process.argv[2]); // URL from CLI argument
    await page.waitForSelector('img'); // Wait for logo
    await page.click('img'); // Click on logo

    await page.pdf({ path: process.argv[3], format: 'A4' });

    await browser.close();
})();
```

This script:
1. Launches a headless browser.
2. Navigates to a URL.
3. Clicks on a logo.
4. Generates a PDF report.

---

## 📦 Installation & Setup
1. Clone this repository:
   ```bash
   git clone https://github.com/sttanminh/EmailAutomationLaravelFilament.git
   cd EmailAutomationLaravelFilament
   ```
2. Install dependencies:
   ```bash
   composer install
   npm install && npm run dev
   ```
3. Set up environment variables:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Run database migrations:
   ```bash
   php artisan migrate
   ```
5. Serve the application:
   ```bash
   php artisan serve --port=8088
   ```

---

## 🛠️ Troubleshooting
- **Jobs not executing?** Ensure the queue worker is running:
  ```bash
  php artisan queue:work
  ```
- **Puppeteer errors?** Install dependencies:
  ```bash
  npm install puppeteer
  ```
- **Permission issues?** Run:
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```
- **Social Login not working?** Update `.env` with:
  ```
  GOOGLE_CLIENT_ID=your-client-id
  GOOGLE_CLIENT_SECRET=your-client-secret
  GOOGLE_REDIRECT_URI=http://localhost:8088/auth/google/callback
  ```

---

## 📌 Future Improvements
- [ ] Implement retry logic for failed jobs.
- [ ] Enhance email templates for better UI.
- [ ] Add role-based authentication for Filament.

---

## 👨‍💻 Author
Developed by **Minh Nguyen** 🚀
