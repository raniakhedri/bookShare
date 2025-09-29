# BookShare Marketplace System

## Overview
The BookShare Marketplace is a comprehensive book exchange and gifting system that allows users to list their books for sharing, request books from others through gifts or exchanges, and manage transactions.

## Features

### 🔐 User Roles
- **Admin**: Full system management capabilities
- **User**: Can list books, make requests, and manage their transactions
- **Visitor**: Basic access (can be upgraded to user)

### 📚 Market Books Management
- Create, read, update, delete market books
- Book conditions: New, Good, Fair, Poor
- Upload book images
- Toggle availability status
- Search and filter capabilities

### 💱 Transaction System
- **Gift Requests**: Users can request books as gifts
- **Exchange Requests**: Users can offer their books in exchange for others
- Transaction statuses: Pending, Accepted, Rejected, Completed
- Messaging between users during transactions

### 🔄 Exchange Requests
- Link exchange transactions with offered books
- Additional notes for exchange details
- Automatic availability management

## Database Schema

### Tables
1. **market_books** - Books available in the marketplace
2. **transactions** - All book requests (gifts/exchanges)
3. **exchange_requests** - Details for exchange transactions
4. **users** - System users (existing table enhanced)

### Relationships
- User hasMany MarketBooks (as owner)
- User hasMany Transactions (as requester)
- MarketBook belongsTo User (owner)
- MarketBook hasMany Transactions
- Transaction belongsTo MarketBook
- Transaction belongsTo User (requester)
- Transaction hasOne ExchangeRequest
- ExchangeRequest belongsTo Transaction
- ExchangeRequest belongsTo MarketBook (offered book)

## API Endpoints

### 🔓 Public Routes (Authenticated Users)

#### Market Books
```
GET    /api/marketbooks              - List market books with filters
POST   /api/marketbooks              - Create a new market book
GET    /api/marketbooks/{id}         - Show specific market book
PUT    /api/marketbooks/{id}         - Update market book (owner/admin only)
DELETE /api/marketbooks/{id}         - Delete market book (owner/admin only)
GET    /api/marketbooks/my/books     - Get user's own books
PATCH  /api/marketbooks/{id}/toggle-availability - Toggle book availability
```

#### Transactions
```
GET    /api/transactions             - List user's transactions
POST   /api/transactions             - Create new transaction (gift/exchange)
GET    /api/transactions/{id}        - Show specific transaction
PUT    /api/transactions/{id}        - Update transaction status (accept/reject)
GET    /api/transactions/my/requests - Get user's requests
GET    /api/transactions/my/received - Get requests for user's books
PATCH  /api/transactions/{id}/complete - Mark transaction as completed
```

#### Exchange Requests
```
GET    /api/exchange-requests        - List exchange requests
GET    /api/exchange-requests/{id}   - Show specific exchange request
PUT    /api/exchange-requests/{id}   - Update exchange request
DELETE /api/exchange-requests/{id}   - Delete exchange request
GET    /api/exchange-requests/my/offered - Get user's offered books
GET    /api/exchange-requests/my/requests - Get user's exchange requests
```

### 🔒 Admin Routes (Admin Only)

#### Dashboard & Analytics
```
GET    /api/admin/dashboard          - Admin dashboard statistics
GET    /api/admin/system-health      - System health metrics
```

#### User Management
```
GET    /api/admin/users              - List all users
POST   /api/admin/users              - Create new user
GET    /api/admin/users/{id}         - Show specific user
PUT    /api/admin/users/{id}         - Update user
DELETE /api/admin/users/{id}         - Delete user
GET    /api/admin/users/statistics   - User statistics
PATCH  /api/admin/users/{id}/toggle-status - Toggle user status
```

## Installation & Setup

### 1. Database Migration
```bash
# Run marketplace migrations
php artisan migrate --path=database/migrations/2025_09_28_172128_create_market_books_table.php
php artisan migrate --path=database/migrations/2025_09_28_172136_create_transactions_table.php
php artisan migrate --path=database/migrations/2025_09_28_172143_create_exchange_requests_table.php
```

### 2. Seed Sample Data
```bash
php artisan db:seed --class=MarketplaceSeeder
```

### 3. Storage Setup
```bash
php artisan storage:link
```

## Usage Examples

### 🎁 Request a Book as Gift
```javascript
POST /api/transactions
{
    "marketbook_id": 1,
    "type": "gift",
    "message": "I would love to read this book!"
}
```

### 🔄 Request Book Exchange
```javascript
POST /api/transactions
{
    "marketbook_id": 1,
    "type": "exchange",
    "message": "Would you like to exchange books?",
    "offered_marketbook_id": 2,
    "exchange_notes": "Both books are in excellent condition"
}
```

### ✅ Accept a Transaction
```javascript
PUT /api/transactions/1
{
    "status": "accepted",
    "response_message": "Happy to share this book with you!"
}
```

### ❌ Reject a Transaction
```javascript
PUT /api/transactions/1
{
    "status": "rejected",
    "response_message": "Sorry, this book is no longer available"
}
```

## Security Features

### 🛡️ Middleware Protection
- **AdminMiddleware**: Protects admin routes
- **Auth Sanctum**: API authentication
- **CSRF Protection**: Form submissions

### 🔐 Authorization Rules
- Users can only modify their own books
- Users can only see transactions they're involved in
- Book owners can accept/reject requests
- Admins have full access to all resources

## Models & Relationships

### MarketBook Model
```php
// Relationships
$book->owner          // User who owns the book
$book->transactions   // All transactions for this book
$book->exchangeOffers // Exchange requests offering this book

// Scopes
MarketBook::available()           // Only available books
MarketBook::byCondition('Good')   // Filter by condition
MarketBook::search('gatsby')      // Search title/author
```

### Transaction Model
```php
// Relationships
$transaction->marketBook      // The requested book
$transaction->requester       // User making the request
$transaction->exchangeRequest // Exchange details (if applicable)

// Methods
$transaction->isGift()        // Check if gift transaction
$transaction->isExchange()    // Check if exchange transaction
$transaction->accept($message) // Accept the transaction
$transaction->reject($message) // Reject the transaction
```

### User Model (Enhanced)
```php
// New Marketplace Relationships
$user->marketBooks            // Books owned by user
$user->requestedTransactions  // Transactions user initiated
$user->receivedTransactions   // Transactions for user's books

// New Methods
$user->isAdmin()             // Check admin role
$user->isUser()              // Check regular user role
```

## Frontend Integration

### 📱 Dashboard Views
- **My Books**: List user's market books with pending requests
- **Browse Books**: Search and filter available books
- **My Requests**: Track outgoing requests
- **Incoming Requests**: Manage requests for user's books
- **Admin Panel**: System management (admin only)

### 🎨 Suggested UI Components
- Book card with image, title, author, condition
- Transaction status badges
- Request/response messaging
- Availability toggle switches
- Search and filter controls

## Error Handling

### Common Validation Errors
- Book not available for request
- User requesting their own book
- Exchange book not owned by requester
- Transaction already responded to
- Invalid book condition values

### HTTP Status Codes
- `200` Success
- `201` Created
- `400` Bad Request (validation errors)
- `401` Unauthorized (not authenticated)
- `403` Forbidden (insufficient permissions)
- `404` Not Found
- `500` Internal Server Error

## Test Users (After Seeding)
- **Admin**: admin@bookshare.com / password
- **User 1**: john@example.com / password
- **User 2**: jane@example.com / password

## Future Enhancements
- Real-time notifications for transactions
- Book reviews and ratings
- Geographic location for local exchanges
- Book recommendation system
- Integration with external book APIs
- Mobile app support
- Advanced analytics dashboard

---

## Support
For issues or questions about the marketplace system, please check the Laravel logs and ensure all migrations have been run successfully.