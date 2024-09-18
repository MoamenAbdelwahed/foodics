## Laravel Order Service Application

This application is a service-oriented solution for managing orders, products, and ingredients. It automatically handles product stock updates, sends alerts when ingredient stock drops below 50%, and manages orders using repositories and services.

### Requirements

- Docker


## Setup Instructions

- Clone the Repository.

    ```
    git clone https://github.com/foodics.git
    cd foodics
    ```

- **Set Up Environment File Copy** the `.env.example` file to `.env` and configure your database and other settings.

- **Start Docker Containers** Use Laravel Sail to bring up the Docker environment for the application. Ensure that Docker is running, and then execute:

    ```
    ./vendor/bin/sail up -d
    ```
- **Run Migrations** To create the necessary database tables, run the following command:
    ```
    ./vendor/bin/sail artisan migrate
    ```
- **Run Seeders** To seed the database with initial data (products and ingredients), run the following command:

    ```
    ./vendor/bin/sail artisan db:seed
    ```
## Testing the Application

You can use a tool like Postman or `curl` to interact with the API.

Endpoint to Place an Order

* URL: /api/v1/orders

* Method: POST

* Content-Type: application/json

* Payload:

    ```
    {
        "products": [
            {
            "product_id": 1,
            "quantity": 2
            }
        ]
    }
    ```
* Example curl Command:

    ```
    curl -X POST http://localhost/api/v1/orders \
    -H "Content-Type: application/json" \
    -d '{
    "customer_id": 1,
    "products": [
        {
        "product_id": 1,
        "quantity": 2
        }
    ]
    }'
    ```
Expected Response
* Status: `200 OK`
* Response:
    ```
    {
       "message": "Order placed successfully"
    }
    ```
## Running Test Cases
This application includes unit tests to verify the functionality of the OrderService class, including order creation, product attachment, stock updates, and alert sending.

- **Run Test Cases** To run all the test cases, use the following command:
    ```
    ./vendor/bin/sail artisan test
    ```
This will execute the unit tests and provide feedback on whether all tests passed or failed.

## Useful Commands

- **Bring Up the Docker Environment:**
    ```
    ./vendor/bin/sail up -d
    ```
- **Stop Docker Containers:**
    ```
    ./vendor/bin/sail down
    ```
- **Run Migrations:**
    ```
    ./vendor/bin/sail artisan migrate
    ```
- **Run Seeders:**
    ```
    ./vendor/bin/sail artisan db:seed
    ```
- **Run Tests:**
    ```
    ./vendor/bin/sail artisan test
    ```

## Application Structure
The application follows a service-oriented architecture:

* **Controllers**: Handle the HTTP requests.
* **Services**: Business logic related to orders and stock management.
* **Repositories**: Handle database interactions for `Product`, `Order`, and `Ingredient`.
* **Mails**: Send stock alerts when inventory drops below the threshold.