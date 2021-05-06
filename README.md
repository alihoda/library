# Library Backend

## How to run

1. Install composer on your system.
2. Go to project directory.
3. Run `composer install` to install packages.

The project runs on docker, so you need docker installed on your os. After that, just run `./vendor/bin/sail up -d` to run containers. Now the backend ready on **localhost** port **80**.

### Sail Commands

| Command                 | Description                                            |
| ----------------------- | ------------------------------------------------------ |
| sail up -d              | Run server and containers                              |
| sail db:seed            | Seeding Database                                       |
| sail db:seed -n         | Seeding database with default values                   |
| sail artisan route:list | Show route table                                       |
| sail down               | Shutdown server and containers                         |
| sail down -v            | Shutdown server and containers and also remove volumes |

> All `sail` keyword in the table actually is `./vendor/bin/sail`.

The **phpmyadmin** runs on `localhost:8080` with `server = mysql` and `username = root`. There is no need for password, just fill **server** and **username** fields and press **Go**.
