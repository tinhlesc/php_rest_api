# PHP REST API

## MySQL initialize
``` sql
CREATE TABLE `rest_api`.`user` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
`username` VARCHAR(45) NOT NULL,
`lastname` VARCHAR(45) NULL,
`firstname` VARCHAR(45) NULL,
`password` VARCHAR(45) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE INDEX `username_UNIQUE` (`username` ASC) VISIBLE);

INSERT INTO `rest_api`.`user` (`id`, `username`, `lastname`, `firstname`, `password`) VALUES ('1', 'username', 'Foo', 'Bar', 'LGvPM6pYdV54CC2');
```

## Run docker
```
- docker-compose up -d --force-recreate --build --remove-orphans
```

## Setup host domain
```
- 127.0.0.1 php_rest_api.local
```

## Run api
```
GET: php_rest_api.local:8080/user?limit=20
```
