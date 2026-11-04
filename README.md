# 🩸 Blood Donor Registry

A PHP + MySQL CRUD application for managing blood donors so hospitals and clinics can quickly find available donors by blood type and city.

**Live app:** https://blood-donor-registry-u9u1.onrender.com

## Features
- **Create** donors with server-side validation
- **Read** donors with search (name/city), blood-type filter and summary stats
- **Update** donor details and availability
- **Delete** donors (POST-only, CSRF-protected, confirmation prompt)
- **Staff login**: anyone can view the registry; only logged-in staff can add, edit or delete donors
- Passwords stored as bcrypt hashes, session ID regenerated on login, HttpOnly/SameSite cookies
- PDO prepared statements (SQL-injection safe), output escaping (XSS safe), CSRF tokens

## Demo staff account
| Username | Password |
|---|---|
| admin | ChangeMe123! |

Change it after deploying. Generate a new hash with `php -r "echo password_hash('NewPassword', PASSWORD_DEFAULT);"` then run
`UPDATE users SET password_hash='<hash>' WHERE username='admin';`

## Tech stack
PHP 8.2 · MySQL 8 · Apache · Docker · Render (app) · Aiven (database)

## Project structure
```
config/db.php        PDO connection (env-based)
includes/            shared header, footer, form, helpers
public/              web root (index, create, edit, delete, assets)
sql/database.sql     exported schema + sample data
Dockerfile           container image
```

## Run locally (XAMPP/MAMP)
1. Copy the project into `htdocs/blood-donor-registry`.
2. Start Apache and MySQL, open phpMyAdmin, and import `sql/database.sql`.
3. Visit `http://localhost/blood-donor-registry/public/`.

Or with PHP's built-in server: `php -S localhost:8000 -t public`

## Environment variables
| Variable | Description | Default |
|---|---|---|
| DB_HOST | MySQL host | 127.0.0.1 |
| DB_PORT | MySQL port | 3306 |
| DB_NAME | Database name | blood_donor_db |
| DB_USER | Database user | root |
| DB_PASS | Database password | (empty) |
| DB_SSL  | `true` for cloud MySQL | false |

## Deployment
- **Database:** Aiven for MySQL. Import `sql/database.sql` into the `defaultdb` database.
- **App:** Render Web Service using the included Dockerfile, with the env vars above.

## Git workflow
Feature branches (`feature/create`, `feature/read`, ...) merged into `main` through pull requests.
