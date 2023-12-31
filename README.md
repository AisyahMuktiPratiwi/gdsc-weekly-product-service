# Product Service

---

### Run

1. Clone the repository

2. Install dependencies

   ```bash
   composer install
   ```

3. Duplicate the .env.example file to .env and set up  database configuration here:

    ```bash
    cp .env.example .env
    ```

4. Generate App Key and Run Migrations

   ```bash
   php artisan key:generate
   php artisan migrate
   ```
5.Running the Server

   ```bash
   php artisan serve --port=5002
   ```
   
