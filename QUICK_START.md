# BookShare Extension - Quick Start Guide 🚀

## 📋 TL;DR - Get it working in 5 minutes!

### Step 1: Create Icons (1 minute)
1. Open this file in your browser: `browser-extension/icons/icon-generator.html`
2. Click all three download buttons
3. Save the PNG files in `browser-extension/icons/` folder

### Step 2: Start Server (30 seconds)
```bash
cd C:\Users\rannn\Desktop\bookShare
php artisan serve
```
✅ Keep this terminal running!

### Step 3: Install Extension (1 minute)
1. Open Chrome → `chrome://extensions/`
2. Turn ON "Developer mode" (top right)
3. Click "Load unpacked"
4. Select folder: `C:\Users\rannn\Desktop\bookShare\browser-extension`
5. ✅ Extension installed!

### Step 4: Test (2 minutes)
1. Add a book to your marketplace:
   - Go to `http://127.0.0.1:8000/marketplace`
   - Login and add a book

2. Test on Amazon:
   - Go to `https://www.amazon.com`
   - Search for your book
   - Open the book page
   - 🎉 See the notification banner!

---

## 🎯 Expected Result

When you visit a book page, you should see:

**If book is available:**
```
┌─────────────────────────────────────────────────┐
│ 📚 Available on BookShare!                      │
│ 2 copies available in the marketplace           │
│ Lowest price: $15.99                           │
│ [View in Marketplace] [Show All (2)]           │
└─────────────────────────────────────────────────┘
```

**If book is not available:**
```
┌─────────────────────────────────────────────────┐
│ ℹ️ Not in BookShare Marketplace                │
│ This book is not currently available            │
│ [Browse Marketplace]                           │
└─────────────────────────────────────────────────┘
```

---

## 🔍 Testing Checklist

- [ ] Icons created (3 PNG files in icons/ folder)
- [ ] Server running (`php artisan serve`)
- [ ] Extension loaded in Chrome
- [ ] Extension icon visible in toolbar
- [ ] Book added to marketplace
- [ ] Amazon page shows notification
- [ ] Clicking "View in Marketplace" works

---

## ⚠️ Troubleshooting

| Problem | Solution |
|---------|----------|
| Extension icon not showing | Create PNG icons first |
| "Cannot connect to API" | Start Laravel server |
| No notification on Amazon | Add books to marketplace first |
| Popup says "not supported" | Make sure you're on a book page |

---

## 📖 Supported Websites

Currently works on:
- ✅ Amazon.com (and other regions)
- ✅ BarnesAndNoble.com
- ✅ Goodreads.com
- ✅ BookDepository.com
- ✅ AbeBooks.com

---

## 🔧 Manual Check

If automatic detection doesn't work:
1. Click the extension icon
2. Click "Check Availability"
3. See results in popup

---

## 📚 More Help

- **Full Guide**: `EXTENSION_INSTALLATION.md`
- **Full Docs**: `EXTENSION_SUMMARY.md`
- **Extension README**: `browser-extension/README.md`

---

## 🎉 That's It!

You're done! Your browser extension is now checking BookShare marketplace while you browse book websites.

**Happy Book Sharing! 📚✨**
