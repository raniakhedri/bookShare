# BookShare Marketplace API Documentation

## Authentication
All API endpoints require authentication using Laravel Sanctum. Include the bearer token in your requests:
```
Authorization: Bearer {your-token-here}
```

## Base URL
```
http://your-domain.com/api
```

---

## 📚 Market Books API

### List Market Books
```http
GET /marketbooks
```

**Query Parameters:**
- `available` - Filter by availability (boolean)
- `condition` - Filter by condition (New, Good, Fair, Poor)
- `search` - Search by title or author
- `exclude_own` - Exclude user's own books (for browsing)

**Example Response:**
```json
{
    "data": [
        {
            "id": 1,
            "title": "The Great Gatsby",
            "author": "F. Scott Fitzgerald",
            "description": "A classic American novel",
            "condition": "Good",
            "price": "15.99",
            "is_available": true,
            "image": null,
            "owner": {
                "id": 2,
                "name": "John Doe",
                "email": "john@example.com"
            },
            "created_at": "2025-09-28T17:30:00.000000Z"
        }
    ],
    "meta": {...pagination}
}
```

### Create Market Book
```http
POST /marketbooks
Content-Type: multipart/form-data
```

**Body Parameters:**
```json
{
    "title": "Book Title",
    "author": "Author Name",
    "description": "Book description",
    "condition": "Good",
    "price": 15.99,
    "image": "file upload"
}
```

### Get My Books
```http
GET /marketbooks/my/books
```

### Toggle Book Availability
```http
PATCH /marketbooks/{id}/toggle-availability
```

---

## 💱 Transactions API

### Create Gift Request
```http
POST /transactions
```

**Body:**
```json
{
    "marketbook_id": 1,
    "type": "gift",
    "message": "I would love to read this book!"
}
```

### Create Exchange Request
```http
POST /transactions
```

**Body:**
```json
{
    "marketbook_id": 1,
    "type": "exchange",
    "message": "Would you like to exchange books?",
    "offered_marketbook_id": 2,
    "exchange_notes": "Both books are in excellent condition"
}
```

### Accept/Reject Transaction
```http
PUT /transactions/{id}
```

**Body:**
```json
{
    "status": "accepted",
    "response_message": "Happy to share this book!"
}
```

### Get My Requests
```http
GET /transactions/my/requests
```

### Get Requests for My Books
```http
GET /transactions/my/received
```

### Mark Transaction as Completed
```http
PATCH /transactions/{id}/complete
```

---

## 🔄 Exchange Requests API

### List Exchange Requests
```http
GET /exchange-requests
```

### Get My Exchange Requests
```http
GET /exchange-requests/my/requests
```

### Get Books I've Offered
```http
GET /exchange-requests/my/offered
```

### Update Exchange Request
```http
PUT /exchange-requests/{id}
```

**Body:**
```json
{
    "offered_marketbook_id": 3,
    "notes": "Updated exchange notes"
}
```

---

## 🔒 Admin API

### Admin Dashboard
```http
GET /admin/dashboard
```

**Response:**
```json
{
    "stats": {
        "total_users": 10,
        "total_market_books": 25,
        "available_books": 20,
        "total_transactions": 15,
        "pending_transactions": 5
    },
    "recent_transactions": [...],
    "books_by_condition": [...],
    "monthly_trends": [...]
}
```

### Manage Users
```http
GET /admin/users
POST /admin/users
PUT /admin/users/{id}
DELETE /admin/users/{id}
```

### User Statistics
```http
GET /admin/users/statistics
```

---

## Error Responses

### 400 Bad Request
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "title": ["The title field is required."]
    }
}
```

### 401 Unauthorized
```json
{
    "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
    "message": "Unauthorized"
}
```

### 404 Not Found
```json
{
    "message": "No query results for model"
}
```

---

## Status Codes Reference

| Code | Meaning |
|------|---------|
| 200 | OK - Request successful |
| 201 | Created - Resource created successfully |
| 400 | Bad Request - Validation errors |
| 401 | Unauthorized - Authentication required |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource doesn't exist |
| 422 | Unprocessable Entity - Validation failed |
| 500 | Internal Server Error - Server error |

---

## Transaction Flow Examples

### 1. Gift Request Flow
1. User A browses available books: `GET /marketbooks?exclude_own=true`
2. User A requests a book as gift: `POST /transactions` (type: gift)
3. Book owner (User B) sees the request: `GET /transactions/my/received`
4. User B accepts/rejects: `PUT /transactions/{id}`
5. If accepted, mark as completed: `PATCH /transactions/{id}/complete`

### 2. Exchange Request Flow
1. User A finds a book they want: `GET /marketbooks/{id}`
2. User A creates exchange request: `POST /transactions` (type: exchange)
3. System automatically creates exchange request record
4. Book owner reviews both books: `GET /transactions/my/received`
5. Owner accepts/rejects: `PUT /transactions/{id}`
6. Both parties mark as completed when books are exchanged

---

## Rate Limiting
- API requests are rate-limited to 60 per minute per user
- Admin endpoints have higher limits (120 per minute)

## Pagination
All list endpoints return paginated results:
```json
{
    "data": [...],
    "links": {
        "first": "http://example.com/api/marketbooks?page=1",
        "last": "http://example.com/api/marketbooks?page=10",
        "prev": null,
        "next": "http://example.com/api/marketbooks?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 10,
        "per_page": 15,
        "to": 15,
        "total": 150
    }
}
```