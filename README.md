# AsiaYo Pretest

This repository contains a PHP Laravel-based API for currency conversion.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [License](#license)

## Installation

### Prerequisites

Before getting started, ensure you have the following installed:

- PHP
- Composer
- Docker
- Docker Compose

### Using 'php artisan serve'

1. Clone this repository to your local environment:

   ```sh
   git clone https://github.com/JasonWu070721/asiayo-pretest-laravel.git
   cd asiayo-pretest-laravel
   ```

2. Install the Laravel dependencies:

   ```sh
   composer install
   ```

3. Copy the .env.example file:

   ```sh
   cp .env.example .env
   ```

4. Generate an application key:

   ```sh
   php artisan key:generate
   ```

5. Start the development server:

   ```sh
   php artisan serve
   ```

The API will now be accessible at http://localhost:8000/convert?source=USD&target=JPY&amount=$1,525

### Using Docker Compose

1. Clone this repository to your local environment:

   ```sh
   git clone https://github.com/JasonWu070721/asiayo-pretest-laravel.git
   cd asiayo-pretest-laravel
   ```

2. Generate Nginx keys:

   ```sh
   chmod +x ./gen_app_key.sh
   ./gen_nginx_key.sh
   ```

3. Start the Docker containers:

   ```sh
   docker-compose up --build
   ```

The API will be accessible at http://localhost:3000/convert?source=USD&target=JPY&amount=$1,525

## Usage

To use the API, make a GET request to the following endpoint:

```text
GET /api/v1/currency/convert?source=USD&target=JPY&amount=$1,525
```

- source: Source currency code (e.g., USD)
- target: Target currency code (e.g., JPY)
- amount: Amount to convert (with commas and a dollar sign)

## API Documentation

Swagger documentation is available at:

```sh
http://localhost:8000/api/documentation
```

This interactive UI provides detailed information about the API endpoints and allows you to test them directly.

## Testing

### Feature Tests

To run the Feature Tests, execute the following command:

```sh
php artisan test --feature
```

### Unit Tests

To run the Unit Tests, execute the following command:

```sh
php artisan test --unit
```

## License

This project is licensed under the AsiaYo License.
