## **Installation Guide**

### -**Setup and configuration**
    1) git clone https://github.com/Arijit000/demoproject.git
    2) create database in mysql "demoproject"
    3) update mysql database connection configuration details in .env file in below section
        DB_DATABASE=
        DB_USERNAME=
        DB_PASSWORD=
    4) composer update
    5) php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    6) php artisan migrate
    7) php artisan storage:link
    8) php artisan config:cache
    9) php artisan server --port=8000
    10) http://127.0.0.1:8000/