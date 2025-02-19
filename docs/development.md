# Setup the development environment

The development environment is managed with Docker, so make sure to [install Docker](https://docs.docker.com/get-docker/).

You’ll also need an account on [Stripe](https://stripe.com/).

First, download flus.fr with Git:

```console
$ git clone --recurse-submodules https://github.com/flusio/flus.fr.git
$ cd flus.fr
```

Copy the `env.sample` file:

```console
$ cp env.sample .env
```

And adapt the `.env` file to your needs.

Initialize the database with:

```console
$ make db-setup
```

Then, start the services:

```console
$ make docker-start
```

This command calls `docker compose` with the file under the `docker/development/` folder.
The first time you call it, it will download the Docker images and build the `php` one with the information from the `docker/Dockerfile` file.

Now, you should be able to access flus.fr at [localhost:8000](http://localhost:8000).

Mailpit is configured to catch all the outgoing emails.
You can access its interface at [localhost:8025](http://localhost:8025).
