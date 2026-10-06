# Secure Login and User Registration System

This project is a secure PHP + Bootstrap 5 login and registration system using a local JSON file as a flat-file database.

## Features
- Root page is the login screen.
- Authentication is enforced with PHP sessions and secure cookie-based remember-me support.
- Access to the internal registration page is restricted unless a user is logged in.
- Strict validation is applied to username, login credentials, and registration fields.
- A dark Bootstrap 5 interface with a modern responsive layout is included.
- User and registration data are persisted in `database.json`.

## Default login account
When the database is first initialized, a default admin user is created automatically:
- Username: `admin`
- Password: `Admin@123`

## Run locally
From the project root:

```bash
php -S localhost:8000
```

Then open:

```text
http://localhost:8000/
```

## Important notes
- The system uses `session_start()` and protects inner routes with a secure session check.
- Registration form validation includes strict RegEx patterns for names, phone numbers, email, and URL fields.
- The default JSON file is created automatically if missing.
