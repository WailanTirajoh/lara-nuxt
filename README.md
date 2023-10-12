# lara-nuxt
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/ab4f28fc-ee63-47d8-aa13-f719544057e1)
Monorepo fullstack apps build with Laravel API & Nuxt 3 FE

## Installation
### Backend Terminal
```
cd api
composer install
cp .env.example .env
php artisan migrate --seed
php artisan serve
```

### Frontend Terminal
```
cd cms
pnpm install
pnpm dev
```

### Login
- open UI at http://localhost:3000
- login credentials _(Look at backend AppSeeder.php)_
```
email: wailantirajoh@gmail.com
password: wailan
```

## UI Preview
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/22281f0b-608e-4767-91fd-b86c6f4ef548)
![image](https://github.com/WailanTirajoh/lara-nuxt/assets/53980548/d7955974-dbf5-4c48-afdd-c064c260d04f)
