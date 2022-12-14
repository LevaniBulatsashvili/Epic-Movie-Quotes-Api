# Epic-Movie-Quotes-API

---

Before getting started checkout [Epic-Movie-Quotes](https://github.com/RedberryInternship/levan-bulatsashvili-epic-movie-quotes) for frontend implementation <br>
Epic Movie Quotes API App allows users to authenticate their accounts, create, get, update and even delete movies and their associated quotes. additionally users can comment, like and get notifications using our API

---

## Table of Contents

-   [Prerequisites](#prerequisites)
-   [Tech Stack](#tech-stack)
-   [Getting Started](#getting-started)
-   [Migrations](#migrations)
-   [Development](#development)
-   [DrawSQL](#drawsql)

---

## Prerequisites

-   PHP@8.0.2 and up
-   MYSQL@14.14 and up
-   npm@6.14.17 and up
-   composer@2.4.2 and up

---

## Tech Stack

-   [vue@3.x](https://vuejs.org/) front framework
-   [Laravel@9.x](https://laravel.com/docs/9.x) back and front framework
-   [Spatie Translatable](https://github.com/spatie/laravel-translatable) - package for translation

---

## Getting Started

1. First of all you need to clone Epic-Movie-Quotes-Api repository from github:

```
git clone https://github.com/RedberryInternship/levan-bulatsashvili-epic-movie-quotes-api
```

2. Next step requires you to run composer install in order to install all the dependencies.

```
composer install
```

3. after you have installed all the PHP dependencies, it's time to install all the JS dependencies:

```
npm install
```

and also:

```
npm run build
```

in order to build your JS/CSS resources.

4. Now we need to set our env file. Go to the root of your project and execute this command.

```
cp .env.example .env
```

And now you should provide .env file all the necessary environment variables:

---

## MYSQL:

> PUSHER_APP_ID=**\***

> PUSHER_APP_KEY=**\***

> PUSHER_APP_SECRET=**\***

> FRONTEND_URL =**\***

> PUSHER_APP_CLUSTER=**\***

> GOOGLE_CLIENT_ID=**\***

> GOOGLE_CLIENT_SECRET=**\***

> FULL_FRONTEND_URL=**\***

> FRONTEND_URL =**\***

> GOOGLE_REDIRECT=**\***

> DB_PASSWORD=**\***

> JWT_SECRET=**\***

after setting up .env file, execute:

```
php artisan config:cache
```

in order to cache environment variables.

5. We also need to create public storage link

```
php artisan storage:link
```

6. Now execute in the root of you project following:

```
php artisan key:generate
```

Which generates auth key.

## Migrations

if you've completed getting started section, then migrating database if fairly simple process, just execute:

```
php artisan migrate
```

## Development

finally:

run Laravel's built-in development server by executing:

```
php artisan serve
```

---

## DrawSQL

Visit database structure here: [DrawSQL](https://drawsql.app/teams/redberry-31/diagrams/epic-movie-quotes)
