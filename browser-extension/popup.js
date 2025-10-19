// Popup script
const checkBtn = document.getElementById('checkBtn');
const settingsBtn = document.getElementById('settingsBtn');
const statusCard = document.getElementById('statusCard');
const statusText = document.getElementById('statusText');
const bookInfo = document.getElementById('bookInfo');
const bookTitle = document.getElementById('bookTitle');
const bookAuthor = document.getElementById('bookAuthor');

// Check availability when button is clicked
checkBtn.addEventListener('click', async () => {
  checkBtn.disabled = true;
  statusCard.className = 'status-card loading';
  statusText.innerHTML = '<span class="loading-spinner"></span>Checking...';
  bookInfo.style.display = 'none';

  try {
    // Get current tab
    const [tab] = await chrome.tabs.query({ active: true, currentWindow: true });
    
    // Execute script to get book info from page
    const results = await chrome.scripting.executeScript({
      target: { tabId: tab.id },
      function: extractBookInfoFromPage
    });

    if (results && results[0] && results[0].result) {
      const bookData = results[0].result;
      
      if (!bookData) {
        statusCard.className = 'status-card';
        statusText.textContent = 'No book detected on this page';
        checkBtn.disabled = false;
        return;
      }

      // Show book info
      bookInfo.style.display = 'block';
      bookTitle.textContent = bookData.title;
      bookAuthor.textContent = bookData.author || 'Unknown';

      // Check availability via API
      const settings = await chrome.storage.sync.get(['apiUrl']);
      const apiUrl = settings.apiUrl || 'http://127.0.0.1:8000/api/public';
      
      const params = new URLSearchParams();
      if (bookData.title) params.append('title', bookData.title);
      if (bookData.author) params.append('author', bookData.author);

      const response = await fetch(`${apiUrl}/books/check-availability?${params.toString()}`);
      const data = await response.json();

      if (data.available) {
        statusCard.className = 'status-card available';
        statusText.textContent = `✅ ${data.count} ${data.count === 1 ? 'copy' : 'copies'} available!`;
        
        // Trigger notification on page
        chrome.tabs.sendMessage(tab.id, { action: 'checkAvailability' });
      } else {
        statusCard.className = 'status-card not-available';
        statusText.textContent = '❌ Not available in marketplace';
      }
    } else {
      statusCard.className = 'status-card';
      statusText.textContent = 'Unable to detect book information';
    }
  } catch (error) {
    console.error('Error:', error);
    statusCard.className = 'status-card';
    statusText.textContent = '⚠️ Error checking availability';
  } finally {
    checkBtn.disabled = false;
  }
});

// Open settings
settingsBtn.addEventListener('click', () => {
  chrome.runtime.openOptionsPage();
});

// Function to inject into page to extract book info
function extractBookInfoFromPage() {
  // Site patterns
  const sitePatterns = {
    amazon: {
      hostPattern: /amazon\.(com|co\.uk|fr|de|es|it)/,
      selectors: {
        title: '#productTitle, h1.a-size-large',
        author: '.author .a-link-normal, .contributorNameID, a[data-asin][class*="author"]'
      }
    },
    barnesandnoble: {
      hostPattern: /barnesandnoble\.com/,
      selectors: {
        title: 'h1.pdp-title',
        author: '.contributors a'
      }
    },
    goodreads: {
      hostPattern: /goodreads\.com/,
      selectors: {
        title: 'h1.Text__title1',
        author: 'span.ContributorLink__name'
      }
    }
  };

  // Detect website
  const hostname = window.location.hostname;
  let config = null;
  
  for (const pattern of Object.values(sitePatterns)) {
    if (pattern.hostPattern.test(hostname)) {
      config = pattern;
      break;
    }
  }

  if (!config) return null;

  // Extract info
  const titleEl = document.querySelector(config.selectors.title);
  const authorEl = document.querySelector(config.selectors.author);

  if (!titleEl) return null;

  return {
    title: titleEl.textContent.trim(),
    author: authorEl ? authorEl.textContent.replace(/^by\s+/i, '').trim() : null
  };
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  // Check if we're on a supported site
  chrome.tabs.query({ active: true, currentWindow: true }, (tabs) => {
    if (tabs[0]) {
      const url = new URL(tabs[0].url);
      const supportedSites = ['amazon.com', 'barnesandnoble.com', 'goodreads.com', 'bookdepository.com', 'abebooks.com'];
      const isSupported = supportedSites.some(site => url.hostname.includes(site));
      
      if (!isSupported) {
        statusText.textContent = 'Navigate to a book seller website';
        checkBtn.disabled = true;
      }
    }
  });
});
