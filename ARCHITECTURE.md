# BookShare Extension - Architecture Overview 🏗️

## System Architecture

```
┌─────────────────────────────────────────────────────────────────────────┐
│                        USER'S BROWSER                                    │
│                                                                          │
│  ┌──────────────────────────────────────────────────────────────────┐  │
│  │  BOOK SELLER WEBSITE (Amazon, B&N, etc.)                         │  │
│  │                                                                   │  │
│  │  ┌─────────────────────────┐                                     │  │
│  │  │  Book Page              │                                     │  │
│  │  │  ─────────              │                                     │  │
│  │  │  Title: "Harry Potter"  │                                     │  │
│  │  │  Author: "J.K. Rowling" │                                     │  │
│  │  │  Price: $29.99          │                                     │  │
│  │  └─────────────────────────┘                                     │  │
│  │           ▲                                                       │  │
│  │           │ DOM Parsing                                          │  │
│  │           │                                                       │  │
│  │  ┌────────▼──────────────────────────────────────┐               │  │
│  │  │  CONTENT SCRIPT (content.js)                  │               │  │
│  │  │  ────────────────────────                     │               │  │
│  │  │  1. Detects book page                         │               │  │
│  │  │  2. Extracts title/author from DOM            │               │  │
│  │  │  3. Calls BookShare API                       │───────┐       │  │
│  │  │  4. Shows notification banner                 │       │       │  │
│  │  └───────────────────────────────────────────────┘       │       │  │
│  │                                                           │       │  │
│  │  ┌───────────────────────────────────────────────────┐   │       │  │
│  │  │  NOTIFICATION BANNER (content.css)             │   │       │  │
│  │  │  ─────────────────────────                      │   │       │  │
│  │  │  ┌──────────────────────────────────────────┐  │   │       │  │
│  │  │  │ 📚 Available on BookShare!               │  │   │       │  │
│  │  │  │ 2 copies available                       │  │   │       │  │
│  │  │  │ Lowest price: $15.99                     │  │   │       │  │
│  │  │  │ [View] [Show All]                        │  │   │       │  │
│  │  │  └──────────────────────────────────────────┘  │   │       │  │
│  │  └───────────────────────────────────────────────────┘   │       │  │
│  └───────────────────────────────────────────────────────────┼───────┘  │
│                                                              │          │
│  ┌──────────────────────────────────────┐                   │          │
│  │  EXTENSION POPUP (popup.html)        │                   │          │
│  │  ──────────────────────               │                   │          │
│  │  ┌────────────────────────────────┐  │                   │          │
│  │  │  📚 BookShare                   │  │                   │          │
│  │  │  ─────────────                 │  │                   │          │
│  │  │  Status: Available (2 copies)  │  │                   │          │
│  │  │  [Check Availability]          │  │                   │          │
│  │  │  [Open Marketplace]            │  │                   │          │
│  │  │  [Settings]                    │  │                   │          │
│  │  └────────────────────────────────┘  │                   │          │
│  └──────────────────────────────────────┘                   │          │
│                                                              │          │
│  ┌──────────────────────────────────────┐                   │          │
│  │  SETTINGS PAGE (options.html)        │                   │          │
│  │  ──────────────────────               │                   │          │
│  │  API URL: http://127.0.0.1:8000      │                   │          │
│  │  ☑ Enable notifications              │                   │          │
│  │  [Save]                               │                   │          │
│  └──────────────────────────────────────┘                   │          │
│                                                              │          │
│  ┌──────────────────────────────────────┐                   │          │
│  │  BACKGROUND WORKER (background.js)   │                   │          │
│  │  Manages extension lifecycle          │                   │          │
│  └──────────────────────────────────────┘                   │          │
└──────────────────────────────────────────────────────────────┼──────────┘
                                                              │
                                                              │ HTTP Request
                                                              │
┌─────────────────────────────────────────────────────────────▼──────────┐
│                    BOOKSHARE SERVER (Laravel)                          │
│                    http://127.0.0.1:8000                              │
│                                                                        │
│  ┌──────────────────────────────────────────────────────────────────┐ │
│  │  API ROUTES (routes/api.php)                                     │ │
│  │  ──────────────────────                                          │ │
│  │  GET /api/public/books/check-availability                        │ │
│  │  GET /api/public/books/{id}                                      │ │
│  └────────────────────────────┬─────────────────────────────────────┘ │
│                               │                                        │
│  ┌────────────────────────────▼─────────────────────────────────────┐ │
│  │  BookAvailabilityController                                      │ │
│  │  ───────────────────────────                                     │ │
│  │  checkAvailability($request)                                     │ │
│  │  ├─ Validate input (title, author)                               │ │
│  │  ├─ Query MarketBook model                                       │ │
│  │  └─ Return JSON response                                         │ │
│  └────────────────────────────┬─────────────────────────────────────┘ │
│                               │                                        │
│  ┌────────────────────────────▼─────────────────────────────────────┐ │
│  │  MarketBook Model (app/Models/MarketBook.php)                    │ │
│  │  ──────────────────                                              │ │
│  │  scopeAvailable() → where('is_available', true)                  │ │
│  │  scopeSearch() → LIKE '%title%' OR '%author%'                    │ │
│  └────────────────────────────┬─────────────────────────────────────┘ │
│                               │                                        │
│  ┌────────────────────────────▼─────────────────────────────────────┐ │
│  │  DATABASE (MySQL)                                                │ │
│  │  ─────────────                                                   │ │
│  │  Table: market_books                                             │ │
│  │  ┌────┬───────────────┬─────────────┬───────┬──────┐            │ │
│  │  │ id │ title         │ author      │ price │ ... │            │ │
│  │  ├────┼───────────────┼─────────────┼───────┼──────┤            │ │
│  │  │ 1  │ Harry Potter  │ J.K. Rowling│ 15.99 │ ... │            │ │
│  │  │ 2  │ Harry Potter  │ J.K. Rowling│ 18.50 │ ... │            │ │
│  │  └────┴───────────────┴─────────────┴───────┴──────┘            │ │
│  └──────────────────────────────────────────────────────────────────┘ │
└────────────────────────────────────────────────────────────────────────┘
```

## Data Flow

### 1. Book Detection Flow
```
User visits Amazon
       ↓
Content script loads
       ↓
Extracts DOM selectors
       ↓
Finds: title = "Harry Potter"
       author = "J.K. Rowling"
       ↓
Sends to API
```

### 2. API Request Flow
```
GET /api/public/books/check-availability
    ?title=Harry+Potter
    &author=J.K.+Rowling
       ↓
Controller validates input
       ↓
Queries: MarketBook::available()->search(...)
       ↓
Database query:
    SELECT * FROM market_books
    WHERE is_available = 1
    AND (title LIKE '%Harry Potter%'
         OR author LIKE '%J.K. Rowling%')
       ↓
Returns JSON:
{
  "available": true,
  "count": 2,
  "books": [...]
}
```

### 3. Display Flow
```
API Response received
       ↓
Content script processes
       ↓
If available:
  - Create banner
  - Show count & price
  - Add "View" buttons
       ↓
If not available:
  - Show "Not found" message
  - Add "Browse Marketplace" link
       ↓
User clicks "View"
       ↓
Opens: http://127.0.0.1:8000/marketplace/books/{id}
```

## Component Interactions

```
┌────────────┐          ┌────────────┐          ┌────────────┐
│  content.js│          │ background │          │  popup.js  │
│  (site)    │◄────────►│   .js      │◄────────►│  (UI)      │
└─────┬──────┘          └────────────┘          └────────────┘
      │
      │ HTTP
      ↓
┌────────────────────────────────────────────────┐
│  Laravel API                                   │
│  /api/public/books/check-availability          │
└────────────────────────────────────────────────┘
      │
      │ Database Query
      ↓
┌────────────────────────────────────────────────┐
│  MarketBook Model                              │
│  ├─ scopeAvailable()                           │
│  └─ scopeSearch()                              │
└────────────────────────────────────────────────┘
      │
      │ SQL
      ↓
┌────────────────────────────────────────────────┐
│  MySQL Database                                │
│  └─ market_books table                         │
└────────────────────────────────────────────────┘
```

## Request/Response Example

### Request
```http
GET /api/public/books/check-availability?title=Harry+Potter&author=J.K.+Rowling HTTP/1.1
Host: 127.0.0.1:8000
Accept: application/json
Origin: https://www.amazon.com
```

### Response (Available)
```json
{
  "available": true,
  "message": "Book(s) found in marketplace",
  "count": 2,
  "books": [
    {
      "id": 1,
      "title": "Harry Potter and the Philosopher's Stone",
      "author": "J.K. Rowling",
      "condition": "Like New",
      "price": "15.99",
      "owner": {
        "id": 5,
        "name": "John Doe"
      },
      "image": "http://127.0.0.1:8000/storage/covers/hp1.jpg",
      "marketplace_url": "http://127.0.0.1:8000/marketplace/books/1"
    },
    {
      "id": 7,
      "title": "Harry Potter and the Philosopher's Stone",
      "author": "J.K. Rowling",
      "condition": "Good",
      "price": "12.50",
      "owner": {
        "id": 12,
        "name": "Jane Smith"
      },
      "image": "http://127.0.0.1:8000/storage/covers/hp1-2.jpg",
      "marketplace_url": "http://127.0.0.1:8000/marketplace/books/7"
    }
  ]
}
```

### Response (Not Available)
```json
{
  "available": false,
  "message": "Book not found in marketplace",
  "books": []
}
```

## Site Detection Patterns

```javascript
// Amazon
hostname: amazon.com
selectors: {
  title: '#productTitle',
  author: '.author .a-link-normal'
}

// Barnes & Noble  
hostname: barnesandnoble.com
selectors: {
  title: 'h1.pdp-title',
  author: '.contributors a'
}

// Goodreads
hostname: goodreads.com
selectors: {
  title: 'h1.Text__title1',
  author: 'span.ContributorLink__name'
}
```

## Security Flow

```
┌─────────────┐
│   Browser   │
└──────┬──────┘
       │ HTTPS (production)
       │ HTTP (development)
       ↓
┌─────────────────┐
│  Laravel API    │ ← CORS configured
│  (Public)       │ ← No authentication
└──────┬──────────┘
       │ Database query
       │ (read-only)
       ↓
┌─────────────────┐
│    Database     │
│  market_books   │ ← Only public data
└─────────────────┘
```

## Technology Stack

```
Frontend (Extension):
├── Manifest V3
├── Vanilla JavaScript
├── CSS3
└── HTML5

Backend (API):
├── Laravel 9+
├── PHP 8.2+
└── MySQL

Communication:
├── REST API
├── JSON
└── CORS enabled
```

## File Organization

```
browser-extension/
├── manifest.json          ← Extension config
├── content.js            ← Runs on book sites
├── content.css           ← Banner styles
├── background.js         ← Service worker
├── popup.html/.js        ← Extension popup
├── options.html/.js      ← Settings page
└── icons/                ← Extension icons

app/Http/Controllers/Api/
└── BookAvailabilityController.php  ← API logic

routes/
└── api.php               ← API routes

app/Models/
└── MarketBook.php        ← Database model
```

---

## Quick Reference

**API Endpoint**: `http://127.0.0.1:8000/api/public/books/check-availability`

**Query Params**: `title`, `author`, `isbn`

**Response Keys**: `available`, `count`, `books[]`

**Supported Sites**: Amazon, Barnes & Noble, Goodreads, Book Depository, AbeBooks

**Browser Support**: Chrome, Edge, Firefox, Opera

---

**Understanding this architecture will help you debug, extend, and customize the extension! 🚀**
