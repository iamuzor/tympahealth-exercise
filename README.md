# Tympahealth (Technical Challenge)

A service for managing devices. Built in PHP using the Slim Framework.

## Prerequisite

- Docker.

## How to run the API

- In your terminal, run `docker compose up`

- Using the content of [`./db.sql`](`./db.sql`), create the table using your Postgres client of choice.

- Enjoy 😊
  ![postman screenshot](./screenshot.png)

## Todo

- API ✅
- Web UI ⏳

## Improvements (Not implemented)

- Data validation: To utilise an efficient library for handling request data validation. (Similar to how Laravel handles validation).
- Error handling: To introduce middleware that will catch errors and return the error in JSON format.
- Response Format: To introduce middleware for ensuring consistent data response format.
- Security: To introduce middleware to validate a predefined API Key.
- Tests: To add **more** API tests.
- Database migration: To use a migration library for handling database changes.
- Documentation: To add swagger API documentation.
