# Tympaheath Test

A PHP application built using the Slim Framework.

## Prerequisite

- Docker
- Docker compose

## How to run project

```
docker compose up
```

## Improvements

- Data validation: Utilise an efficient library for handling request data validation. (Similar to how Laravel handles validation).
- Error handling: Introduce middleware that will catch errors and return the error in JSON format.
- Response Format: Introduce middleware for ensuring consistent data response format.
- Security: Introduce middleware to validate a predefined API Key.
- Tests: Add API tests.
- Database migration: Use a migration library for handling database changes.
- Documentation: Add swagger API documentation.
