# Bilmo Platform

## About

Bilmo provides you with some APIs to manage your business of phones.

As our Partner, you are what we call "an App". This way, you're given an APP_ID & APP_SECRET that allows you to consume the Bilmo-API.

Once you've been registered at us, you can:

- Get the complete list of our products, their customers, their categories.
- Manage your users.

## Documentation (Partner)
At your registration with Bilmo, you've been given a complete documentation of what can be achieve using our APIs. Please, refer to it for more details about requests and responses.

The following is just a quick overview.

### Manufacturer
- GET /manufacturers
- GET /manufacturers/{id}

### Category
- GET /categories
- GET /categories/{id}

### Product
- GET /products
- GET /products/{id}

### User
- GET /users
- POST /users
- GET /users/{id}
- PUT /users/{id}
- DELETE /users/{id}

## Install, DB, running tests
1. First, create your .env & behat.yml based on the existing .dist files.
2. `composer install` to install your dependencies.
3. `php bin/console d:d:c` to create your sqlite db.
4. `php bin/console d:s:c` to mount your schema.
5. `php bin/console d:f:l` to fill your db with some data.
6. `openssl genrsa -out var/jwt/private.pem -aes256 4096` to generate the private JWT ssh key.
7. `openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem` to generate the public JWT ssh key.
8. Store the pass phrase you've just entered in your .env (JWT_PASSPHRASE).
9. For testing, switch to the dev environment (.env) and then run the embedded PHP web server: `php bin/console server:start`.
10. Switch back to the test environment and run `vendor/bin/behat --tags=allinone`.
11. You can also run the entire test by running `vendor/bin/behat` (Beware, counting may differs in ALLINONE.feature due to previous tests).