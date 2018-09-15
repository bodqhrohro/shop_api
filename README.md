shop_api
========

A Symfony project providing an API with one request for retrieving shop items.

Installation
============

* clone the repository
* create the database and set it up in `app/config/parameters.yml`
* run `php bin/console doctrine:migrations:migrate`

If you need the preset with random data:
* run `vendor/fakerino/fakerino/build/ods vendor/fakerino/fakerino/data`
* run `php bin/console doctrine:fixtures:load`
