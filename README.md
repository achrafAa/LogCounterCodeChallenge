## Usage

To get started, make sure you have [Docker installed](https://docs.docker.com/docker-for-mac/install/) on your system, and then clone this repository.

1. Clone the current project

then, navigate in your terminal to the directory you cloned this, and spin up the containers for the web server by running `docker compose up -d --build webapp`.

The following are built for our web server, with their exposed ports detailed:

- **nginx** - `:8080`
- **mysql** - `:3306`
- **php** - `:9000`


then install the needed dependencies by running the following commands

- `docker compose run --rm composer install`

...


## Steps

1- Run the migration for both test and dev:

- `docker exec -it php sh -c "php bin/console d:m:m --env test" `
- `docker exec -it php sh -c "php bin/console d:m:m" `

2- Run Tests : 

- `docker exec -it php sh -c "php bin/phpunit"`



## versions :

V1 : 
- Checkout the first version solution:
    `git checkout solution-v1`

### Features Implemented in V1:

-   **Reading Pointer Management:**
    -   Implemented to manage the position of the reading pointer using a file-based approach.
-   **Data Storage:**
    -   Data is stored in MySQL database.
-   **Queue Management:**
    -   Messaging is handled using the Messenger component with database transport.

### Goals Achieved in V1:

-   **Functionality Assurance:**
    -   Ensures all core functionalities work correctly.
-   **Initial Implementation:**
    -   Provides a foundational snapshot of the solution's quality and functionality.

### Next Steps (V2):

Moving forward, the focus will be on optimizing the code and infrastructure with the following enhancements:

-   **Data Storage Optimization:**
    -   Integrate OpenSearch for efficient data storage.
-   **Queue Management Enhancement:**
    -   Implement RabbitMQ for improved queue management.
-   **Caching Strategy:**
    -   Utilize Redis for caching the last position, replacing the current file-based approach.

### Note:

Due to time constraints, these enhancements will be addressed in the upcoming versions to avoid over-engineering and ensure timely delivery.

Feel free to reach out if you have any questions or need further clarification!