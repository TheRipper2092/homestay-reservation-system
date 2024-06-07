
# HomeStay Reservation System

A web-based reservation system for HomeStay Hub, allowing users to book rooms online.

## Features
- User authentication (login and registration)
- Room category and room selection
- Date-based reservation
- AJAX-based form submission

## Requirements
- PHP 7.x or higher
- MySQL 5.x or higher
- Apache/Nginx server
- Composer (for managing PHP dependencies)

## Installation

1. Clone the repository:
   ```sh
   git clone https://github.com/yourusername/homestay-reservation-system.git

2.Navigate to the project directory:

      cd homestay-reservation-system

3.Install dependencies:

      composer install

4.Create a .env file and set your database credentials:

      DB_HOST=your_db_host
      DB_NAME=your_db_name
      DB_USER=your_db_user
      DB_PASS=your_db_password

5.Import the database:

      Import the SQL file provided in the /database directory into your MySQL database.

6.Start the server:

        php -S localhost:8000

7.Access the application in your browser at http://localhost:8000.
