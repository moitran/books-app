<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
</p>

## About Books App

The Books API app is designed to aggregate and manage book data from a multitude of providers, seamlessly importing this data into a MySQL database.
Our Books API features a robust search endpoint that allows users to perform quick searches across millions of stored books.

To ensure optimal search performance, we have integrated Elasticsearch, which significantly speeds up full-text search capabilities. Additionally, we have implemented a Redis caching layer to further enhance the overall performance and efficiency of the API. With these technologies, the Books API delivers a fast, reliable, and scalable solution for accessing and managing extensive book data.

## Prerequisites

- Docker >=25
- PHP 8.2
- MySQL 8
- Elasticsearch 8.13.4
- Kibana 8.13.4
- Redis 7.2.5
- Nginx 1.25.5

## How to Run on a Local Machine

1. Clone the repository:
    ```bash
    git clone git@github.com:moitran/books-app.git
    cd books-app
    ```

2. Start the Docker containers:
    ```bash
    docker compose up -d
    ```

3. Run database migrations and seed dummy book data:
    ```bash
    docker compose exec -it app php artisan migrate:refresh --seed
    ```

4. Perform a full sync of book data into Elasticsearch:
    ```bash
    docker compose exec -it app php artisan scout:import "App\Models\Book"
    ```

5. Access the homepage at `http://localhost:8080`.

6. Application monitoring is integrated with Laravel Telescope. You can access it at `http://localhost:8080/telescope` to monitor requests, queues, Redis, etc.

7. To visualize Elasticsearch data, Kibana is integrated. You can access it at `http://localhost:5601`.

8. Laravel Swagger is integrated for API specifications. You can check it out at `http://localhost:8080/api/docs`.
