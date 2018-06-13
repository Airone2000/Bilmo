# Bilmo Platform

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
11. You can alse run the entire test by running `vendor/bin/behat` (Beware, counting may differs in ALLINONE.feature due to previous tests).