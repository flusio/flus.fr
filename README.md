# flus.fr

This is the repository of the website [flus.fr](https://flus.fr).

If you’re looking for the code of [app.flus.fr](https://app.flus.fr), it’s in a
different repository: [flusio/Flus](https://github.com/flusio/Flus).

flus.fr is the entrance of my paid service based on Flus. It is intended to
French people, thus the website is only translated in French.

flus.fr is licensed under [AGPL 3](https://github.com/flusio/flus.fr/blob/main/LICENSE.txt).

## Setup the development environment

The development environment is managed with Docker and PHP in CLI mode. It
requires PHP 8.2 to work properly.

You’ll also need an account on [Stripe](https://stripe.com/).

Make sure to [install Docker](https://docs.docker.com/get-docker/).

First, download flus.fr with Git:

```console
$ git clone --recurse-submodules https://github.com/flusio/flus.fr.git
$ cd flus.fr
```

Copy the `env.sample` file:

```console
$ cp env.sample .env
```

And adapt the `.env` file to your needs. The `SMTP_` variables should be set to
be used with an existing email account.

Initialize the database with:

```console
$ make setup
```

Then, start the services:

```console
$ make docker-start
```

This command calls `docker compose` with the file under the `docker/` folder.
The first time you call it, it will download the Docker images and build the
`php` one with the information from the `docker/Dockerfile` file.

Now, you should be able to access flus.fr at [localhost:8000](http://localhost:8000).

## Deploy in production

Installing flus.fr on your own server is quite simple but still requires basic
notions in sysadmin. First, make sure you match with the following
requirements:

- Git, Nginx, PHP 8.2 are installed on your server;
- PHP requires `intl` and `pcntl` extensions;
- flus.fr must be served over <abbr>HTTPS</abbr>.

**Other configurations might work but aren’t officialy supported.**

If you don’t know how to configure HTTPS, you should check if your server
provider doesn’t already configures one for you. If not, you can get one for
free with [Let’s Encrypt `certbot` client](https://certbot.eff.org/). This
documentation isn’t intended to teach you how to use it though.

First, start by getting the code with Git (you might need to run the commands
as the `root` user):

```console
# cd /var/www/
# git clone --recurse-submodules https://github.com/flusio/flus.fr.git
# cd flus.fr
```

You must now configure the environment by creating the `.env` file:

```console
# cp env.sample .env
# vim .env # or edit with nano or whatever editor you prefer
```

The environment file is commented so it should not be too complicated to setup
correctly.

The SMTP information should be given to you by your email provider. If you
don’t have an address to send emails, just set `APP_MAILER` to `mail` and
`SMTP_FROM` with an address corresponding to your domain. The other lines can
be commented or deleted. This is not recommended though.

You should set the owner of the files to the user that runs Nginx. This is
often `www-data`:

```console
# chown -R www-data:www-data .
```

You should also change the permissions on the `.env` file to limit the risks of
credentials being stolen. The `www-data` user only needs `read` permission:

```console
# chmod 400 .env
```

You must now load the SQL schema to your database. You can do it with:

```console
# sudo -u www-data make setup NODOCKER=true
```

If the permissions are correct, you should have a message to tell you the
system has been initialized. If an error should occur during this installation,
this is probably where it will happen!

Finally, we have to configure Nginx.

**Make sure to have your domain served over HTTPS by Nginx.**

Then, you must configure your Nginx server. Here’s an example:

```nginx
server {
    listen 80;
    listen [::]:80;

    # This must match the `APP_HOST` variable
    server_name example.com;

    # Redirect all HTTP requests to HTTPS with a 301 Moved Permanently response.
    # If you’re not sure of what you’re doing, you should settle for 302
    return 301 https://$host$request_uri;
}

server {
    # Configure HTTP2 to listen on HTTPS port for both IPv4 and IPv6
    # The port must match the `APP_PORT` variable
    listen 443 ssl http2;
    listen [::]:443 ssl http2;

    # This must match the `APP_HOST` variable
    server_name example.com;

    # Please note that we serve the public/ folder, it **must not** be set
    # directly to the flus.fr root folder!
    root /var/www/flus.fr/public;
    index index.html index.php;

    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;

    location / {
        # This tries to serve the file under the public/ folder first, then if
        # it doesn’t exist, it redirects the request to the index.php file
        try_files $uri $uri/ /index.php$is_args$query_string;
    }

    location ~ index.php$ {
        # Please refer to the official Nginx documentation if you have any doubt
        # https://www.nginx.com/resources/wiki/start/topics/examples/phpfcgi/
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        include fastcgi.conf;
    }

    # Your HTTPS certificate paths provided either by your provider or certbot
    ssl_certificate /etc/letsencrypt/live/your-domain/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain/privkey.pem;
}
```

Obviously, you can adapt this file to your needs and add any optimization you
think that you might need of.

Let’s check that your configuration is valid with `nginx -t` and reload Nginx
with `systemctl reload nginx`.

If you’ve done everything right, you should now be able to access flus.fr at
the address you’ve configured, congratulations!

## Update flus.fr

This is quite simple, but please note that I don't maintain a changelog, nor
migration notes. You may break things if you’re not me :). Also, always make a
backup of your data before performing an update.

Then, you can pull the new code from GitHub:

```console
$ git status # check that you didn't make any change in your working directory
$ git pull --recurse-submodules
```

**In production,** you should change the owner of the files:

```console
# chown -R www-data:www-data .
```

**In development,** don’t prefix the following commands with `sudo -u www-data`.

Then, apply the migrations:

```console
$ sudo -u www-data make setup NODOCKER=true
```

If the migrations go wrong and you need to reset the application to
its previous state, you can reverse the migrations with:

```console
$ sudo -u www-data make rollback STEPS=1 NODOCKER=true
```

You can increase `STEPS` to rollback more migrations (its default value is `1`
so its optional).

That’s all!

Obviously, if you made changes in your own working directory, things might not
go so easily. Please always check the current status of the Git repository.

## Setup the Jobs Watcher

To work fully properly, you’ll need to setup the Jobs Watcher.
It is in charge of executing some jobs in background, such as completing the
paid payments, or sending reminders emails.

The preferred way is to use systemd.
For instance, create the file `/etc/systemd/system/flusfr-worker.service`:

```systemd
[Unit]
Description=A job worker for flus.fr

[Service]
ExecStart=php /var/www/flus.fr/cli jobs watch
User=www-data
Group=www-data

Restart=on-failure
RestartSec=5s

[Install]
WantedBy=multi-user.target
```

Then, reload the systemd daemon and start the service:

```console
# systemctl daemon-reload
# systemctl enable flusfr-worker
# systemctl start flusfr-worker
```

Each time you update the app, remember to restart the service:

```console
# systemctl restart flusfr-worker
```

An alternative is to setup a cron task:

```cron
* * * * * www-data php /var/www/flus.fr/cli jobs watch --stop-after=5 >>/var/log/flusfr-watcher.txt 2>&1
```

## Execute the tests and linters

First, install the dev dependencies with:

```console
$ make install
```

Execute the linters with:

```console
$ make lint
$ # OR to fix issues
$ make lint-fix
```

To execute the tests, execute:

```console
$ make test
```
