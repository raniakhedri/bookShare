// Background service worker
// Handles extension lifecycle and communication

// Installation
chrome.runtime.onInstalled.addListener((details) => {
  if (details.reason === 'install') {
    console.log('Bookly Extension installed');
    // Set default settings
    chrome.storage.sync.set({
      apiUrl: 'http://127.0.0.1:8000/api/public',
      enabled: true,
      showNotifications: true
    });
  } else if (details.reason === 'update') {
    console.log('Bookly Extension updated');
  }
});

// Handle messages from content scripts and popup
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
  if (request.action === 'getSettings') {
    chrome.storage.sync.get(['apiUrl', 'enabled', 'showNotifications'], (settings) => {
      sendResponse(settings);
    });
    return true; // Will respond asynchronously
  }

  if (request.action === 'saveSettings') {
    chrome.storage.sync.set(request.settings, () => {
      sendResponse({ success: true });
    });
    return true;
  }

  if (request.action === 'checkAvailability') {
    // Forward to content script of active tab
    chrome.tabs.query({ active: true, currentWindow: true }, (tabs) => {
      if (tabs[0]) {
        chrome.tabs.sendMessage(tabs[0].id, { action: 'checkAvailability' });
      }
    });
  }
});

// Handle browser action click
chrome.action.onClicked.addListener((tab) => {
  // Open popup is default behavior, this is for additional actions if needed
  console.log('Extension icon clicked');
});
