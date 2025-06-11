# 💸 Traxp - Expense Tracker App

Traxp is a modern, mobile-responsive expense tracker built with Laravel 9. It helps users manage their finances by tracking income and expenses, viewing summaries and trends, and gaining insights through visual reports.

---

## 🚀 Features

-   **Authentication**
    -   Register
    -   Login
    -   Logout
    -   Forgot Password (reset link sent to email)
    -   Reset Password
-   **User Management**
    -   Update Profile Details
    -   Delete Account
-   **Responsive Design**
    -   Fully adaptable UI for mobile, tablet, and desktop screens
-   **Dashboard**
    -   Quick summary cards for:
        -   Daily
        -   Weekly
        -   Monthly
        -   Yearly
    -   Income, Expense, and Balance visualization
-   **Transactions**
    -   View all transactions in a table
    -   Search bar and static filters
    -   Pagination support
    -   Add, Edit, and Delete transactions
    -   Clickable rows to view detailed transaction info
-   **Cash Flow Calendar View**
    -   Monthly calendar showing cash inflow and outflow
    -   Switch months and years
    -   Click on a day to view all transactions for that date
-   **Insights**
    -   Graphical reports and charts for better understanding of financial trends

---

## 🛠️ Tech Stack

-   Laravel 9
-   Blade Templates
-   Tailwind CSS (or Bootstrap, depending on your UI choice)
-   Chart.js (or another charting library)
-   Laravel Breeze/Fortify for authentication (optional)

---

## 📦 Installation

1. **Clone the Repository**

    ```bash
    git clone https://github.com/your-username/traxp.git
    cd traxp

    ```

2. **Install php dependencies**

    ```bash
    composer install

    ```

3. **Install javascript dependencies**

    ```bash
    npm install
    npm run dev

    ```

4. **Setup Environment**

    ```bash
    cp .env.example .env
    php artisan key:generate

    ```

5. **Setup Database**
    ## Database Configuration

    By default, Traxp supports MySQL, SQLite, and PostgreSQL.  
    Make sure to configure your database connection in the `.env` file:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=traxp
    DB_USERNAME=root
    DB_PASSWORD=
    ```
    ```bash
    php artisan migrate
    php artisan db:seed

    ```

6. **Setup Mail Configuration**

    ```bash
    MAIL_MAILER=smtp
    MAIL_HOST=sandbox.smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=your_mailtrap_username
    MAIL_PASSWORD=your_mailtrap_password
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="noreply@traxp.com"
    MAIL_FROM_NAME="Traxp"

    ```

7. **Start the application**
    ```bash
    php artisan serve
    ```
````
