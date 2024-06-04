<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

[![PHPStan](https://github.com/moitran/books-app/actions/workflows/phpstan-ci.yml/badge.svg)](https://github.com/moitran/books-app/actions/workflows/phpstan-ci.yml)
[![Pint](https://github.com/moitran/books-app/actions/workflows/pint-ci.yml/badge.svg)](https://github.com/moitran/books-app/actions/workflows/pint-ci.yml)
[![PHPUnit](https://github.com/moitran/books-app/actions/workflows/phpunit-ci.yml/badge.svg)](https://github.com/moitran/books-app/actions/workflows/phpunit-ci.yml)
[![codecov](https://codecov.io/github/moitran/books-app/graph/badge.svg?token=FKQVP99EM5)](https://codecov.io/github/moitran/books-app)


## About Books App

The Books API app is designed to aggregate and manage book data from a multitude of providers, seamlessly importing this data into a MySQL database.
Our Books API features a robust search endpoint that allows users to perform quick searches across millions of stored books.

To ensure optimal search performance, we have integrated Elasticsearch, which significantly speeds up full-text search capabilities. Additionally, we have implemented a Redis caching layer to further enhance the overall performance and efficiency of the API. With these technologies, the Books API delivers a fast, reliable, and scalable solution for accessing and managing extensive book data.

![image](https://github.com/moitran/books-app/assets/30226535/a2d9f1f3-0808-400f-a8d4-876d146a3791)


* ***Book Crawler** in step 1 has not been implemented yet in this source. For now, we are temporarily using PHP Faker to create some dummy data.*

## Performance comparison between ES & Mysql

* The performance of Elasticsearch and MySQL is nearly identical when the total number of book records is below 100,000.
* However, when the number of book records is increased to approximately 800,000:
    * Increasing ES nodes from 1 to 2 resulted in ES being three times faster than MySQL.
    * Currently, the search API applies sorting and pagination, which impacts search performance.
    * Considering scenarios where the user is typing in the search input and we implement an API for auto-suggesting books relevant to the typed keywords without sorting and pagination, I believe the search performance in ES can be significantly faster.
![image](https://github.com/moitran/books-app/assets/30226535/778ab1cd-774b-470f-b3b1-faa183763631)
![image](https://github.com/moitran/books-app/assets/30226535/bba8fd0b-7df0-448b-a81d-3e862c10a0d3)
![image](https://github.com/moitran/books-app/assets/30226535/1843b3fe-c7c0-4f9a-8f08-6d5b0fdc00cd)

MySQL CURL request
```
curl --request GET \
  --url 'http://localhost:8080/api/books?query=and&per_page=100&order_by=title&order_type=desc&page=401'
```

ES CURL request
```
curl --request GET \
  --url 'http://localhost:8080/api/books/search?query=and&per_page=100&order_by=title&order_type=desc&page=401'
```

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
    cp .env.example .env
    ```

2. Start the Docker containers:
    ```bash
    docker compose up -d --build
    ```
3. Composer install
    ```bash
    docker compose exec -it app composer install
    # Generate Laravel application keys
    docker compose exec -it app php artisan key:generate
    # clear cache & reload config
    docker compose exec -it app php artisan optimize
    ```

4. Run database migrations
    ```bash
    docker compose exec -it app php artisan migrate
    ```
    ![image](https://github.com/moitran/books-app/assets/30226535/3dbfbc67-99a7-4620-a035-5d05954690bd)

5. Run database seeding
    ```bash
    docker compose exec -it app php artisan db:seed
    ```
    ![image](https://github.com/moitran/books-app/assets/30226535/06f5ee67-5365-423b-b680-f151fc1ee9c7)


6. Perform a full sync of book data into Elasticsearch:
    ```bash
    # Publish book data into ES
    docker compose exec -it app php artisan scout:import "App\Models\Book"

    # Setting ES index.max_result_window upto a million
    docker compose exec -it app curl -X PUT "localhost:9200/my_index/_settings" -H 'Content-Type: application/json' -d'
    {
      "index" : {
        "max_result_window" : 100000
      }
    }
    '
    ```
    ![image](https://github.com/moitran/books-app/assets/30226535/4be9786e-5dab-4be7-be78-28d666212cdc)


7. Access the homepage at `http://localhost:8080`.

8. Perform API by CURL or you can perform on Swagger

    * Search by ES:

    ```
        curl --request GET \
        --url 'http://localhost:8080/api/books/search?query=and&per_page=100&order_by=title&order_type=desc&page=100' \
        --header 'User-Agent: insomnia/9.2.0'
    ```


    * Search by Mysql:
    ```
        curl --request GET \
        --url 'http://localhost:8080/api/books?query=and&per_page=100&order_by=title&order_type=desc&page=1' \
        --header 'User-Agent: insomnia/9.2.0'
    ```

9. Laravel Swagger is integrated for API specifications. You can check it out at `http://localhost:8080/api/documentation`.
![image](https://github.com/moitran/books-app/assets/30226535/6bc75562-68c3-4c11-a7d4-41ffa00ac489)

10. Application monitoring is integrated with Laravel Telescope. You can access it at `http://localhost:8080/telescope` to monitor requests, queues, Redis, etc.
![image](https://github.com/moitran/books-app/assets/30226535/0492ec20-c0f1-44df-9aa8-02443daf9d75)

11. To visualize Elasticsearch data, Kibana is integrated. You can access it at `http://localhost:5601`.
![image](https://github.com/moitran/books-app/assets/30226535/3d05b6ac-051f-44e9-a1bb-1e51a36bca6c)
