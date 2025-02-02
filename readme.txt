Event Management System
-------------------------------------------------------------------------------------------------

Overview:

This Event Management System is a web-based application designed to fulfill almost all the requirements outlined in the provided assessment. It allows users to view, book, and manage events while enabling admins to handle event creation, participant management, and bookings.

Features

User Features:

View Active Events: Users can browse all active events.

Event Booking: Book events after registration or login.

Event Management: View and manage booked or reserved events.

Search Functionality: Search events by keywords or categories.

Event Details: Click on any event to see detailed information including time, venue, and other specifics.

Admin Features:

Admin Dashboard: Create, update, delete, and manage events.

Participant Management: Confirm/accept participant bookings.and download excell file

Seat Management: Seats are deducted from total capacity upon booking confirmation.

Advanced Search: Search for events and manage bookings efficiently.
----------------------------------------------------------------------------------------------------

Demo Credentials

Admin Login:

Email: admin@admin.com

Password: admin

User Login:

Email: user@user.com

Password: user
----------------------------------------------------------------------------------------------------
Installation Instructions

1. Uploading to Web Host

Use a hosting service like 000webhost, Heroku, or any preferred PHP hosting service.

Upload all website files to the public_html (or equivalent) directory on your server.

2. Database Setup

Import the database:

Go to your hosting control panel (like cPanel) and open phpMyAdmin.

Create a new database (e.g., event_management).

Import the provided SQL file: home/db/event_management.sql.

3. Database Configuration

Locate the db.php file in the project directory.

Update the database credentials in db.php:

$db = new mysqli('hostname', 'username', 'password', 'database_name');

Additionally, any file that connects to the database using db.php needs to be updated accordingly.

Look for $db and $conn variables in the codebase and ensure the correct database connection is configured.

Folder Structure

root/
 |   └── db/
 |	├── db.php
 |      └── event_management.sql
 ├── index.php
 └── (other project files)
-------------------------------------------------------------------------------------------------------

Technologies Used

Frontend: HTML, CSS (Bootstrap), JavaScript

Backend: PHP (Pure PHP)

Database: MySQL

Notes

Ensure that PHP and MySQL are supported on your hosting environment.

For any database connection issues, double-check credentials and database host information in db.php.

Adjust folder permissions if needed to allow file uploads or database access.

License

This project is developed as part of an assessment and is free to use for demonstration purposes.