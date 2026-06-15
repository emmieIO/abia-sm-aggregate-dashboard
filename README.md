## Abia Smart School Aggregate Dashboard

The Abia Smart School Aggregate Dashboard is a Laravel application for viewing statewide and per-school analytics from the Smart School SMS database. It shows enrollment, schools, students, parents, staff, alumni, discipline records, academic records, and intervention watchlists where assessment data exists.

## Requirements

- PHP 8.2 or newer
- Composer 2
- MySQL or MariaDB
- Node.js 20 or newer
- npm
- Apache, Nginx, or another PHP-capable web server

The app uses two database connections:

- `mysql`: the dashboard application's own database for users, sessions, cache, jobs, and auth.
- `abia_sms`: the source Smart School SMS database used for schools, students, parents, staff, scores, penalties, and analytics.

## Fresh Deployment

1. Clone the project.

```bash
git clone <repository-url>
cd abia-sm-aggregate-dashboard
```

2. Install PHP dependencies.

```bash
composer install --no-dev --optimize-autoloader
```

3. Create the environment file.

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure `.env`.

Set the dashboard app URL and production flags:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example
```

Set the dashboard app database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dashboard_db
DB_USERNAME=dashboard_user
DB_PASSWORD=secure-password
```

Set the SMS source database:

```env
DB_CONNECTION_ABIA_SMS=mysql
DB_HOST_ABIA_SMS=127.0.0.1
DB_PORT_ABIA_SMS=3306
DB_DATABASE_ABIA_SMS=sms_database
DB_USERNAME_ABIA_SMS=sms_user
DB_PASSWORD_ABIA_SMS=secure-password
```

Recommended production drivers:

```env
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

5. Run migrations and seed the dashboard admin user.

```bash
php artisan migrate --force
php artisan db:seed --force
```

6. Install and build frontend assets.

```bash
npm ci
npm run build
```

7. Optimize Laravel for production.

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

8. Configure the web server.

Point the web server document root to:

```text
<project-path>/public
```

Make sure PHP-FPM or the web server user can write to:

```bash
storage
bootstrap/cache
```

Typical Linux permissions:

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

9. Start the queue worker if queues are enabled.

```bash
php artisan queue:work --tries=3
```

Use Supervisor, systemd, or your hosting control panel to keep the worker running.

## Updating an Existing Deployment

Run the deployment script from the project directory after pulling new code:

```bash
./deploy.sh
```

Set `RUN_GIT_PULL=1 ./deploy.sh` if the server should pull the latest code before deploying.
Set `RUN_MIGRATIONS=0 ./deploy.sh` if migrations should be skipped for a specific deploy.

Restart PHP-FPM and queue workers if your hosting environment requires it.

## Local Development

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

## Verification Checklist

After deployment, confirm:

- Login page loads.
- A dashboard user can sign in.
- `/schools`, `/parents`, `/alumni`, and `/penalties` pages load.
- The dashboard can read the `abia_sms` database.
- `public/build/manifest.json` exists after `npm run build`.
- `storage/logs/laravel.log` has no fresh deployment errors.

Useful checks:

```bash
php artisan about
php artisan route:list --except-vendor
php artisan test --compact
```

## Data Notes

Academic analytics and the Intervention Watchlist depend on rows in:

- `exam_records`
- `exam_records_summary`

If those tables are empty for a school, the dashboard will still load, but academic performance and intervention sections will show empty states until assessment data is available.
