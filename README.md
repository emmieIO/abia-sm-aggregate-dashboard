

## ABIA STATE AGGREGATOR DASHBOARD

The **ABIA State Smart School Aggregator Dashboard** is a web application that delivers comprehensive data visualization and reporting for key metrics across Abia State. Powered by Laravel, it consolidates information from diverse sources, allowing users to monitor trends, evaluate performance, and make informed decisions. With an intuitive interface and interactive charts, the dashboard empowers stakeholders to gain actionable insights into state-wide activities and outcomes.

## System Requirements
To run the ABIA State Aggregator Dashboard, ensure your system meets the following requirements:

- **PHP** >= 8.1
- **Composer** (dependency manager for PHP)
- **Laravel** >= 10.x
- **Database**: MySQL, PostgreSQL, or SQLite
- **Node.js** >= 16.x and **npm** (for frontend asset compilation)
- **Web Server**: Apache, Nginx, or Laravel's built-in server

For detailed setup instructions, refer to the [Laravel documentation](https://laravel.com/docs).

##  Installation Guide


To install and set up the ABIA State Aggregator Dashboard, the DevOps team should follow these steps:

1. **Clone the Repository**
    ```bash
    git clone https://github.com/your-username/abia-aggregate-dashboard.git
    cd abia-aggregate-dashboard
    ```

2. **Install PHP Dependencies**
    ```bash
    composer install
    ```

3. **Set Up Environment Configuration**
    ```bash
    cp .env.example .env
    ```
    Update the `.env` file with database credentials, mail settings, and other environment variables as required for your infrastructure.

4. **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

5. **Run Database Migrations and Database Seed**
    ```bash
    php artisan migrate --seed
    ```

6. **Install Node.js Dependencies and Build Frontend Assets**
    ```bash
    npm install
    npm run build
    ```

7. **Configure Web Server**
    - For **Apache** or **Nginx**, point the document root to the `public` directory.
    - Ensure proper permissions for storage and bootstrap/cache directories:
      ```bash
      chmod -R 775 storage bootstrap/cache
      chown -R www-data:www-data storage bootstrap/cache
      ```

8. **Start the Application**
    - For development:
      ```bash
      php artisan serve
      ```
      Access the dashboard at `http://localhost:8000`.
    - For production, use your web server to serve the application and configure SSL as needed.

9. **Monitor and Maintain**
    - Set up log rotation and monitoring for application health.
    - Regularly update dependencies and apply security patches.




