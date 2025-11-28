# HR & Project Management System - Laravel 11

A comprehensive HR and Project Management system built with **Laravel 11** and **Material Design 3** interface, specifically designed for construction companies and similar businesses.

## 🚀 Features

### ✅ **Complete Authentication System**
- **JWT Authentication** for API endpoints
- **Session Authentication** for web interface
- **Email verification** support
- **Remember me** functionality

### ✅ **Role-Based Access Control**
- **5 User Roles**: Super Admin, Admin, Project Manager, Employee, Consultant
- **40+ Permissions** across 7 modules
- **Dynamic navigation** based on user permissions
- **Role-based route protection**

### ✅ **Modern Material Design 3 Interface**
- **Responsive design** for all devices
- **Touch-friendly** mobile interface
- **Consistent styling** across all components
- **Accessibility features** built-in

### ✅ **User Management**
- **Complete employee profiles** with personal details
- **Profile image upload** with automatic resizing
- **Department and designation** management
- **Active/Inactive status** control
- **Search and filtering** capabilities

### ✅ **Dashboard & Analytics**
- **Real-time statistics** cards
- **Interactive charts** (ready for Chart.js integration)
- **Monthly performance** tracking
- **Department-wise** analytics

### ✅ **GPS & Location Features**
- **Geofencing calculations** using Haversine formula
- **Location accuracy validation**
- **GPS-based attendance** structure ready
- **Distance calculation** helper functions

## 🛠️ Technology Stack

- **Laravel 11.x** - Latest PHP framework
- **PHP 8.2+** - Modern PHP features
- **MySQL 8.0** - Reliable database
- **Material Design 3** - Google's latest design system
- **JWT Authentication** - Stateless API authentication
- **Spatie Permissions** - Role-based access control
- **Intervention Image** - Image processing
- **Chart.js Ready** - For interactive charts

## 📋 Requirements

- **PHP 8.2** or higher
- **MySQL 8.0** or higher
- **Composer** (latest version)
- **Node.js 18+** and NPM
- **Git** for version control

## 🚀 Installation

### Step 1: Create Fresh Laravel 11 Project
```bash
composer create-project laravel/laravel hr-system
cd hr-system
```

### Step 2: Extract HR System Files
1. Extract the `hr-system-laravel11-overlay.zip` into your Laravel project root
2. Allow it to overwrite existing files when prompted

### Step 3: Install Dependencies
```bash
# Install PHP packages
composer install

# Install Node.js packages
npm install
```

### Step 4: Environment Setup
```bash
# Copy environment file (if not exists)
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret
```

### Step 5: Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE hr_project_management;
EXIT;

# Run migrations
php artisan migrate

# Seed default data
php artisan db:seed
```

### Step 6: Storage & Assets
```bash
# Create storage link
php artisan storage:link

# Build frontend assets
npm run build
```

### Step 7: Start Development Server
```bash
php artisan serve
```

Visit: **http://127.0.0.1:8000**

## 🔑 Default User Accounts

| Role | Email | Password | Access Level |
|------|-------|----------|--------------|
| **Super Admin** | `superadmin@cyberforttech.com` | `Admin@123456` | Full system access |
| **Admin** | `admin@cyberforttech.com` | `Admin@123456` | Management access |
| **Project Manager** | `manager@cyberforttech.com` | `Manager@123456` | Project & team management |
| **Employee** | `employee@cyberforttech.com` | `Employee@123456` | Basic employee access |
| **Consultant** | `consultant@cyberforttech.com` | `Consultant@123456` | Limited project access |

## ⚙️ Configuration

### Environment Variables (.env)
```env
# Database
DB_DATABASE=hr_project_management
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Google Maps (for location features)
GOOGLE_MAPS_API_KEY=your_google_maps_api_key
GOOGLE_GEOCODING_API_KEY=your_geocoding_api_key

# Geofencing
GEOFENCE_RADIUS=100
GEOFENCE_ACCURACY_THRESHOLD=50

# JWT
JWT_SECRET=your_jwt_secret
JWT_TTL=60

# Admin Credentials
SUPER_ADMIN_EMAIL=superadmin@cyberforttech.com
ADMIN_EMAIL=admin@cyberforttech.com
ADMIN_PASSWORD=Admin@123456
```

## 🎯 API Endpoints

### Authentication
```
POST   /api/auth/login       # User login
POST   /api/auth/logout      # User logout
POST   /api/auth/refresh     # Refresh JWT token
GET    /api/auth/me          # Get current user
```

### Admin Routes
```
GET    /api/admin/dashboard      # Dashboard data
GET    /api/admin/users          # List users
POST   /api/admin/users          # Create user
GET    /api/admin/users/{id}     # Show user
PUT    /api/admin/users/{id}     # Update user
DELETE /api/admin/users/{id}     # Delete user
POST   /api/admin/users/{id}/toggle-status  # Toggle user status
```

### Location & Attendance
```
POST   /api/location/validate-geofence  # Validate location
POST   /api/attendance/check-in         # Check-in attendance
POST   /api/attendance/check-out        # Check-out attendance
```

## 📁 Project Structure

```
hr-system/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/           # Admin controllers
│   │   └── Auth/            # Authentication controllers
│   ├── Models/              # Eloquent models
│   └── Helpers/             # Helper functions
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   └── views/
│       ├── layouts/        # Blade layouts
│       └── auth/           # Authentication views
├── routes/
│   ├── web.php             # Web routes
│   └── api.php             # API routes
└── config/                 # Configuration files
```

## 🔐 Security Features

- **CSRF Protection** on all forms
- **Input validation** and sanitization
- **Password hashing** with bcrypt
- **JWT token management** with expiration
- **Role-based route protection**
- **SQL injection prevention**
- **File upload security**

## 📱 Mobile Responsive

The application is fully responsive and works perfectly on:
- **Desktop browsers** (1200px+)
- **Tablets** (768px-1199px)
- **Mobile phones** (320px-767px)
- **Touch interfaces** with proper touch targets

## 🎨 Customization

### Adding New Roles
```php
// In database/seeders/RoleSeeder.php
$role = Role::create(['name' => 'custom-role']);
$role->syncPermissions(['view-dashboard', 'view-projects']);
```

### Adding New Permissions
```php
// In database/seeders/PermissionSeeder.php
Permission::create(['name' => 'custom-permission']);
```

### Customizing UI Colors
```css
/* In resources/css/app.css */
:root {
    --mdc-theme-primary: #your-color;
    --mdc-theme-secondary: #your-secondary-color;
}
```

## 🚀 Deployment

### Production Setup
```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage bootstrap/cache
```

### Environment Variables for Production
```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
```

## 📚 Documentation

- [Laravel 11 Documentation](https://laravel.com/docs/11.x)
- [Material Design 3 Guidelines](https://m3.material.io)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)

## 🤝 Support

For support and questions:
- **Email**: support@cyberforttech.com
- **Issues**: Create GitHub issues for bug reports
- **Features**: Submit feature requests via GitHub

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

**Built with ❤️ by Cyber Fort Technologies**

🎉 **Your HR Management System is ready for production!**
