$(document).ready(function () {
    // === State Management ===
    let cart = JSON.parse(localStorage.getItem('bistro_cart')) || [];
    const vatRate = 0.1925; // 19.25%
    const deliveryFee = 2500; // FCFA

    // === Selectors ===
    const $checkoutItems = $('#checkout-items');
    const $subtotalEl = $('#chk-subtotal');
    const $taxEl = $('#chk-tax');
    const $totalEl = $('#chk-total');
    const $paymentTabs = $('.payment-tab');
    const $placeOrderBtn = $('#placeOrderBtn');

    // === Currency Formatter ===
    function formatFCFA(amount) {
        return amount.toLocaleString('en-US') + ' FCFA';
    }

    // === Initialize ===
    renderSummary();
    updateBadge();

    // === UI Toggles ===
    $('.hamburger-menu').on('click', function () {
        $('.custom-navbar-nav').toggleClass('active');
        $(this).toggleClass('active');
    });

    // === Badge Update ===
    function updateBadge() {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const $badge = $('.cart-badge');
        if (totalItems > 0) {
            $badge.text(totalItems).show();
        } else {
            $badge.hide();
        }
    }

    // === Render Logic ===
    function renderSummary() {
        if (cart.length === 0) {
            $checkoutItems.html('<p class="text-muted">Your cart is empty.</p>');
            $subtotalEl.text(formatFCFA(0));
            $taxEl.text(formatFCFA(0));
            $totalEl.text(formatFCFA(0));
            return;
        }

        let html = '';
        let subtotal = 0;

        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;

            html += `
                <div class="summary-item">
                    <img src="${item.img}" alt="${item.name}" class="summary-item-img">
                    <div class="summary-item-details">
                        <div class="summary-item-name">${item.name}</div>
                        <div class="summary-item-qty">Quantity: ${item.quantity}</div>
                    </div>
                    <div class="summary-item-price">${formatFCFA(itemTotal)}</div>
                </div>
            `;
        });

        $checkoutItems.html(html);

        const tax = subtotal * vatRate;
        const total = subtotal + tax + deliveryFee;

        $subtotalEl.text(formatFCFA(subtotal));
        $taxEl.text(formatFCFA(Math.round(tax)));
        $totalEl.text(formatFCFA(Math.round(total)));
    }

    // === Interaction Logic ===
    $paymentTabs.on('click', function () {
        $paymentTabs.removeClass('active');
        $(this).addClass('active');

        const method = $(this).data('method');
        if (method === 'card') {
            $('#card-form').slideDown();
        } else {
            $('#card-form').slideUp();
        }
    });

    $placeOrderBtn.on('click', function () {
        if (cart.length === 0) {
            alert("Your cart is empty!");
            return;
        }

        // Collect form data
        // For simplicity, we are grabbing just what the API needs.
        // In a real app, strict validation of these fields is needed.
        const addressInput = $('.chk-input').eq(2).val(); // Street address index
        const city = $('.chk-select').val();
        const deliveryAddress = addressInput ? (addressInput + ', ' + city) : city;
        const paymentMethod = $('.payment-tab.active').data('method');

        // Build items array
        const orderItems = cart.map(item => ({
            meal_id: item.meal_id,
            quantity: item.quantity
        }));

        $placeOrderBtn.prop('disabled', true).text('Processing...');

        $.ajax({
            url: '../backend/api/place_order.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                items: orderItems,
                payment_method: paymentMethod,
                delivery_address: deliveryAddress || 'Douala' // Default if empty
            }),
            success: function(response) {
                if(response.success) {
                    // clear cart
                    localStorage.removeItem('bistro_cart');
                    alert("Order placed successfully! Order ID: " + response.data.order_id);
                    window.location.href = 'index.php';
                }
            },
            error: function(xhr) {
                $placeOrderBtn.prop('disabled', false).html(`Place Order <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 12h14M12 5l7 7-7 7" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>`);
                
                if (xhr.status === 401) {
                    alert("Please sign in to place an order.");
                    window.location.href = 'signin.php';
                } else {
                    let msg = 'Order failed.';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    alert(msg);
                }
            }
        });
    });
});
