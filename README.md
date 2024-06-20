## Usage

To get started, make sure you have [Docker installed](https://docs.docker.com/docker-for-mac/install/) on your system, and then clone this repository.

Next Clone the current project

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




