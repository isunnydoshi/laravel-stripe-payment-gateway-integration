## Laravel Stripe Payment Gateway Integration

## About Laravel

Laravel is one of the most known PHP frameworks in the market, used by millions of developers around the world. Moreover, installing Laravel is quite simple as well, and can be done within minutes.

This repository comes with **Stripe Payment Gateway Integration in Laravel 5.5** application by storing customer payment logs data in our MySQL database.

## You will need to make sure your server meets the following requirements:

- PHP >= 7.0.0
- OpenSSL PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- [xampp](https://www.apachefriends.org/download.html) OR wamp (Local system)
- [Composer](https://getcomposer.org/download/) (Laravel utilizes Composer to manage its dependencies. So, before using Laravel, make sure you have Composer installed on your machine.)
- [Git Bash](https://git-scm.com/downloads) (You can also use system command line as well)
- Nodejs (node_modules folder also contains the package dependencies related to your javascript projects. So, that you can require it in your projects. And All those packages and their dependencies are listed in package.json)

## Installation Instructions

Run following commands in Command Prompt OR Open [Git Bash](https://git-scm.com/downloads)

- Redirect to desired directory (project root folder) where you want to place your code.
- From Local machine, Start the Apache and MySQL services from xampp or wamp whatever you use.
- Run `git clone https://github.com/isunnydoshi/laravel-stripe-payment-gateway-integration.git`
- Create a MySQL database for the project.
- Copy .env.example to .env file and add your app settings and database settings in .env file: `cp .env.example .env`
- Run `composer install`. This will install all vendor dependencies and Laravel Framework.
- Configure developers account for [Stripe](https://stripe.com/) to get **STRIPE_KEY** and **STRIPE_SECRET** and also put in .env file.
- Run `php artisan key:generate`. This will generate new APP_KEY.
- Run `php artisan migrate`. This will run the [Migration](https://laravel.com/docs/5.5/migrations) and will create tables for authentication module.
- Run `php artisan serve`. This will run Laravel default web server and you can access it using `http://localhost:8000`

Done!

## Feedback

I currently made this project for personal purposes. I decided to share it here to help anyone with the same needs. If you have any feedback to improve it, feel free to make a suggestion, or open a PR!

## Official Documentation

Documentation for the framework can be found on the [Laravel website](https://laravel.com/docs/5.5).

For package: [Check Here](https://github.com/stripe/stripe-php)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
