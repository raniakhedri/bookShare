// Options page script
const form = document.getElementById('settingsForm');
const apiUrlInput = document.getElementById('apiUrl');
const enabledInput = document.getElementById('enabled');
const showNotificationsInput = document.getElementById('showNotifications');
const resetBtn = document.getElementById('resetBtn');
const alert = document.getElementById('alert');

// Default settings
const defaultSettings = {
  apiUrl: 'http://127.0.0.1:8000/api/public',
  enabled: true,
  showNotifications: true
};

// Load settings
function loadSettings() {
  chrome.storage.sync.get(['apiUrl', 'enabled', 'showNotifications'], (settings) => {
    apiUrlInput.value = settings.apiUrl || defaultSettings.apiUrl;
    enabledInput.checked = settings.enabled !== false;
    showNotificationsInput.checked = settings.showNotifications !== false;
  });
}

// Show alert
function showAlert(message, type = 'success') {
  alert.textContent = message;
  alert.className = `alert ${type} show`;
  
  setTimeout(() => {
    alert.classList.remove('show');
  }, 3000);
}

// Save settings
form.addEventListener('submit', (e) => {
  e.preventDefault();
  
  const settings = {
    apiUrl: apiUrlInput.value.trim(),
    enabled: enabledInput.checked,
    showNotifications: showNotificationsInput.checked
  };

  chrome.storage.sync.set(settings, () => {
    showAlert('✅ Settings saved successfully!', 'success');
  });
});

// Reset to defaults
resetBtn.addEventListener('click', () => {
  if (confirm('Are you sure you want to reset all settings to default?')) {
    chrome.storage.sync.set(defaultSettings, () => {
      loadSettings();
      showAlert('🔄 Settings reset to default', 'success');
    });
  }
});

// Initialize
document.addEventListener('DOMContentLoaded', loadSettings);
