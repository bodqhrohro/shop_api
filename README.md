shop_api
========

A Symfony project providing an API with one request for retrieving shop items.

Installation
============

* clone the repository and `cd` to its directory
* create the database and set it up in `app/config/parameters.yml`
* run `php bin/console doctrine:migrations:migrate`

Setup JWT:
```
openssl genrsa -out app/config/jwt/private.pem 4096
openssl rsa -pubout -in app/config/jwt/private.pem -out app/config/jwt/public.pem
cp app/config/jwt.yml.dist app/config/jwt.yml
```
Edit `app/config/jwt.yml` if needed.

If you need the preset with random data:
* run `vendor/fakerino/fakerino/build/ods vendor/fakerino/fakerino/data`
* run `php bin/console doctrine:fixtures:load`

Obtaining a token
=================

```
php bin/console shop_api:token-generate
```
