# SCHOOL SUMMARY

## 1. Client-Server in This App
The browser is the client and sends HTTP requests to the Laravel server.
The server validates input, queries PostgreSQL, and returns HTML (Blade) or JSON (available-time endpoint).

## 2. Frontend and Backend
Frontend:
- Blade templates rendered in browser
- Bootstrap 5 for layout and styling
- JavaScript fetch for available-time updates

Backend:
- Laravel controllers, models, middleware, and validation
- Business logic in a reusable service
- PostgreSQL persistence with migrations and constraints

## 3. Why Laravel Is a Framework
Laravel is a framework because it provides structured architecture and ready components: routing, validation, ORM, templating, auth, middleware, CSRF, testing tools, and service container.

## 4. MVC Roles in This Project
Model:
- `Hairdresser`, `Booking`, `User` represent database entities and relationships.

View:
- Blade files render public booking UI, admin list, and login form.

Controller:
- `BookingController` handles public booking flow.
- `AdminBookingController` handles protected admin listing.
- `AuthenticatedSessionController` handles login/logout.

## 5. Main Security Risks for This App
- Invalid or malicious input
- SQL injection
- XSS
- CSRF
- Unauthorized admin access
- Double booking during concurrent requests

## 6. Concrete Protections Used
- Invalid/malicious input -> Laravel server-side validation (`StoreBookingRequest`, request validation in endpoints)
- SQL injection -> Eloquent/query builder with parameterized queries
- XSS -> Blade escaped output (`{{ }}`)
- CSRF -> Laravel CSRF token in all forms (`@csrf`)
- Unauthorized admin access -> `auth` + custom `admin` middleware
- Double booking under concurrent requests -> DB unique constraint on `(hairdresser_id, booking_date, start_time)` + caught unique-violation error handling

## 7. Code Standard Used
PSR-12 style is followed:
- strict types declarations
- clear namespaces/imports
- consistent class/method formatting
- meaningful names and separated responsibilities

## 8. Hosting / Deployment
Public URL: _ADD_PUBLIC_URL_HERE_

Deployment checklist:
- configure production `.env`
- run migrations and seeders
- build frontend assets
- cache config/routes/views
- use HTTPS and strong secrets
