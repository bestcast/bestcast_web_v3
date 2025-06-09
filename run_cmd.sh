#!/bin/bash

# Check for root privileges for restarting Apache
if [ "$EUID" -ne 0 ]; then 
  echo "Please run as root or use sudo"
  exit 1
fi

echo "Running Laravel database migration and clearing caches..."

# Navigate to Laravel project directory
cd /var/www/bestcast_web || { echo "Directory not found"; exit 1; }

# Run Laravel Artisan commands
php artisan migrate

php artisan cache:clear
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Restart Apache server
echo "Restarting Apache..."
systemctl restart apache2

echo "Done."

