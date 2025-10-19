# BookShare Browser Extension - Installation Guide

## Complete Setup Instructions

### Step 1: Generate Extension Icons 🎨

Before installing the extension, you need to create the PNG icons:

**Option A: Use the HTML Generator (Easiest)**
1. Open `browser-extension/icons/icon-generator.html` in any web browser
2. Click each button to download the icons:
   - Download 128x128
   - Download 48x48
   - Download 16x16
3. Save all three PNG files in the `browser-extension/icons/` folder

**Option B: Use an Online Converter**
1. Go to https://cloudconvert.com/svg-to-png
2. Upload `browser-extension/icons/icon128.svg`
3. Convert to PNG at these sizes: 128x128, 48x48, 16x16
4. Save as `icon128.png`, `icon48.png`, `icon16.png` in `browser-extension/icons/`

**Option C: Use Image Editor**
- Open `icon128.svg` in Photoshop, GIMP, or any image editor
- Export as PNG in three sizes (128x128, 48x48, 16x16)

### Step 2: Ensure Laravel Server is Running 🚀

```bash
cd c:\Users\rannn\Desktop\bookShare
php artisan serve
```

Your server should be running at: `http://127.0.0.1:8000`

### Step 3: Install Extension in Chrome/Edge 🌐

1. Open Chrome/Edge browser
2. Navigate to:
   - Chrome: `chrome://extensions/`
   - Edge: `edge://extensions/`

3. **Enable Developer Mode**
   - Look for the toggle switch in the top-right corner
   - Turn it ON

4. **Load the Extension**
   - Click "Load unpacked"
   - Browse to: `C:\Users\rannn\Desktop\bookShare\browser-extension`
   - Click "Select Folder"

5. **Verify Installation**
   - You should see "BookShare Marketplace Checker" in your extensions list
   - The extension icon should appear in your browser toolbar

### Step 4: Configure Extension (Optional) ⚙️

If your server is running on a different URL:

1. Click the BookShare extension icon
2. Click "Settings"
3. Update the API URL (default: `http://127.0.0.1:8000/api/public`)
4. Click "Save Settings"

### Step 5: Test the Extension 🧪

1. **Add Test Books to Marketplace**
   - Open `http://127.0.0.1:8000/marketplace`
   - Login to your account
   - Add some books to the marketplace

2. **Test on Amazon**
   - Go to https://www.amazon.com
   - Search for one of the books you added
   - Open the book's detail page
   - The extension should automatically show a notification if the book is available

3. **Manual Test**
   - Visit any book page on a supported site
   - Click the extension icon
   - Click "Check Availability"
   - View results in the popup

### Step 6: Verify API Endpoint 🔌

Test the API manually:

```bash
# Test availability check
curl "http://127.0.0.1:8000/api/public/books/check-availability?title=Test+Book&author=Test+Author"
```

Expected response:
```json
{
  "available": true/false,
  "message": "...",
  "books": [...]
}
```

## Troubleshooting 🔧

### Extension Icon Not Loading
- Make sure you created the PNG icons (Step 1)
- Reload the extension: Go to extensions page → Click reload icon

### "Service Unavailable" Error
- Check if Laravel server is running (`php artisan serve`)
- Verify the API URL in extension settings
- Check browser console for CORS errors

### Books Not Being Detected
- Make sure you're on a supported website (Amazon, B&N, Goodreads, etc.)
- Check browser console (F12) for "BookShare Extension:" messages
- Try the manual check via the popup

### API Connection Failed
- Verify Laravel is running: Visit `http://127.0.0.1:8000`
- Check CORS configuration in `config/cors.php`
- Look for errors in browser's Network tab (F12)

### No Notification Banner
- Check "Show notifications" is enabled in Settings
- Reload the book page after enabling
- Check if popup shows results (extension might be working but banner not showing)

## Supported Websites 🌐

The extension currently works on:
- ✅ Amazon (all regions)
- ✅ Barnes & Noble
- ✅ Goodreads  
- ✅ Book Depository
- ✅ AbeBooks

## Testing Checklist ✅

- [ ] Extension installed successfully
- [ ] Icons are visible
- [ ] Laravel server running
- [ ] API endpoint responding
- [ ] Books added to marketplace
- [ ] Notification appears on Amazon
- [ ] Popup shows correct status
- [ ] Settings page works
- [ ] "View in Marketplace" link works

## Production Deployment 🚀

For production use:

1. **Update Laravel .env**
   ```
   APP_URL=https://yourdomain.com
   ```

2. **Update CORS** in `config/cors.php`
   ```php
   'allowed_origins' => ['https://yourdomain.com'],
   ```

3. **Update Extension Settings**
   - Change API URL to: `https://yourdomain.com/api/public`

4. **Publish to Chrome Web Store** (Optional)
   - Create a developer account
   - Package the extension
   - Submit for review
   - Users can install from the store

## Next Steps 🎯

1. ✅ Create extension icons
2. ✅ Install in browser
3. ✅ Test with real books
4. ✅ Customize styling if needed
5. 📦 Package for distribution (optional)

## Need Help? 💬

- Check the browser console (F12) for errors
- Review Laravel logs: `storage/logs/laravel.log`
- Test API endpoints directly with curl/Postman
- Verify database has books in `market_books` table

---

**Enjoy your BookShare Browser Extension! 📚✨**
