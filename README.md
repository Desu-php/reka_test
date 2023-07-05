# Reka test

## Install for development

### Requirements:
- php 8.1+
- mysql 8
- node.js 16+


### Run in terminal:

```shell
# install composer packages
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
npm install
npm run dev
php artisan serve
```

### URLs:
- Website: http://127.0.0.1:8000
