# BookShare Browser Extension 📚

A browser extension that checks if books are available in the BookShare marketplace while you browse book seller websites.

## Features ✨

- **Automatic Detection**: Automatically detects book information on supported websites
- **Real-time Availability**: Checks BookShare marketplace in real-time
- **Beautiful Notifications**: Shows non-intrusive banners with availability status
- **Detailed View**: See all available copies with prices and conditions
- **Multi-site Support**: Works on Amazon, Barnes & Noble, Goodreads, and more

## Supported Websites 🌐

- Amazon (all regions: .com, .co.uk, .fr, .de, etc.)
- Barnes & Noble
- Goodreads
- Book Depository
- AbeBooks

## Installation 🚀

### Chrome/Edge (Developer Mode)

1. Open Chrome/Edge and navigate to `chrome://extensions/` or `edge://extensions/`
2. Enable "Developer mode" (toggle in top right)
3. Click "Load unpacked"
4. Select the `browser-extension` folder
5. The extension is now installed!

### Firefox (Developer Mode)

1. Open Firefox and navigate to `about:debugging#/runtime/this-firefox`
2. Click "Load Temporary Add-on"
3. Select the `manifest.json` file from the `browser-extension` folder
4. The extension is now installed temporarily

## Configuration ⚙️

### API URL

By default, the extension connects to:
```
http://127.0.0.1:8000/api/public
```

To change this:
1. Click the extension icon
2. Click "Settings"
3. Update the API URL
4. Save settings

### Production Setup

For production use:
1. Update your BookShare `.env` file with your production domain
2. Ensure CORS is configured correctly in `config/cors.php`
3. Update the extension settings with your production API URL

## Usage 📖

### Automatic Mode

1. Navigate to any supported book seller website
2. Open a book detail page
3. The extension will automatically:
   - Detect the book information
   - Check BookShare marketplace
   - Show a notification banner if available

### Manual Check

1. On any book page, click the extension icon
2. Click "Check Availability"
3. View results in the popup

## How It Works 🔍

1. **Detection**: Content script extracts book title and author from page
2. **API Call**: Sends request to BookShare API: `/api/public/books/check-availability`
3. **Display**: Shows results in a banner or popup
4. **Navigation**: Direct links to marketplace listings

## API Endpoints 🔌

The extension uses these public API endpoints:

```
GET /api/public/books/check-availability?title={title}&author={author}
GET /api/public/books/{id}
```

## Development 🛠️

### File Structure

```
browser-extension/
├── manifest.json          # Extension configuration
├── content.js            # Runs on book seller sites
├── content.css           # Notification banner styles
├── background.js         # Service worker
├── popup.html            # Extension popup UI
├── popup.js              # Popup logic
├── options.html          # Settings page UI
├── options.js            # Settings logic
├── icons/                # Extension icons
│   ├── icon16.png
│   ├── icon48.png
│   └── icon128.png
└── README.md
```

### Testing

1. Start your Laravel development server:
   ```bash
   php artisan serve
   ```

2. Add some books to the marketplace via the web interface

3. Visit Amazon or another supported site

4. Search for one of your marketplace books

5. The extension should detect and notify you!

### Debugging

- Open browser console (F12) on book pages
- Look for "BookShare Extension:" log messages
- Check the Network tab for API calls
- View popup console via "Inspect" in extension menu

## Customization 🎨

### Adding New Websites

Edit `content.js` and add a new pattern:

```javascript
const sitePatterns = {
  yoursite: {
    hostPattern: /yoursite\.com/,
    selectors: {
      title: 'h1.book-title',
      author: '.author-name'
    }
  }
};
```

### Styling

Edit `content.css` to customize:
- Banner colors and position
- Modal appearance
- Animations

## Privacy 🔒

This extension:
- Only activates on book seller websites
- Only sends book title/author to BookShare API
- Does not track browsing history
- Does not collect personal data
- All data stays between you and BookShare

## Troubleshooting 🔧

### Extension not detecting books

- Check if the website is supported
- Open console and look for errors
- Try manual check via popup

### API connection errors

- Verify Laravel server is running
- Check API URL in settings
- Ensure CORS is configured
- Check browser console for network errors

### Banner not showing

- Check "Show notifications" in settings
- Verify extension is enabled
- Reload the page

## Future Enhancements 🚀

- [ ] Add more book seller websites
- [ ] Price comparison features
- [ ] Wishlist integration
- [ ] Browser notifications
- [ ] ISBN-based matching
- [ ] Multi-language support
- [ ] Dark mode

## Contributing 🤝

Feel free to submit issues and enhancement requests!

## License 📄

This extension is part of the BookShare project.

---

**Made with ❤️ for BookShare**
