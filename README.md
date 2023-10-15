# lara-nuxt
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/ab4f28fc-ee63-47d8-aa13-f719544057e1)
Monorepo fullstack apps build with Laravel API & Nuxt 3 FE

## Installation
### Docker
```
# Copy the env.example to .env
cd api
cp .env.example .env
cd ..

# Build docker
docker compose up --build

# Database Migration
docker compose exec php php /var/www/html/artisan migrate
```

By following this CLI steps, we can open http://localhost:8080 on browser to see API up and running.

### Development on Frontend Terminal
```
cd cms
pnpm install
pnpm dev
```

### Login
- open CMS UI at http://localhost:3000
- login credentials _(Look at api AppSeeder.php)_
```
email: wailantirajoh@gmail.com
password: wailan
```

## UI Preview
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/22281f0b-608e-4767-91fd-b86c6f4ef548)
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/d7955974-dbf5-4c48-afdd-c064c260d04f)
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/382bcdaa-792b-4764-b692-99c938e127fb)
