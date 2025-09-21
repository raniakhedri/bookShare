// Template JavaScript - Combined functionality from booklytemplate
import './bootstrap';

// Initialize Alpine.js if not already imported
document.addEventListener('DOMContentLoaded', function() {
    
    // Preloader
    const preloader = document.querySelector('.preloader');
    if (preloader) {
        window.addEventListener('load', function() {
            preloader.style.opacity = '0';
            setTimeout(() => {
                preloader.style.display = 'none';
            }, 300);
        });
    }

    // Mobile Menu Toggle
    const mobileMenuButton = document.querySelector('[data-mobile-menu-button]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    const mobileMenuClose = document.querySelector('[data-mobile-menu-close]');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.add('open');
            document.body.style.overflow = 'hidden';
        });
    }

    if (mobileMenuClose && mobileMenu) {
        mobileMenuClose.addEventListener('click', function() {
            mobileMenu.classList.remove('open');
            document.body.style.overflow = '';
        });
    }

    // Search Popup
    const searchButton = document.querySelector('[data-search-button]');
    const searchPopup = document.querySelector('[data-search-popup]');
    const searchClose = document.querySelector('[data-search-close]');

    if (searchButton && searchPopup) {
        searchButton.addEventListener('click', function() {
            searchPopup.classList.remove('hidden');
            searchPopup.querySelector('input').focus();
        });
    }

    if (searchClose && searchPopup) {
        searchClose.addEventListener('click', function() {
            searchPopup.classList.add('hidden');
        });
    }

    // Close search popup when clicking outside
    if (searchPopup) {
        searchPopup.addEventListener('click', function(e) {
            if (e.target === searchPopup) {
                searchPopup.classList.add('hidden');
            }
        });
    }

    // Smooth Scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Sticky Header
    const header = document.querySelector('header');
    if (header) {
        let lastScrollY = window.scrollY;
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                header.classList.add('bg-white', 'shadow-md');
            } else {
                header.classList.remove('bg-white', 'shadow-md');
            }

            // Hide/show header on scroll
            if (window.scrollY > lastScrollY && window.scrollY > 200) {
                header.style.transform = 'translateY(-100%)';
            } else {
                header.style.transform = 'translateY(0)';
            }
            lastScrollY = window.scrollY;
        });
    }

    // Product Image Gallery
    window.changeMainImage = function(src) {
        const mainImage = document.getElementById('mainImage');
        if (mainImage) {
            mainImage.src = src;
            
            // Update thumbnail states
            document.querySelectorAll('.thumbnail').forEach(img => {
                img.classList.remove('border-primary');
                img.classList.add('border-transparent');
            });
            
            // Add active state to clicked thumbnail
            event.target.classList.remove('border-transparent');
            event.target.classList.add('border-primary');
        }
    };

    // Quantity Controls
    window.increaseQuantity = function() {
        const input = document.getElementById('quantity');
        if (input) {
            input.value = parseInt(input.value) + 1;
        }
    };

    window.decreaseQuantity = function() {
        const input = document.getElementById('quantity');
        if (input && parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    };

    // Product Tabs
    window.showTab = function(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'border-primary', 'text-primary');
            button.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show selected tab content
        const targetTab = document.getElementById(tabName);
        if (targetTab) {
            targetTab.classList.remove('hidden');
        }
        
        // Add active class to clicked button
        if (event && event.target) {
            event.target.classList.add('active', 'border-primary', 'text-primary');
            event.target.classList.remove('border-transparent', 'text-gray-500');
        }
    };

    // Add to Cart Animation
    document.querySelectorAll('[data-add-to-cart]').forEach(button => {
        button.addEventListener('click', function() {
            const originalText = this.textContent;
            this.textContent = 'Added!';
            this.disabled = true;
            
            setTimeout(() => {
                this.textContent = originalText;
                this.disabled = false;
            }, 1500);
        });
    });

    // Newsletter Form
    const newsletterForm = document.querySelector('[data-newsletter-form]');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            if (email) {
                // Here you would typically send the email to your backend
                alert('Thank you for subscribing!');
                this.reset();
            }
        });
    }

    // Contact Form
    const contactForm = document.querySelector('[data-contact-form]');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic form validation
            const name = this.querySelector('input[name="name"]').value;
            const email = this.querySelector('input[name="email"]').value;
            const message = this.querySelector('textarea[name="message"]').value;
            
            if (name && email && message) {
                // Here you would typically send the form data to your backend
                alert('Thank you for your message! We will get back to you soon.');
                this.reset();
            } else {
                alert('Please fill in all required fields.');
            }
        });
    }

    // FAQ Accordion
    document.querySelectorAll('[data-faq-button]').forEach(button => {
        button.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('svg');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                if (icon) icon.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        });
    });

    // Lazy Loading for Images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Back to Top Button
    const backToTop = document.querySelector('[data-back-to-top]');
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTop.classList.remove('hidden');
            } else {
                backToTop.classList.add('hidden');
            }
        });

        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Initialize tooltips and other interactive elements
    initializeTooltips();
    initializeCarousels();
});

// Tooltip initialization
function initializeTooltips() {
    document.querySelectorAll('[data-tooltip]').forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute z-50 px-2 py-1 text-sm bg-gray-900 text-white rounded shadow-lg';
            tooltip.textContent = this.dataset.tooltip;
            tooltip.style.top = this.offsetTop - 30 + 'px';
            tooltip.style.left = this.offsetLeft + 'px';
            this.parentNode.appendChild(tooltip);
        });

        element.addEventListener('mouseleave', function() {
            const tooltip = this.parentNode.querySelector('.absolute.z-50');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });
}

// Carousel initialization (if needed)
function initializeCarousels() {
    document.querySelectorAll('[data-carousel]').forEach(carousel => {
        const slides = carousel.querySelectorAll('[data-slide]');
        const prevBtn = carousel.querySelector('[data-prev]');
        const nextBtn = carousel.querySelector('[data-next]');
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('hidden', i !== index);
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentSlide = currentSlide > 0 ? currentSlide - 1 : slides.length - 1;
                showSlide(currentSlide);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentSlide = currentSlide < slides.length - 1 ? currentSlide + 1 : 0;
                showSlide(currentSlide);
            });
        }

        // Auto-advance slides every 5 seconds
        setInterval(() => {
            if (nextBtn) nextBtn.click();
        }, 5000);
    });
}