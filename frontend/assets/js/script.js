$(document).ready(function () {
    // === State Management ===
    let cart = JSON.parse(localStorage.getItem('bistro_cart')) || [];
    const taxRate = 0.1925; // 19.25% VAT
    const deliveryFee = 2500; // FCFA

    // === Selectors ===
    const $cartDrawer = $('#cartDrawer');
    const $cartOverlay = $('#cartOverlay');
    const $cartItemsList = $('#cartItems');
    const $cartBadge = $('.cart-badge');
    const $body = $('body');
    const $btnCheckout = $('.btn-checkout');

    // === Persistence ===
    function saveCart() {
        localStorage.setItem('bistro_cart', JSON.stringify(cart));
    }

    // === Initialize Cart UI ===
    updateCartUI();

    // === UI Toggles ===
    $('.hamburger-menu').on('click', function () {
        $('.custom-navbar-nav').toggleClass('active');
        $(this).toggleClass('active');
    });

    const toggleCart = () => {
        $cartDrawer.toggleClass('active');
        $cartOverlay.toggleClass('active');
        $body.toggleClass('cart-open');
    };

    $('.btn-icon-header, #closeCart, #cartOverlay').on('click', toggleCart);

    // === Auth State Management ===
    function checkLoginState() {
        const user = JSON.parse(localStorage.getItem('user'));
        if (user) {
            $('#auth-buttons').removeClass('d-lg-flex').addClass('d-none'); // Hide auth buttons
            $('.mobile-signin').hide(); // Hide mobile auth links
            
            $('#user-profile').removeClass('d-none').addClass('d-flex'); // Show profile
            $('#user-name').text(`Hi, ${user.full_name.split(' ')[0]}`); // Show first name
        } else {
            $('#auth-buttons').removeClass('d-none').addClass('d-lg-flex');
            $('.mobile-signin').show();
            $('#user-profile').removeClass('d-flex').addClass('d-none');
        }
    }

    // Call on load
    checkLoginState();

    // Logout Handler
    $('#btn-logout').on('click', function() {
        localStorage.removeItem('user');
        window.location.href = 'index.php';
    });

    $btnCheckout.on('click', function () {
        if (cart.length > 0) {
            window.location.href = 'checkout.php';
        } else {
            alert("Your cart is empty!");
        }
    });

    // === Add to Cart Functionality ===
    $(document).on('click', '.btn-add-cart', function () {
        const $btn = $(this);
        const $card = $btn.closest('.menu-card');
        const name = $card.find('.menu-title').text();

        const isInCart = cart.some(item => item.name === name);

        if (isInCart) {
            // Remove from cart (Toggle off)
            cart = cart.filter(item => item.name !== name);
            updateCartUI();
        } else {
            // Add to cart (Toggle on)
            const item = {
                id: Date.now() + Math.random(), // unique ID for cart entry
                meal_id: $card.data('id'),     // Actual DB ID
                name: name,
                price: parseFloat($card.find('.menu-price').text().replace(/,/g, '').replace(' FCFA', '')),
                img: $card.find('.menu-image').attr('src'),
                quantity: 1
            };
            addToCart(item);
        }
    });

    function addToCart(newItem) {
        // Check if item already exists
        const existingItem = cart.find(item => item.name === newItem.name);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push(newItem);
        }
        updateCartUI();
    }

    // === Button State Synchronization ===
    function syncButtons() {
        $('.btn-add-cart').each(function () {
            const $btn = $(this);
            const $card = $btn.closest('.menu-card');
            const name = $card.find('.menu-title').text();
            const isInCart = cart.some(item => item.name === name);

            if (isInCart) {
                // Apply Selected state if in cart
                if (!$btn.hasClass('selected')) {
                    $btn.addClass('selected').html(`
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 6L9 17L4 12" stroke="#F28C28" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Selected
                    `);
                }
            } else {
                // Revert to Add to Cart if not in cart
                $btn.removeClass('selected').text('Add to Cart');
            }
        });
    }

    // === Cart UI Updates ===
    function updateCartUI() {
        if (cart.length === 0) {
            $cartItemsList.html(`
                <div class="cart-empty-state">
                    <p>Your cart is empty.</p>
                    <p class="empty-hint">Add some delicious dishes from our menu!</p>
                </div>
            `);
            $cartBadge.hide();
        } else {
            let html = '';
            cart.forEach(item => {
                html += `
                    <div class="cart-item" data-id="${item.id}">
                        <div class="cart-item-img">
                            <img src="${item.img}" alt="${item.name}">
                        </div>
                        <div class="cart-item-info">
                            <h4 class="cart-item-name">${item.name}</h4>
                            <p class="cart-item-price">${(item.price * item.quantity).toLocaleString()} FCFA</p>
                            <div class="cart-item-qty">
                                <button class="qty-btn minus">-</button>
                                <span>${item.quantity}</span>
                                <button class="qty-btn plus">+</button>
                            </div>
                        </div>
                        <button class="remove-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" 
                                    stroke="#8A5A2B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                `;
            });
            $cartItemsList.html(html);

            // Update badge
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            $cartBadge.text(totalItems).show();
        }

        syncButtons(); // Update all button states on the page
        updateTotals();
        saveCart(); // Persist changes
    }

    function updateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const tax = subtotal * taxRate;
        const total = subtotal > 0 ? (subtotal + tax + deliveryFee) : 0;

        $('#subtotal').text(`${subtotal.toLocaleString()} FCFA`);
        $('#tax').text(`${Math.round(tax).toLocaleString()} FCFA`);
        $('#delivery-fee').text(`${subtotal > 0 ? deliveryFee.toLocaleString() : '0'} FCFA`);
        $('#total').text(`${Math.round(total).toLocaleString()} FCFA`);
    }

    // === Quantity & Removal Listeners ===
    $cartItemsList.on('click', '.qty-btn', function () {
        const id = $(this).closest('.cart-item').data('id');
        const item = cart.find(i => i.id === id);

        if ($(this).hasClass('plus')) {
            item.quantity++;
        } else if ($(this).hasClass('minus') && item.quantity > 1) {
            item.quantity--;
        }

        updateCartUI();
    });

    $cartItemsList.on('click', '.remove-item', function () {
        const id = $(this).closest('.cart-item').data('id');
        cart = cart.filter(i => i.id !== id);
        updateCartUI();
    });
});
