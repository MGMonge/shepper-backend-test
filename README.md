# :pencil: Shepper Backend Developer Test

## How to run the application
In order to run the application you must follow the steps below:

#### 1 - Make sure you have database credentials set up on your `.env` file
#### 2 - Run the following command
```shell script
php artisan start
```
#### 3 - Try the different endpoints
```shell script
GET    http://127.0.0.1:8888/user
GET    http://127.0.0.1:8888/locations
POST   http://127.0.0.1:8888/locations
PUT    http://127.0.0.1:8888/locations/{id}
DELETE http://127.0.0.1:8888/locations/{id}
```

## Run the tests
#### 1 - Make sure you have database credentials set up on your `.env` file
#### 2 - Run the following command
```shell script
php artisan test
```

Original spec [Original spec](original-spec.md)