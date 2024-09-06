# Symfony Voter usage

This simple app shows how we can handle security with Symfony Security Voter.

### Run this project

```bash
$ docker run --rm -v $PWD:/app composer composer install
$ docker run --rm -v $PWD:/app -p 8000:8000 php:8.3 php -t /app/public -S 0.0.0.0:8000
```
