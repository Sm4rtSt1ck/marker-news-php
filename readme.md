# Marker News

Marker News is a web application for publishing local events and media news. The platform allows users to mark events on a map, view dynamic news feeds, and interact with content—all without relying on any frameworks. It uses PHP, HTML/CSS, JavaScript, MySQL, and Yandex Maps.

## Features

- **User Events:** Post events with descriptions and media attachments.
- **Media News:** Dedicated space for news published by media outlets.
- **Map Integration:** Visualize events on Yandex Maps.
- **Lightweight Architecture:** Pure PHP, HTML/CSS, and JavaScript without frameworks.
- **Template-Based Views:** Consistent header and footer across the site.
- **Simple Routing:** Clean URLs without the need to type `index.php`.

## Requirements

- PHP 7.0 or higher.
- MySQL database.
- A local development environment (e.g., XAMPP, WAMP, or PHP built-in server).

## Setup and Installation

1. **Clone the Repository:**

    ```bash
    git clone <repository_url>
    cd <repository_directory>
    ```

2. **Create Local Configuration:**

    - Copy config/config.php to create config/local.php.
    - Create config/local.php to set your real database credentials:
        ```php
        <?php
        return [
            'db_host' => 'localhost',
            'db_name' => 'marker_news',
            'db_user' => 'your_real_username',
            'db_pass' => 'your_real_password',
        ];
        ```

3. **Set Up the Database:**

    - Import the SQL file located at sql/database.sql into your MySQL server.
    - For example, using the command line:
    ```bash
    mysql -u your_real_username -p marker_news < sql/database.sql
    ```

## Running the Server

For development, you can use PHP's built-in web server. From the project root, run:
```bash
php -S localhost:8000 -t public
```
Then open your browser and navigate to http://localhost:8000.

> Important: The built-in PHP server does not utilize .htaccess files. If you deploy your project on Apache, the .htaccess file in public/ will enable URL rewriting and clean URLs.

## Additional Information

- **Routing:**
    The config/routes.php file contains the URL-to-view mapping. Modify it to add new routes as needed.

- **Template System:**
    Header and footer templates are located in app/views/templates/ and are included in each page to maintain a consistent layout.

- **Database Connection:**
    The connection is managed by app/helpers/db.php, which uses PDO to connect using credentials from the configuration files.