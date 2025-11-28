#!/bin/bash

echo "🚀 Setting up HR & Project Management System (Laravel 11)"
echo "============================================================"

# Check requirements
echo "📋 Checking system requirements..."

# Check PHP
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed"
    exit 1
fi

php_version=$(php -v | head -n1 | awk '{print $2}' | cut -d. -f1,2)
echo "✅ PHP $php_version detected"

# Check Composer
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed"
    exit 1
fi
echo "✅ Composer detected"

# Check Node.js
if ! command -v node &> /dev/null; then
    echo "❌ Node.js is not installed"
    exit 1
fi
echo "✅ Node.js detected"

# Install dependencies
echo ""
echo "📦 Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev

echo "📦 Installing Node.js dependencies..."
npm install --production

# Setup environment
echo ""
echo "🔧 Setting up environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅ Created .env file"
fi

# Generate keys
echo "🔑 Generating application keys..."
php artisan key:generate --force
php artisan jwt:secret --force

# Setup storage
echo "🔗 Setting up storage..."
php artisan storage:link

# Database setup
echo ""
echo "📊 Database Setup"
echo "Make sure your MySQL server is running and create the database:"
echo "CREATE DATABASE hr_project_management;"
echo ""
read -p "Press Enter when ready to run migrations..."

php artisan migrate --force
echo "✅ Database migrated"

php artisan db:seed --force
echo "✅ Database seeded with default data"

# Build assets
echo ""
echo "🎨 Building frontend assets..."
npm run build

# Final setup
echo ""
echo "🧹 Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "🎉 Installation completed successfully!"
echo "============================================================"
echo ""
echo "🔑 Default Login Credentials:"
echo "Super Admin: superadmin@cyberforttech.com / Admin@123456"
echo "Admin:       admin@cyberforttech.com / Admin@123456"
echo "Manager:     manager@cyberforttech.com / Manager@123456"
echo "Employee:    employee@cyberforttech.com / Employee@123456"
echo ""
echo "🚀 To start the development server:"
echo "php artisan serve"
echo ""
echo "🌐 Then visit: http://127.0.0.1:8000"
echo ""
echo "📝 Next steps:"
echo "1. Update database credentials in .env"
echo "2. Add Google Maps API key for location features"
echo "3. Configure email settings for notifications"
echo "4. Customize the system as needed"
echo ""
echo "✨ Happy coding with your new HR Management System!"
