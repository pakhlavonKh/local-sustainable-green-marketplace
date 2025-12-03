# Local Sustainable Green Marketplace - Setup

This project uses MongoDB for product and user data. To ensure all collaborators connect to the same remote Atlas cluster, set your MongoDB connection using environment variables.

## 1) PHP driver
- Install and enable the `ext-mongodb` PHP extension on your machine (required by the MongoDB PHP library).
- On Windows, download the DLL matching your PHP version (ZTS/NTS, VC build, x64/x86) from:
  https://windows.php.net/downloads/pecl/releases/mongodb/
- Place `php_mongodb.dll` in your PHP `ext` directory and add `extension=php_mongodb.dll` to your `php.ini`.
- Restart your webserver / PHP process and verify with `php -m | grep mongodb`.

## 2) Environment configuration
- Copy `.env.example` to `.env` (or set environment variables in your system) and fill in the values.
- Recommended: set `MONGODB_URI` to your Atlas connection string (mongodb+srv://...)

## 3) Install PHP dependencies
From the project root:

```bash
composer install --no-interaction --prefer-dist
```

If Composer complains about platform requirements, ensure `ext-mongodb` is enabled and re-run the command.

## 4) Database
- Import `marketplace.sql` into your MySQL server if you want the SQL schema:

```bash
mysql -u root -p < marketplace.sql
```

- To populate MongoDB with sample products, you can run `seed_db.php` from the project root:

```bash
php seed_db.php
```

This script uses the same `getDBConnection()` behavior (reads `MONGODB_URI` / environment variables).

## 5) Run locally (quick test)

```bash
php -S 127.0.0.1:8000 -t .
```

Open `http://127.0.0.1:8000/` in your browser.

---

If you want, I can:
- Provide the exact `php_mongodb.dll` download link for your PHP build (I already detected `PHP 8.4.14 ZTS VC++ 2022 x64`).
- Re-run `composer install` here after you enable the extension and confirm the `.env` is set.
