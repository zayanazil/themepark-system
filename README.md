# Theme Park Management System

A Laravel-based web application for managing theme park operations including hotel bookings, ferry services, and event ticketing.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- **PHP** (>= 8.1)
- **Composer** (PHP dependency manager)
- **Node.js** (>= 16.x) and **npm**
- **MySQL** (>= 5.7 or 8.0)
- **Git** (optional, for cloning the repository)

## Installation

Follow these steps to set up the project locally:

### 1. Clone or Download the Repository

```bash
cd "themepark-system"
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Set Up Environment File

Copy the example environment file and configure it:

```bash
copy .env.example .env
```

### 5. Configure Database

Open the `.env` file and update the database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=themepark_db
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

### 6. Create Database

Log into MySQL and create the database:

```bash
mysql -u root -p
```

Then run:

```sql
CREATE DATABASE themepark_db;
```

### 7. Generate Application Key

```bash
php artisan key:generate
```

### 8. Run Migrations

Create all the necessary database tables:

```bash
php artisan migrate
```

### 9. Seed the Database

Populate the database with initial data:

```bash
php artisan db:seed
```


### 10. Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

