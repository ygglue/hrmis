/**
 * HRMIS - Centralized JavaScript Utilities
 * Common functions and utilities used across the entire application
 */

// ============================================
// MESSAGE SYSTEM
// ============================================

/**
 * Show a message to the user
 * @param {string} text - Message text
 * @param {string} type - Message type: 'success', 'error', 'info'
 * @param {number} duration - Duration in milliseconds (default: 5000)
 */
function showMessage(text, type = 'info', duration = 5000) {
    // Remove existing messages
    const existingMessages = document.querySelectorAll('.message');
    existingMessages.forEach(msg => msg.remove());

    // Create new message
    const message = document.createElement('div');
    message.className = `message ${type}`;
    
    const icon = type === 'success' ? '✓' : type === 'error' ? '⚠' : 'ℹ';
    message.innerHTML = `
        <span>${icon}</span>
        <span>${text}</span>
    `;

    // Insert at top of body or container
    const container = document.querySelector('.container') || document.body;
    container.insertBefore(message, container.firstChild);

    // Auto-remove after duration
    setTimeout(() => {
        message.style.animation = 'fadeOut 0.4s ease';
        setTimeout(() => message.remove(), 400);
    }, duration);
}

// ============================================
// VALIDATION UTILITIES
// ============================================

/**
 * Validate email format
 * @param {string} email - Email address to validate
 * @returns {boolean}
 */
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Validate phone number (basic numeric check)
 * @param {string} phone - Phone number to validate
 * @returns {boolean}
 */
function isValidPhone(phone) {
    const re = /^\d{10,15}$/;
    return re.test(phone.replace(/\D/g, ''));
}

/**
 * Validate date is not in the future
 * @param {string} dateString - Date string to validate
 * @returns {boolean}
 */
function isNotFutureDate(dateString) {
    const selectedDate = new Date(dateString);
    const today = new Date();
    return selectedDate <= today;
}

/**
 * Calculate age from birthdate
 * @param {string} birthdate - Birthdate string
 * @returns {number} Age in years
 */
function calculateAge(birthdate) {
    const today = new Date();
    const birthDate = new Date(birthdate);
    return Math.floor((today - birthDate) / (365.25 * 24 * 60 * 60 * 1000));
}

// ============================================
// FORM UTILITIES
// ============================================

/**
 * Format phone number input (remove non-numeric characters)
 * @param {HTMLInputElement} input - Phone input element
 */
function formatPhoneInput(input) {
    input.addEventListener('input', function () {
        let value = this.value.replace(/\D/g, '');
        this.value = value;
    });
}

/**
 * Set up real-time validation for required fields
 * @param {HTMLFormElement} form - Form element
 */
function setupRequiredFieldValidation(form) {
    const requiredInputs = form.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function () {
            if (!this.value.trim()) {
                this.style.borderColor = 'rgba(245, 87, 108, 0.5)';
            } else {
                this.style.borderColor = 'rgba(75, 254, 159, 0.5)';
            }
        });

        input.addEventListener('input', function () {
            if (this.value.trim()) {
                this.style.borderColor = '';
            }
        });
    });
}

/**
 * Scroll to first invalid field in form
 * @param {HTMLFormElement} form - Form element
 */
function scrollToFirstInvalid(form) {
    const firstInvalid = form.querySelector('[required]:invalid, [required][value=""]');
    if (firstInvalid) {
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstInvalid.focus();
    }
}

/**
 * Collect form data including radio buttons and checkboxes
 * @param {HTMLFormElement} form - Form element
 * @returns {Object} Form data object
 */
function collectFormData(form) {
    const formData = new FormData(form);
    const data = {};

    // Convert FormData to object
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }

    return data;
}

// ============================================
// LOCAL STORAGE UTILITIES
// ============================================

/**
 * Save data to localStorage
 * @param {string} key - Storage key
 * @param {*} data - Data to save (will be JSON stringified)
 */
function saveToLocalStorage(key, data) {
    try {
        localStorage.setItem(key, JSON.stringify(data));
        return true;
    } catch (e) {
        console.error('Error saving to localStorage:', e);
        return false;
    }
}

/**
 * Load data from localStorage
 * @param {string} key - Storage key
 * @returns {*} Parsed data or null
 */
function loadFromLocalStorage(key) {
    try {
        const data = localStorage.getItem(key);
        return data ? JSON.parse(data) : null;
    } catch (e) {
        console.error('Error loading from localStorage:', e);
        return null;
    }
}

/**
 * Remove data from localStorage
 * @param {string} key - Storage key
 */
function removeFromLocalStorage(key) {
    try {
        localStorage.removeItem(key);
        return true;
    } catch (e) {
        console.error('Error removing from localStorage:', e);
        return false;
    }
}

// ============================================
// ANIMATION UTILITIES
// ============================================

/**
 * Set up intersection observer for scroll animations
 * @param {string} selector - CSS selector for elements to observe
 * @param {string} animation - Animation name to apply
 */
function setupScrollAnimation(selector, animation = 'fadeInUp 0.6s ease') {
    const elements = document.querySelectorAll(selector);
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = animation;
            }
        });
    }, observerOptions);

    elements.forEach(element => {
        observer.observe(element);
    });
}

// ============================================
// LOADING STATE UTILITIES
// ============================================

/**
 * Set button loading state
 * @param {HTMLButtonElement} button - Button element
 * @param {boolean} isLoading - Loading state
 */
function setButtonLoading(button, isLoading) {
    if (isLoading) {
        button.classList.add('loading');
        button.disabled = true;
    } else {
        button.classList.remove('loading');
        button.disabled = false;
    }
}

// ============================================
// DEBOUNCE UTILITY
// ============================================

/**
 * Debounce function calls
 * @param {Function} func - Function to debounce
 * @param {number} wait - Wait time in milliseconds
 * @returns {Function} Debounced function
 */
function debounce(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ============================================
// CONFIRMATION DIALOG
// ============================================

/**
 * Show confirmation dialog
 * @param {string} message - Confirmation message
 * @returns {boolean} User's choice
 */
function confirmAction(message) {
    return confirm(message);
}

// ============================================
// DYNAMIC STYLE INJECTION
// ============================================

// Add fadeOut animation if not already in CSS
if (!document.querySelector('style[data-animations]')) {
    const style = document.createElement('style');
    style.setAttribute('data-animations', 'true');
    style.textContent = `
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }
    `;
    document.head.appendChild(style);
}

// ============================================
// CONSOLE UTILITIES
// ============================================

/**
 * Log to console with timestamp (development only)
 * @param {string} message - Message to log
 * @param {*} data - Optional data to log
 */
function devLog(message, data = null) {
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        const timestamp = new Date().toLocaleTimeString();
        console.log(`[${timestamp}] ${message}`, data || '');
    }
}

// Export for use in other scripts (if using modules)
// export { showMessage, isValidEmail, isValidPhone, calculateAge, ... };
