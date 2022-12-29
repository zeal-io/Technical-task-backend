# Coordinates resolver
This is a simple tool to resolve coordinates from google and here APIs and it will be cached in db.
## structure
* I didn't change the project structure and I used the same structure of the project.
* It not follows all DDD structure, but it's a good for the task.
* I just added some new services as Application services.
* I didn't create infrastructure or Domain layer to avoid over engineering.
## Test Coverage
It is fully covered by Feature test: tests/Feature/CoordinatesTest.php
Unit test just for the main service: tests/Unit/GetCoordinatesServiceTest.php
