## How to Install

-   clone the repository into your local machine using `git clone https://github.com/amitleuva1987/api_webshop.git`.
-   Install dependencies using `composer install`
-   copy .env.example and create an .env file, and place the database details in .env file
-   Run `php artisan migrate` to create all database tables
-   Run `php artisan import:data` command to import the given customers and products
-   Run `php artisan serve` to start the application
-   Application is running at http://localhost:8000

## Below are the API's

-   Orders CRUD Operations APIs

    1 List all orders (GET) -> http://localhost:8000/api/orders

    2 Retrive a single order (GET) -> http://localhost:8000/api/orders/{id}

    3 Create an order (POST) -> http://localhost:8000/api/orders

    4 Update an order (PUT) -> http://localhost:8000/api/orders/{id}

    5 Delete an order (DELETE) -> http://localhost:8000/api/orders/{id}

-   Add product to an order API

    (POST) http://localhost:8000/api/orders/{id}/add

-   Pay order Endpoint

    (POST) http://localhost:8000/api/orders/{id}/pay

## API Testing

-   Run `php artisan test` to test the all API's

## Estimated and Tracked time

-   I estimated 2 hours of work for this assignment, But it took 3 hours 15 minutes to complete this assignment
