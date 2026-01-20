<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Cameroonian Bistro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lora:wght@600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>

    <?php include 'header.php'; ?>

    <!-- Main Checkout Section -->
    <main class="checkout-page">
        <div class="container-custom">

            <div class="checkout-title-group">
                <h1>Checkout</h1>
                <p>Please enter your delivery and payment details to complete your order.</p>
            </div>

            <div class="row">
                <!-- Left Column: Forms -->
                <div class="col-lg-7">
                    <!-- Delivery Details -->
                    <div class="form-card">
                        <div class="section-label">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 3h15v13H1V3z" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M16 8h4l3 3v5h-7V8z" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <circle cx="5.5" cy="18.5" r="2.5" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <circle cx="18.5" cy="18.5" r="2.5" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Delivery Details
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="chk-label">First Name</label>
                                <input type="text" class="chk-input" placeholder="e.g. Jean">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="chk-label">Last Name</label>
                                <input type="text" class="chk-input" placeholder="e.g. Kouam">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="chk-label">Street Address</label>
                            <input type="text" class="chk-input" placeholder="House number and street name">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="chk-label">City</label>
                                <select class="chk-select">
                                    <option value="douala">Douala</option>
                                    <option value="yaounde">Yaoundé</option>
                                    <option value="buea">Buea</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="chk-label">Phone Number</label>
                                <input type="text" class="chk-input" placeholder="+237 6XX XXX XXX">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="form-card">
                        <div class="section-label">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <line x1="1" y1="10" x2="23" y2="10" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Payment Method
                        </div>
                        <div class="payment-tabs">
                            <div class="payment-tab active" data-method="card">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <line x1="1" y1="10" x2="23" y2="10" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Credit Card
                            </div>
                            <div class="payment-tab" data-method="momo">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <line x1="12" y1="18" x2="12.01" y2="18" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Mobile Money
                            </div>
                        </div>

                        <!-- Card Details Form -->
                        <div id="card-form">
                            <div class="mb-3">
                                <label class="chk-label">Card Number</label>
                                <input type="text" class="chk-input" placeholder="0000 0000 0000 0000">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="chk-label">Expiry Date</label>
                                    <input type="text" class="chk-input" placeholder="MM / YY">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="chk-label">CVV</label>
                                    <input type="text" class="chk-input" placeholder="123">
                                </div>
                            </div>
                        </div>

                        <div class="secure-payment-info">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Your payment information is encrypted and secure
                        </div>
                    </div>
                </div>

                <!-- Right Column: Summary -->
                <div class="col-lg-5">
                    <div class="summary-card">
                        <h2>Order Summary</h2>
                        <div class="summary-items-list" id="checkout-items">
                            <!-- Populated by JS -->
                        </div>

                        <div class="summary-divider"></div>

                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="chk-subtotal">0 FCFA</span>
                        </div>
                        <div class="summary-row">
                            <span>Delivery Fee</span>
                            <span id="chk-delivery">2,500 FCFA</span>
                        </div>
                        <div class="summary-row">
                            <span>VAT (19.25%)</span>
                            <span id="chk-tax">0 FCFA</span>
                        </div>

                        <div class="summary-row total">
                            <span>Total</span>
                            <span class="total-amount" id="chk-total">0 FCFA</span>
                        </div>

                        <button class="btn-place-order" id="placeOrderBtn">
                            Place Order
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12h14M12 5l7 7-7 7" stroke="white" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>

                        <div class="summary-footer-notice">
                            SECURE PAYMENT POWERED BY STRIPE & MOBILEPAY
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/checkout.js"></script>
</body>

</html>