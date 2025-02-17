# News Aggregator Application - Installation Guide

This guide provides step-by-step instructions to set up Laravel backend for the News Aggregator application.

---

## Backend (Laravel API)

### Prerequisites

Ensure you have the following installed:

- PHP (^8.2)
- Composer
- MySQL (8.2.0)
- Laravel (^11.31)
- Docker (Optional)

### Installation Steps

1. **Clone the repository**

   ```sh
   git clone https://github.com/your-repository/news-aggregator-backend.git
   cd news-aggregator-backend
   ```

2. **Install dependencies**

   ```sh
   composer install
   ```

3. **Copy environment file and configure**

   ```sh
   cp .env.example .env
   ```

   Update the `.env` file with your database credentials:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=news_aggregator
   DB_USERNAME=root
   DB_PASSWORD=yourpassword
   ```

4. **Generate application key**

   ```sh
   php artisan key:generate
   ```

5. **Run database migrations**

   ```sh
   php artisan migrate
   ```

6. **Run the Laravel server**

   ```sh
   php artisan serve
   ```

   The API will be available at `http://127.0.0.1:8000/api`

7. **(Optional) Run using Docker**

   ```sh
   docker-compose up -d
   ```

---

## Additional Notes

- Ensure that both backend and frontend are running concurrently.
- If using Docker, update the `.env` file for both Laravel and React accordingly.
- CORS should be properly handled in Laravel if accessing from a different origin.
- API routes should be prefixed with `/api/` in the frontend requests.

Now your News Aggregator application should be up and running!

