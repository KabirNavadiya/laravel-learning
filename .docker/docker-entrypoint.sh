#!/bin/bash

# Fix permissions for storage and bootstrap/cache directories

# Fix permissions for storage, bootstrap/cache, vendor, node_modules, and public directories
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/vendor /var/www/html/node_modules /var/www/html/public
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/vendor /var/www/html/node_modules /var/www/html/public

# Check for vendor directory to determine if this is a fresh installation
if [ ! -d /var/www/html/vendor ]; then
  echo "Installing PHP dependencies..."
  composer install --no-interaction --optimize-autoloader
  
  echo "Installing Node.js dependencies..."
  npm install --no-audit
fi

# Create .env file if it doesn't exist
if [ ! -f /var/www/html/.env ]; then
  echo "Creating .env file from .env.example..."
  cp /var/www/html/.env.example /var/www/html/.env
  
  # Generate application key
  echo "Generating application key..."
  php /var/www/html/artisan key:generate --no-interaction
  
  echo "Environment setup complete."
fi

# Wait for MySQL to be ready before running migrations
echo "Waiting for MySQL to be ready..."
MYSQL_READY=false
COUNTER=0
MAX_TRIES=60  # Increased from 30 to 60 attempts (5 minutes total)

# Improved connection check using a simple PHP script
while [ $MYSQL_READY == false ] && [ $COUNTER -lt $MAX_TRIES ]; do
  if php -r "try {\$pdo = new PDO('mysql:host=mysql;port=3306;dbname=laravel-learning', 'laraveluser', 'laravelpassword'); echo 'connected'; } catch (PDOException \$e) { exit(1); }" 2>/dev/null; then
    MYSQL_READY=true
    echo "MySQL is ready!"
    
    # Run migrations
    echo "Running database migrations..."
    php /var/www/html/artisan migrate --force --no-interaction
    
    # Run seeders if database is empty (optional)
    # echo "Running database seeders..."
    # php /var/www/html/artisan db:seed --force --no-interaction
  else
    echo "Waiting for MySQL connection... (Attempt $COUNTER of $MAX_TRIES)"
    sleep 5
    COUNTER=$((COUNTER+1))
  fi
done

if [ $MYSQL_READY == false ]; then
  echo "Warning: Could not connect to MySQL after $MAX_TRIES attempts. You may need to run migrations manually."
fi

# Initialize Husky hooks if .git directory exists
if [ -d "/var/www/html/.git" ]; then
  echo "Initializing Husky hooks..."
  cd /var/www/html
  npm ci &> /dev/null || npm install &> /dev/null
  npx husky install &> /dev/null
fi

# Execute the original command
exec "$@"