# safaribook_website

Public site for **safaribook.africa**: a landing page for the Safaribook Virtual Accountant and a business
registration form. A standalone Laravel 10 project. It shares no code with `shulesoft_website`.

## Pages
- `/` landing page
- `/join` registration form, `POST /join` creates the client
- `/verify` one-time code entry, `POST /verify`, `POST /verify/resend`

## What sign-up does
1. Validates the form (plus honeypot / timing / link checks) and rejects duplicate name, email, phone for Safaribook.
2. Inserts a row in `admin.clients` with `project_id = 3` (Safaribook), a hashed 6-digit OTP valid 15 minutes.
3. Sends the OTP by WhatsApp (Meta OTP template first, notification service fallback) and email; rolls the row back
   if neither channel worked. Alerts `TEAM_WHATSAPP`.
4. After verification, sends the owner to `https://{username}.safaribook.africa` where the main platform
   (`shulesoft_staging`, `Install::handleSafariBookOnboarding`) runs the business setup.

Only `admin.clients` (insert/update/delete of its own unverified rows) and `constant.refer_countries` (read) are touched.

## Setup
```
composer install --no-dev -o
cp .env.example .env && php artisan key:generate
# fill DB_*, NOTIFICATION_API_*, META_WHATSAPP_*, TEAM_WHATSAPP, MAIL_*
php artisan config:cache && php artisan route:cache && php artisan view:cache
```
Point nginx `safaribook.africa` / `www.safaribook.africa` at `public/` (php-fpm). Tenant hosts
(`*.safaribook.africa`) belong to the main platform, not this project.
