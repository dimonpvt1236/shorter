URL Shorter
=======

A Symfony project created on February 26, 2017, 10:12 am.

## Installation

1. Run this command in terminal:

                composer install
                
2. After composer install, set database connection parameters in file `app/config/parameters.yml`. Example:
                
                parameters:
                    database_host: 127.0.0.1
                    database_port: null
                    database_name: symfony
                    database_user: root
                    database_password: pass
                    
3. Configure Apache server for this project or use Symfony internal Web Server:

                php bin/console server:start
                
 And go to url in next message:
                
                [OK] Web server listening on http://127.0.0.1:8000       