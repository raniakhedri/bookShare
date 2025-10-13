# Unified Laravel Project Structure

This project combines two Laravel applications into one unified system:

## 🏢 **BACKOFFICE** (Admin Panel)
- **URL Access**: `http://localhost:8000/admin/`
- **Login**: `http://localhost:8000/admin/login`
- **Dashboard**: `http://localhost:8000/admin/dashboard`
- **Views Location**: `resources/views/backoffice/`
- **Controllers**: `app/Http/Controllers/Backoffice/`
- **Purpose**: Admin management, user management, content management

### Default Admin Credentials:
- **Email**: admin@softui.com
- **Password**: secret

## 🌐 **FRONTOFFICE** (User Interface)
- **URL Access**: `http://localhost:8000/` (root)
- **Main Pages**:
  - Home: `http://localhost:8000/`
  - Books: `http://localhost:8000/book`
  - Notes: `http://localhost:8000/notes`
  - Groups: `http://localhost:8000/groups`
  - Marketplace: `http://localhost:8000/marketplace`
  - Blog: `http://localhost:8000/blog`
  - Community: `http://localhost:8000/community`
- **Views Location**: `resources/views/frontoffice/`
- **Controllers**: `app/Http/Controllers/Frontoffice/`
- **Purpose**: Public user interface, book sharing, community features

## 📁 **File Structure**
```
├── app/
│   └── Http/
│       └── Controllers/
│           ├── Backoffice/     # Admin controllers
│           └── Frontoffice/    # User controllers
├── resources/
│   └── views/
│       ├── backoffice/         # Admin views
│       └── frontoffice/        # User views
├── database/
│   └── migrations/             # Combined migrations
├── public/                     # Combined public assets
└── routes/
    └── web.php                # Combined routes
```

## 🚀 **Getting Started**

1. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```

2. **Setup Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Setup**:
   ```bash
   php artisan migrate --seed
   ```

4. **Start Development Server**:
   ```bash
   php artisan serve
   ```

5. **Access the Application**:
   - **Frontoffice**: http://localhost:8000
   - **Backoffice**: http://localhost:8000/admin/login

## 🔧 **Development Notes**

- Both frontoffice and backoffice share the same database
- Models are located in `app/Models/` and can be used by both sides
- Middleware and authentication are configured for both sections
- Public assets from both projects have been merged

## 📋 **Routes Structure**

### Frontoffice Routes (Public)
```php
Route::get('/', 'frontoffice.home');
Route::get('/book', 'frontoffice.book');
// ... other frontoffice routes
```

### Backoffice Routes (Admin - Auth Required)
```php
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/dashboard', 'backoffice.dashboard');
    // ... other admin routes
});
```

This structure allows you to maintain both the admin panel and user interface in a single Laravel application while keeping them logically separated.