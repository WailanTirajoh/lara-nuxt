# lara-nuxt
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/ab4f28fc-ee63-47d8-aa13-f719544057e1)
Monorepo fullstack apps build with Laravel API & Nuxt 3 FE

## Requirement
1. docker + docker compose
2. npm / pnpm / yarn / bun (for FE package manager) _*this will be replaced latter when fe image created._

## Installation
### 1. Git Clone
```
git clone https://github.com/WailanTirajoh/lara-nuxt.git
cd lara-nuxt
```

### 2. Build Docker Images
```
# Build docker
docker compose up -d
```

### 3. Setup API
```
# Get into php container shell
docker compose exec php sh

# Setup
cp .env.example .env
composer install
php artisan key:generate

# DB Seed
php artisan migrate --seed

# Permission
chown www-data:www-data -R storage

# Storage Link
php artisan storage:link

# Quit from php sh
exit
```
By following this CLI steps, we can open http://api.localhost on browser to see API up and running.

### 4. Setup FE for development
```
# Currently no image for FE, todo latter. We can install FE using any package manager, i'm using bun here since its super fast.
# You can use npm / pnpm / yarn other than bun.

cd cms
cp .env.example .env
bun install
bun run dev -o
```

And then open localhost:3000 for development, for production we have image running at http://cms.localhost

### Login
- open CMS UI at localhost:3000 or http://cms.localhost
- login credentials _(Look at api AppSeeder.php)_
```
email: wailantirajoh@gmail.com
password: wailan
```

### API Tests
```
docker compose exec php php artisan test --coverage --testsuite=Feature

# OR with php unit

docker compose exec php vendor/bin/phpunit --coverage-html reports/ --testsuite Feature
```

### Lint Fix
```
docker compose exec php vendor/bin/pint
```

### phpmyadmin
open http://phpmyadmin.localhost
```
# Login Credentials
username: homestead
password: secret
```

### Entity Relationship Diagram
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/aaa4167c-2e17-4b87-9503-12d317073f2d)


## UI Preview
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/22281f0b-608e-4767-91fd-b86c6f4ef548)
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/d7955974-dbf5-4c48-afdd-c064c260d04f)
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/382bcdaa-792b-4764-b692-99c938e127fb)
