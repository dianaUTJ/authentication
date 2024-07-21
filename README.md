# Laravel Filament Application

## Description

This is a Laravel application that uses the Filament admin panel for managing users, products, and payments. It includes a robust system for managing user roles and permissions. It integrates with the Stripe API for handling payments.

## Features

- User Management: Create, update, and delete users.
- Product Management: Add, edit, and remove products.
- Payment Management: Track and manage payments using Stripe.
- Role-Based Access Control: Assign roles and permissions to users to control access to various parts of the application.

## Requirements

- PHP >= 8.3: Laravel and Filament require a PHP version of 8.3 or higher.
- Composer: This is a tool for dependency management in PHP. It allows you to declare the libraries your project depends on and it will manage (install/update) them for you.
- Node.js and npm: These are required for compiling assets with Laravel Mix.
- Laravel Herd >= 1.7.1 : This is used to manage your server.
- DBngin: A native application which helps you easily bootstrap a database server.
- TablePlus: A native application which helps you easily edit database contents and structure in a
clean, fluent manner.
- Stripe Account: Since the application uses Stripe for payment processing, you'll need a Stripe account and API keys.


## Installation

[Provide instructions on how to install and set up your project.]
1. Install and set up [Laravel Herd](https://github.com/calebporzio/laravel-herd) to manage your server.
2. Install and set up [DBngin](https://dbngin.com/) and [TablePlus](https://tableplus.com/) to manage your database.
3. Clone the repository to your local machine on the Herd folder using `git clone https://github.com/username/projectname.git myprojectname`.
4. Navigate to the project directory with `cd myprojectname`.
5. Install composer dependencies with `composer install`.
6. Copy the `.env.example` file to a new file named `.env` with `cp .env.example .env`.
7. Generate an application encryption key with `php artisan key:generate`.
8. Create a database for the application.
9. In the .env file add database information and the modify the following:
    APP_URL= [URL of your project]
    APP_LOCALE= es
    If using mysql:
    DB_CONNECTION= mysql
    Uncomment: DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD and change them to match your own database information.
    Change from QUEUE_CONNECTION=database to QUEUE_CONNECTION=sync
10. Run the database migrations with `php artisan migrate`.
11. Start the local development server with `npm run dev`. The application will be available at `http://projectName.test/admin`.

## Usage

After starting the server with Laravel Herd, navigate to `http://projectName.test/admin` in your web browser. 
Register for an account on filament with
`php artisan make:filament-user` then log in to start managing users, products, and payments. Use the Filament admin panel to manage roles and permissions. Payments are handled through Stripe, so you'll need to configure your Stripe API keys in the `.env` file.

Manage your database using DBngin and TablePlus. Refer to their respective documentation for detailed usage instructions.




