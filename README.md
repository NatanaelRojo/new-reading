# New Reading

This project is an API that manages information about authors, books, comments, reviews, users, etc. This API was built with **[Laravel](https://laravel.com/)** (a PHP framework).

## Local Installation

### Requirements

To install locally this project on you machine, you need:

- Composer 2.7 (or above)
- git 2.34 (or above)
- Laravel 11.x (or above)
- PHP 8.3 (or above)
- A database manager e.g PostgreSQL

### Installation Steps

- Clone the repo with this command:

```bash
git clone <repo_url>
```

- Move to the folder that was created in the previous step
- Once there, you can install PHP dependencies with the following command:

```bash
composer install
```

- In this moment we need to create something named .env file. The app uses this file for some base configurations. Enter this in your command line:

```bash
cp .env.example .env
```

- Now, create a app key that indentifies this project with a unique key. Prompt this:

```bash
php artisan key:generate
```

- When the previous steps are ready, you can continue with the following step, which is set the environment variables. 

#### Set Environment Variables

In this project, you can find a file named .env.example. This file is like a template for seting up the API. Here's some important variables:

- DB_CONNECTION: The database connection that you will use e.g pgsql for PostgreSQL
- DB_HOST: The host of the database
- DB_PORT: The port of the database. By default is 5432 for PostgreSQL
- DB_DATABASE: The name of the database
- DB_USERNAME: The username of the database
- DB_PASSWORD: The password of the database
You just have to change the values of DB_DATABASE, DB_USERNAME, and DB_PASSWORD. Set them to your own values.

With these steps you should have installed the project locally

## Features

### Admin panel

This project has an admin panel section where you can manage all of the information related with the API. Before diving in that, we need to do somethings

#### Seed The API

You may wish to access to the admin panel, but not have to create data from scratch. For these cases, seeders were created. To seed the database, enter this in the command line:

```bash
php artisan db:seed
```

This command will populate the database with some random data, and when you access to the admin panel, you'll see the outcomes

#### Access To The Admin Panel

When you ran seeders in the previous section, a default admin user was created. So you now login with those credentials. Open your browser and enter this address on the search bar:

```bash
http://localhost:8000/ad/login
```

Fill out the fields with the default credentials:

Admin email: admin@admin.com
Admin password: admin.123456

After filling out the fields, press on the login button. You should see the admin panel interface