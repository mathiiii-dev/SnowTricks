# P6 OC DA/PHP - Symfony

Develop the SnowTricks community site from A to Z

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/be8e95dcbea945d3827803f70c6b7c6c)](https://www.codacy.com/gh/mathiiii-dev/SnowTricks/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=mathiiii-dev/SnowTricks&amp;utm_campaign=Badge_Grade)

## Getting Started

These instructions will get you a copy of the project up and running on your local machine if you want to test it or develop something on it.

### Prerequisites

To make the project run you will need to install those things :

* [Laragon](https://laragon.org/download/)
* [PHP 7.4.11](https://www.php.net/releases/index.php)
* [Apache 2.4.35](http://archive.apache.org/dist/httpd/httpd-2.4.35.tar.gz)
* [MySQL 5.7.24](https://downloads.mysql.com/archives/get/p/23/file/mysql-5.7.24-winx64.zip)
* [Composer](https://getcomposer.org/download/)
* [Node.js & npm](https://nodejs.org/fr/)

### Installing

Follow those steps to make the projetc run on your machine

Clone the project :
```
git clone https://github.com/mathias73/SnowTricks.git
```
Update composer :
```
composer update
```
Install npm packages :
```
npm i
```

### Database & DataFixtures

First you can load the database with this file : 
https://drive.google.com/file/d/1p_TW3rvatb1X-5ijMKLItliKB6cY-fIS/view?usp=sharing

You can edit .env with your database credentials : 
```php
DATABASE_URL="mysql://root:@127.0.0.1:3306/SnowTricks?serverVersion=5.7"
```

You can load some data into the database : 
```
php bin/console doctrine:fixtures:load
```
## Mail

For some test with the mail, I've used gmail.

First you need to create a account password here : 

https://support.google.com/mail/answer/185833?hl=fr

And then you have to edit .env : 
```
MAILER_DSN=gmail+smtp:/email:password@default
```

Or if you don't have gmail you can use an other one like here : https://symfony.com/doc/current/mailer.html

## Admin account

With the database you have one admin accounts : 
```
Pseudo : Mathias
Password : password
```

### Launch project

You can launch the project with this command : 
```
symfony server:start
```

## Built With

* [Symfony](https://symfony.com/) - Framework PHP
* [Twig](https://twig.symfony.com/) - Template engine
* [Tailwind](https://tailwindcss.com/) - Framework CSS
* [Fontawesome](https://fontawesome.com/) - Icon library


## Versioning

For the versions available, see the [tags on this repository](https://github.com/mathias73/snowtricks/tags). 

## Authors

* **Mathias Micheli** - *Student* - [Github](https://github.com/mathias73)

