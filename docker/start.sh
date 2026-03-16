#!/bin/bash
set -e

echo "=== Starting Karyawan App ==="

# Run database initialization
echo "Running database initialization..."
php /var/www/html/docker/init-db.php

echo "Starting Apache..."
exec apache2-foreground
