## install guide

to install this blueprint you need to follow the next steps:

- git clone (ssh-repo-link)
- cp .env.example .env
- php artisan key:generate
- change the .env file 
- composer install
- php artisan migrate --seed
- npm install
- laravel-echo-server configure


# To run this project

- php artisan serve
- laravel-echo-server start