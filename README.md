# lara-nuxt
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/ab4f28fc-ee63-47d8-aa13-f719544057e1)
Monorepo fullstack apps build with Laravel API & Nuxt 3 FE

## Installation
### Requirement
```
- wsl (For windows user)
- docker
- bun
- php8.1
- composer
```
- Why php8.1 & composer is required? because we're mounting laravel (api) to nginx.
- Why Bun? for FE development. (You can choose any for eg pnpm, yarn other than bun. personal preference)
- **TODO**: Create API & FE Image for production.
### 1. Git Clone
```
git clone https://github.com/WailanTirajoh/lara-nuxt.git
cd lara-nuxt
```
### 2. Setup API
```
cd api
cp .env.example .env
composer install
php artisan key:generate
cd ..
```
### 3. Build Docker
```
# Build docker
docker compose up -d

# Database Migration
docker compose exec php php /var/www/html/artisan migrate --seed

# Chown
docker compose exec php chown www-data:www-data -R /var/www/html/storage
```

By following this CLI steps, we can open http://localhost:8080 on browser to see API up and running.

### 4. Setup FE
```
cd cms
cp .env.example .env
bun install
bun run dev -o
```

### Login
- open CMS UI at http://localhost:3000
- login credentials _(Look at api AppSeeder.php)_
```
email: wailantirajoh@gmail.com
password: wailan
```

### phpmyadmin
open http://localhost:8081
```
# Login Credentials
username: homestead
password: secret
```

## UI Preview
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/22281f0b-608e-4767-91fd-b86c6f4ef548)
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/d7955974-dbf5-4c48-afdd-c064c260d04f)
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/382bcdaa-792b-4764-b692-99c938e127fb)
