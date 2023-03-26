# PHP Website

A simple PHP website that has a home page, a product page, and a user page that can be connected to an Amazon account.

## Installation

1. Install [Composer](https://getcomposer.org/download/).

2. Install the required dependencies using Composer:
composer install

arduino
Copy code

3. Build and run the Docker container:
docker build -t php-website .
docker run -d -p 8080:80 php-website

arduino
Copy code

4. Access the website at http://localhost:80