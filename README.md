# New Reading

This project is an API that manages information about authors, books, comments, reviews, users, etc. This API was built with **[Laravel](https://laravel.com/)** (a PHP framework).

## Table of Contents

- [New Reading](#new-reading)
  - [Table of Contents](#table-of-contents)
  - [Local Installation](#local-installation)
    - [Requirements](#requirements)
    - [Installation Steps](#installation-steps)
      - [Set Environment Variables And Migrate The Database](#set-environment-variables-and-migrate-the-database)
  - [Features](#features)
    - [Admin panel](#admin-panel)
      - [Seed The API](#seed-the-api)
      - [Access To The Admin Panel](#access-to-the-admin-panel)
    - [Documentation](#documentation)
    - [Admin Panel Sidebar](#admin-panel-sidebar)
    - [Roles And Permissions](#roles-and-permissions)

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

- Now, create a app key that identifies this project with a unique key. Prompt this:

```bash
php artisan key:generate
```

- When the previous steps are ready, you can continue with the following step, which is set the environment variables. 

#### Set Environment Variables And Migrate The Database

In this project, you can find a file named .env.example. This file is like a template for seting up the API. Here's some important variables:

- DB_CONNECTION: The database connection that you will use e.g pgsql for PostgreSQL
- DB_HOST: The host of the database
- DB_PORT: The port of the database. By default is 5432 for PostgreSQL
- DB_DATABASE: The name of the database
- DB_USERNAME: The username of the database
- DB_PASSWORD: The password of the database
- DEFAULT_ADMIN_NAME: The name of the default admin user
- DEFAULT_ADMIN_EMAIL: The email of the default admin user
- DEFAULT_ADMIN_PASSWORD: The password of the default admin user

You just have to change the values of DB_DATABASE, DB_USERNAME, and DB_PASSWORD. Set them to your own values. You may wish to change the default credentials of the admin user. You can do that by changing DEFAULT_ADMIN_NAME, DEFAULT_ADMIN_EMAIL, and DEFAULT_ADMIN_PASSWORD to your own values.

After setting up the environment variables, you need to run app migrations:

```bash
php artisan migrate
```

With these steps you should have installed the project locally

## Features

### Admin panel

This project has an admin panel section where you can manage all of the information related with the API. Before diving into that, we need to do somethings

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

### Documentation

As a developer you will need a simple way of consulting how this API works. Here's where Swagger comes in handy. You can generate the whole documentation of each end-point with the following command:

```bash
php artisan l5-swagger:generate
```

After runing the previous command you should see the generated documentation by visiting this address on your browser:

```bash
http://localhost:8000/api/documentation
```

### Admin Panel Sidebar

Once in the admin panel, you should see a sidebar where you will be able to create, edit, and view the information stored in the database. These are the sections that you should see:

- Authors
- Books
- Comments
- Genres
- Posts
- Reviews
- Tags
- Users
- Roles
- Permissions

### Roles And Permissions

This project hasdifferents roles and permissions. Depending on the role that user has, it has differents permissions. In this project these are the roles:

- Admin
- Author
- Editor
- Moderator
- User

To access to the admin panel you need to have some of these:

- Admin
- Editor
- Moderator

Sometimes you may wish to change a user's role. You can do that in the user section of the admin panel.