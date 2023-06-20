FROM php:8.2-cli

COPY . /usr/src/myapp

WORKDIR /usr/src/myapp

CMD [ "php", "public/index.php" ]