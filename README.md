# Hair Salon Booking MVP (Laravel 12)

## Project Overview
This is a minimal but production-minded hair salon booking system built with Laravel 12, PHP 8.3, PostgreSQL, Blade templates, and Bootstrap 5.

Main features:
- Public booking form (hairdresser, date, available time, customer data)
- Dynamic available-time loading
- Double-booking protection with backend checks and a DB unique constraint
- Admin login and protected bookings list with date filtering

## Requirements
- PHP 8.3+
- Composer 2+
- Node.js 20+ and npm
- PostgreSQL 14+

## Install and Run
```bash
composer create-project laravel/laravel .
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run build
```

Copy this repository files into the fresh Laravel project, then configure `.env`.

## Database Setup
Set PostgreSQL values in `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=juuksur
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

Generate key and run schema:
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## Admin Account
Seeded admin credentials:
- Email: admin@example.com
- Password: Admin12345!

Login from `/login`.

## Main Routes
Public:
- `GET /` booking form
- `GET /available-times` available times for selected hairdresser/date
- `POST /bookings` create booking

Admin:
- `GET /admin/bookings` protected booking list

Auth:
- `GET /login`, `POST /login`, `POST /logout`

## Double-Booking Safety
The app prevents duplicate slots in two layers:
1. Pre-insert backend availability check.
2. Database unique index on `(hairdresser_id, booking_date, start_time)` as final concurrency-safe guard.

If a race condition happens, user sees:
- "Sorry, this time slot was just booked. Please choose another time."

## Testing
Run tests:
```bash
php artisan test
```

Included feature tests:
1. Public booking page loads
2. Available times exclude booked slots
3. Booking can be created
4. Duplicate booking is rejected
5. Guest is redirected from admin page
6. Authenticated admin can access bookings list

## Deployment Notes
- Use production `.env` and secure secrets.
- Set `APP_ENV=production`, `APP_DEBUG=false`.
- Run `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`.
- Run `npm run build` and ensure `public/build` is deployed.
- Ensure PostgreSQL user has migration and index permissions.
