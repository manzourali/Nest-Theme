# Nest
Laravel Multipurpose eCommerce Script
## Installation
You can install the script manually by following these steps:

- Upload all files into the root folder of your hosting (normally, it ispublic_html).
- Create a database and import data from database.sql (it's located in source code).
- Update your database credentials and APP_URL in .env.
- In terminal run:
```bash
  npm install
```
and finally find your website on http://localhost/nest/public/
## Documentation

### Admin panel

- http://localhost/nest/public/admin
- The default admin account is admin - 12345678.

###Requirements
Before installing our script, ensure that your server meets the following requirements:
- Apache, nginx, or another compatible web server
- PHP >= 8.2 or higher
- MySQL Database server
- PDO PHP extension
- OpenSSL PHP extension
- mbstring PHP extension
- exif PHP extension
- fileinfo PHP extension
- xml PHP extension
- Ctype PHP extension
- JSON PHP extension
- Tokenizer PHP extension
- cURL PHP extension
- zip PHP extension
- iconv PHP extension
- Ensure the mod_rewrite Apache module is enabled

###PHP Configuration
Open your php configuration file php.ini and change the following settings.

```bash
memory_limit = 256M
max_execution_time = 300
```
