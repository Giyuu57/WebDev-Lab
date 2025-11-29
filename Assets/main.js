document.addEventListener('DOMContentLoaded', () => {

    // ============================================
    // 1. STAGGERED SCROLL REVEAL (The "Wave" Effect)
    // ============================================
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                // Trigger the visibility
                entry.target.style.opacity = "1";
                entry.target.style.transform = "translateY(0)";
                
                // Stop observing once visible
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Select elements to animate
    const animatedElements = document.querySelectorAll('.game-card, .section-title, .hero-content');
    
    animatedElements.forEach((el, index) => {
        // 1. Set start state (invisible and pushed down)
        el.style.opacity = "0";
        el.style.transform = "translateY(30px)";
        
        // 2. Calculate Staggered Delay
        // (index % 5) means the delay resets every 5 items.
        // Item 1: 0ms, Item 2: 100ms, Item 3: 200ms... Item 6: 0ms
        const delay = (index % 5) * 100; 
        
        // 3. Apply Transition with the calculated delay
        el.style.transition = `opacity 0.6s ease ${delay}ms, transform 0.6s ease ${delay}ms`;
        
        // 4. Start Watching
        observer.observe(el);
    });


    // ============================================
    // 2. CHECKOUT INPUT MASKING (MM/YY & CVV)
    // ============================================
    const expiryInput = document.getElementById('expiry-date');
    const cvvInput = document.querySelector('input[placeholder="123"]'); // CVV field
    const cardInput = document.querySelector('input[placeholder="0000 0000 0000 0000"]'); // Card Number

    if (expiryInput) {
        expiryInput.addEventListener('input', function(e) {
            // Remove non-numbers
            var input = this.value.replace(/\D/g, '');
            // Add Slash automatically
            if (input.length > 2) {
                input = input.substring(0, 2) + '/' + input.substring(2, 4);
            }
            this.value = input;
        });
    }

    if (cvvInput) {
        cvvInput.addEventListener('input', function() {
            // Limit to 3 numbers only
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3);
        });
    }

    if (cardInput) {
        cardInput.addEventListener('input', function() {
            // Groups of 4 numbers
            this.value = this.value.replace(/[^0-9]/g, '').replace(/(.{4})/g, '$1 ').trim().slice(0, 19);
        });
    }


    // ============================================
    // 3. AUTO-HIDE ALERTS
    // ============================================
    const alerts = document.querySelectorAll('div[style*="background: #00b894"], div[style*="background: var(--danger-color)"]');
    
    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(alert => {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);
    }


    // ============================================
    // 4. CART BUTTON EFFECTS
    // ============================================
    const cartBtns = document.querySelectorAll('.btn-add-cart');
    cartBtns.forEach(btn => {
        const originalText = btn.innerHTML;
        
        btn.addEventListener('mouseenter', () => {
            btn.innerHTML = '<i class="fa fa-cart-plus"></i> Grab It!';
            btn.style.transform = "scale(1.05)";
        });
        
        btn.addEventListener('mouseleave', () => {
            btn.innerHTML = originalText;
            btn.style.transform = "scale(1)";
        });
    });


    // ============================================
    // 5. UTILITIES (Search & Images)
    // ============================================
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            const input = searchForm.querySelector('input[name="q"]');
            if (input.value.trim() === "") {
                e.preventDefault();
                input.focus();
                input.style.boxShadow = "0 0 10px var(--accent-color)";
                setTimeout(() => input.style.boxShadow = "none", 2000);
            }
        });
    }

    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('error', () => {
            img.src = 'https://via.placeholder.com/300x200?text=No+Image';
        });
    });

});