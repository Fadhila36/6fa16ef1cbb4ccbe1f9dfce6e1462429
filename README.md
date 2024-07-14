# REST API for Email Sending and User Authentication

This project provides a REST API for user authentication, email sending, and integration with Google OAuth2. It includes functionalities for user registration, login, and asynchronous email processing. The system uses PostgreSQL for data storage, Redis for message queuing, and Docker for containerization.

## Features

- **User Registration**: Register new users with a username and password.
- **User Login**: Authenticate users and provide access tokens.
- **OAuth2 Authentication**: Integrate with Google OAuth2 for secure authentication.
- **Email Sending**: Send emails asynchronously using Redis queues.
- **Docker Support**: Containerize the application for consistent deployment.

## System Design

### Overview

The system consists of the following components:

1. **REST API**: Handles HTTP requests for user management, email sending, and authentication.
2. **OAuth2 Authentication**: Manages user authentication through Google OAuth2.
3. **Database**: PostgreSQL is used for storing user and email data.
4. **Queue System**: Redis is used for queuing email sending tasks.
5. **Docker**: Containerizes the application to ensure a consistent environment.

### Architecture Diagram

Below is a high-level diagram of the system architecture:

```plaintext
                       +-------------------+
                       |    Google OAuth2  |
                       +---------+---------+
                                 |
                                 |
                       +---------v---------+
                       |  REST API (PHP)   |
                       +---------+---------+
                                 |
                                 |
     +---------------------------+---------------------------+
     |                           |                           |
+----v-----+               +-----v-----+               +-----v-----+
| PostgreSQL|               |   Redis   |               |  Docker   |
|  Database |               |  Queue    |               |  Container|
+----+------+               +-----------+               +-----------+
     |                                                          |
     |                                                          |
+----v-----+                                                +----v-----+
| Users    |                                                | Emails   |
| Table    |                                                | Table    |
+----------+                                                +----------+


## API Endpoints

- **POST /register:** Registers a new user.
- **POST /login:** Authenticates a user and returns an access token.
- **GET /auth/callback:** Handles the OAuth2 callback from Google.
- **POST /send-email:** Sends an email (requires authentication).
- **POST /logout:** Logs out the user and clears the session.
