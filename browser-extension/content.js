// Content script - runs on book seller websites
// Detects book information and checks availability in BookShare marketplace

// Configuration
const API_BASE_URL = 'http://127.0.0.1:8000/api/public';

// Book information extraction patterns for different websites
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
  },
  bookdepository: {
    hostPattern: /bookdepository\.com/,
    selectors: {
      title: 'h1[itemprop="name"]',
      author: 'span[itemprop="author"] a'
    }
  },
  abebooks: {
    hostPattern: /abebooks\.com/,
    selectors: {
      title: 'h1[itemprop="name"]',
      author: 'span[itemprop="author"]'
    }
  },
  alkitab: {
    hostPattern: /alkitab\.tn/,
    selectors: {
      title: 'h1.titre, .livre_titre',
      author: 'h2.livre_auteur, .auteur'
    }
  }
};

// Detect current website
function detectWebsite() {
  const hostname = window.location.hostname;
  for (const [site, pattern] of Object.entries(sitePatterns)) {
    if (pattern.hostPattern.test(hostname)) {
      return { site, config: pattern };
    }
  }
  return null;
}

// Extract text from element
function extractText(selector) {
  try {
    const element = document.querySelector(selector);
    return element ? element.textContent.trim() : null;
  } catch (e) {
    // Invalid selector, return null
    return null;
  }
}

// Extract book information from current page
function extractBookInfo() {
  const website = detectWebsite();
  if (!website) return null;

  const { selectors } = website.config;
  
  const title = extractText(selectors.title);
  const author = extractText(selectors.author);

  // Clean up extracted data
  const cleanTitle = title ? title.replace(/\s+/g, ' ').trim() : null;
  const cleanAuthor = author ? author.replace(/\s+/g, ' ').replace(/^by\s+/i, '').trim() : null;

  if (!cleanTitle) return null;

  return {
    title: cleanTitle,
    author: cleanAuthor,
    website: website.site,
    url: window.location.href
  };
}

// Check availability in BookShare marketplace
async function checkAvailability(bookInfo) {
  try {
    const params = new URLSearchParams();
    if (bookInfo.title) params.append('title', bookInfo.title);
    if (bookInfo.author) params.append('author', bookInfo.author);

    const response = await fetch(`${API_BASE_URL}/books/check-availability?${params.toString()}`);
    const data = await response.json();
    
    return data;
  } catch (error) {
    console.error('Bookly Extension: Error checking availability', error);
    return { available: false, error: error.message };
  }
}

// Create and inject notification banner
function createNotificationBanner(bookInfo, availabilityData) {
  // Remove existing banner if any
  const existingBanner = document.getElementById('bookshare-notification');
  if (existingBanner) {
    existingBanner.remove();
  }

  const banner = document.createElement('div');
  banner.id = 'bookshare-notification';
  banner.className = 'bookshare-banner';

  if (availabilityData.available) {
    banner.classList.add('bookshare-available');
    const count = availabilityData.count || 0;
    const lowestPrice = availabilityData.books.reduce((min, book) => 
      Math.min(min, parseFloat(book.price) || Infinity), Infinity);
    
    banner.innerHTML = `
      <div class="bookshare-banner-content">
        <div class="bookshare-banner-header">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z"/>
            <circle cx="12" cy="12" r="4"/>
          </svg>
          <h3>📚 Available on Bookly!</h3>
        </div>
        <p><strong>${count}</strong> ${count === 1 ? 'copy' : 'copies'} available in the marketplace</p>
        ${lowestPrice !== Infinity ? `<p>Lowest price: <strong>$${lowestPrice.toFixed(2)}</strong></p>` : ''}
        <div class="bookshare-banner-actions">
          <a href="${availabilityData.books[0].marketplace_url}" target="_blank" class="bookshare-btn-primary">
            View in Marketplace
          </a>
          <button class="bookshare-btn-secondary" id="bookshare-show-details">
            Show All (${count})
          </button>
        </div>
      </div>
    `;
  } else {
    banner.classList.add('bookshare-not-available');
    banner.innerHTML = `
      <div class="bookshare-banner-content">
        <div class="bookshare-banner-header">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
          </svg>
          <h3>Not in Bookly Marketplace</h3>
        </div>
        <p>This book is not currently available in the Bookly marketplace.</p>
        <a href="http://127.0.0.1:8000/marketplace" target="_blank" class="bookshare-btn-secondary">
          Browse Marketplace
        </a>
      </div>
    `;
  }

  // Insert banner at the top of the page
  document.body.insertBefore(banner, document.body.firstChild);

  // Add event listener for "Show All" button
  if (availabilityData.available) {
    const showDetailsBtn = document.getElementById('bookshare-show-details');
    if (showDetailsBtn) {
      showDetailsBtn.addEventListener('click', () => {
        showDetailsModal(availabilityData.books);
      });
    }
  }

  // Add close button
  const closeBtn = document.createElement('button');
  closeBtn.className = 'bookshare-banner-close';
  closeBtn.innerHTML = '×';
  closeBtn.onclick = () => banner.remove();
  banner.appendChild(closeBtn);
}

// Show details modal
function showDetailsModal(books) {
  const modal = document.createElement('div');
  modal.className = 'bookshare-modal';
  modal.innerHTML = `
    <div class="bookshare-modal-content">
      <div class="bookshare-modal-header">
        <h2>Available Copies</h2>
        <button class="bookshare-modal-close">×</button>
      </div>
      <div class="bookshare-modal-body">
        ${books.map(book => `
          <div class="bookshare-book-card">
            ${book.image ? `<img src="${book.image}" alt="${book.title}">` : '<div class="bookshare-no-image">No Image</div>'}
            <div class="bookshare-book-info">
              <h3>${book.title}</h3>
              <p class="bookshare-author">${book.author}</p>
              <p class="bookshare-condition">Condition: <strong>${book.condition}</strong></p>
              <p class="bookshare-owner">Seller: ${book.owner.name}</p>
              <a href="${book.marketplace_url}" target="_blank" class="bookshare-btn-primary">View Details</a>
            </div>
          </div>
        `).join('')}
      </div>
    </div>
  `;

  document.body.appendChild(modal);

  // Close modal on click
  modal.querySelector('.bookshare-modal-close').addEventListener('click', () => {
    modal.remove();
  });
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.remove();
    }
  });
}

// Main function - check and display availability
async function checkAndDisplayAvailability() {
  const bookInfo = extractBookInfo();
  
  if (!bookInfo) {
    console.log('BookShare Extension: No book information found on this page');
    return;
  }

  console.log('BookShare Extension: Book detected', bookInfo);

  const availabilityData = await checkAvailability(bookInfo);
  console.log('BookShare Extension: Availability data', availabilityData);

  createNotificationBanner(bookInfo, availabilityData);
}

// Initialize when page is loaded
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', checkAndDisplayAvailability);
} else {
  checkAndDisplayAvailability();
}

// Listen for messages from popup
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
  if (request.action === 'checkAvailability') {
    checkAndDisplayAvailability();
  }
});
