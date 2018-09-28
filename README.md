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
Tokens live one week right after generation.

Working with API
================

The API has a single root request, `/`. The input should be a POST request with an `Authorization` header with a valid JWT token. The input body should be a JSON of the following structure:
```
{
    "price": {
        "min": "30000",
        "max": "31000"
    },
    "name": {
        "sort": "DESC"
    },
    "date": {
        "min": "2017-05-30",
        "max": "2017-08-17",
        "sort": "ASC"
    },
    "count": 10
}
```
* All input fields and subfields are optional.
* Dates should have `YYYY-MM-DD` format.
* Valid values for sorting are `ASC` and `DESC`.
* `count` parameter specifies the maximum number of outputted entries.

The successful output is a JSON array:
```
[
    {
        "id": 351518,
        "name": "Zoologists Managers Teachers",
        "price": 30747,
        "created_at": "2017-12-13T10:12:06.000+01:00"
    },
    {
        "id": 359754,
        "name": "Writers Manufacturing Production Technicians Camera Operators",
        "price": 30641,
        "created_at": "2017-11-06T21:32:33.000+01:00"
    },
    ...
]
```

If an error occurs, a message and an error code are returned:
```
{
    "error": {
        "message": "DEC: The value you selected is not a valid choice.",
        "code": 0
    }
}
```

Test server
===========

For a while, a test server is available: http://94.177.207.80:19816/

You can use this token for authorization: `eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjhhMjFjNDU5NGQzYjQwODBkODdlZTU4Y2NhM2IwM2Q0In0.eyJpc3MiOiJib2RxaHJvaHJvIiwiYXVkIjoiYXNnYXJ0IiwianRpIjoiOGEyMWM0NTk0ZDNiNDA4MGQ4N2VlNThjY2EzYjAzZDQiLCJpYXQiOjE1MzgwOTMyODAsImV4cCI6MTUzODY5ODA4MH0.JJDKdZgbae-KhEowY2g4MwmNuMmn26dTRUcmN-XpBxK5VqHBseaPd9Lc9HMfh859kxkW89hRYxYpuzKUrorq5Kh4NM783a3HjaNHE7uIT8sfgjtygtQ1pC5AZVBpsFErFjcS_r7ZOBB9KW2A61FBjtj-0YM9j51CG3hxjRbtzza5l0gaQECXns9NVrM7YXXnDD8M4d-5513klImIC9EZM5kyZQeV1BhlYkYgS9lSLWX3fBOX4vre_ZKgqmRGER9nLXv9caXqfDPFh9lLGambz55rZzlLrojXSuyfJ16q6_s3tF6D0Fig_icd_yrNmzfHSwYc5SF4IkkPVlxmuF0wqzQ7AmMcy6mJycCBeug0lAwD3DHWlb-MZOkEYzZHFApwvJst_Nuv_-5cmGMyxmkSiW6cr5wKkRiMBgJRyuvCuOZIlDwT43aVxhjEhtdCWNHXgtBv4w5WX6S6jW69qEeYKrHUmRB2YNGuLafYZTsY4UEYRnjmrpeCjRzeNZGq1SqQhgcY48iEzMaQ72SNvlwWYu6uf5t4lhRXvv1YIeuKSROlfuPC3d4x2HZDgNv8wlhn13G0DfnOuJDtXcVDrE5JPQIVkPUamhFopG8ANHGx4_jc6zXYVSykU11otbR6uRgSI8Gx_Jwo3jjwG-7elmlI43hTyroGUlEp73rYiShYAeI`
