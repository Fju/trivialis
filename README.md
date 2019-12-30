# trivialis

Super simple and lightweight Content Management System especially designed for single page websites.

Trivialis features a modern and straight-forward frontend and a robust backend API, that can also be accessed programmatically.

## Development

This repository provides a `docker-compose` configuration for running the required backend servers without installing them on your local machine. When running `docker-compose up`, three containers will be created. One container will provide an Apache 2 server that serves and executes the backend PHP scripts. The other two containers run a MySQL database server and phpMyAdmin for database management.

**Important**
The `dist` directory will be mounted into the `www` container. In order for a PHP script to write a file into a directory, it is important that the `www-data` user inside the container must have the same UID as the user of the host machine. Therefore it is recommended to build the `www` using the following command (if no build argument is set, a default value of 1000 will be used).

``` sh
docker-compose build --build-arg uid=$(id -u) www
```


