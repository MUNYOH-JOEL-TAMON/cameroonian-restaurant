<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Explore the authentic soul of Cameroon with our full menu featuring traditional dishes from grilled tilapia to Ndole.">
    <title>The Full Menu - Cameroonian Bistro</title>

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


    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container-custom">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="hero-title">The Full Menu</h1>
                    <p class="hero-subtitle">Savor the authentic soul of Cameroon. From the coastal flavors of grilled
                        tilapia to the heart-warming Ndole of the Littoral region.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <button class="btn-specials">
                        <svg class="btn-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path
                                d="M8 1L10.163 5.38L15 6.12L11.5 9.545L12.326 14.36L8 12.09L3.674 14.36L4.5 9.545L1 6.12L5.837 5.38L8 1Z"
                                fill="white" />
                        </svg>
                        View Today's Specials
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter and Sort Section -->
    <section class="filter-section">
        <div class="container-custom">
            <div class="row align-items-center">
                <div class="col-lg-9">
                    <div class="category-tabs">
                        <button class="tab-btn active">All Dishes</button>
                        <button class="tab-btn">Starters</button>
                        <button class="tab-btn">Main Course</button>
                        <button class="tab-btn">Grilled Specialties</button>
                        <button class="tab-btn">Sides</button>
                        <button class="tab-btn">Drinks</button>
                    </div>
                </div>
                <div class="col-lg-3 text-lg-end mt-3 mt-lg-0">
                    <button class="sort-btn">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M3 5H13" stroke="#1C1A17" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M5 8H11" stroke="#1C1A17" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M7 11H9" stroke="#1C1A17" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        Sort by Price
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Grid Section -->
    <section class="menu-grid-section">
        <div class="container-custom">
            <div id="menu-grid-container" class="row g-4 d-none">
                <!-- Template for cloning (hidden) -->
                <div class="col-12 col-sm-6 col-lg-3 menu-item-template">
                    <div class="menu-card">
                        <div class="menu-image-wrapper">
                            <button class="btn-love">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </button>
                            <img src="assets/images/image2.jpg" alt="Meal Name" class="menu-image">
                        </div>
                        <div class="card-content">
                            <h3 class="menu-title">Meal Name</h3>
                            <div class="product-rating">
                                <span class="star">★</span>
                                <span class="star">★</span>
                                <span class="star">★</span>
                                <span class="star">★</span>
                                <span class="star">★</span>
                            </div>
                            <p class="menu-price">0 FCFA</p>
                            <div class="card-actions">
                                <button class="btn-add-cart">Add to Cart</button>
                                <button class="btn-view">View</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="menu-loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            
            <div id="dynamic-menu-row" class="row g-4">
                <!-- Dynamic content will be injected here -->
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS for interactivity -->
    <script>
        // Category filter tabs
        const tabButtons = document.querySelectorAll('.tab-btn');
        tabButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                tabButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';
        
        // Fetch Meals
        $(document).ready(function() {
            $.ajax({
                url: '../backend/api/get_meals.php',
                method: 'GET',
                success: function(response) {
                    $('#menu-loading').hide();
                    
                    if(response.success && response.data) {
                        const template = $('.menu-item-template');
                        const container = $('#dynamic-menu-row');
                        
                        response.data.forEach(meal => {
                            const clone = template.clone().removeClass('menu-item-template mb-0');
                            
                            // Map existing images randomly if null for demo purposes, or use default
                            // Since we don't have exact URL mapping in DB yet, we'll try to pick a nice one or default
                            let imgUrl = meal.image_url ? 'assets/images/' + meal.image_url : 'assets/images/image2.jpg';
                            
                            clone.find('.menu-title').text(meal.meal_name);
                            clone.find('.menu-price').text(parseFloat(meal.price).toLocaleString() + ' FCFA');
                            clone.find('.menu-image').attr('src', imgUrl).attr('alt', meal.meal_name);
                            
                            // Add meal_id data attribute for cart
                            clone.find('.menu-card').attr('data-id', meal.meal_id);
                            
                            container.append(clone);
                        });
                        
                        // Re-sync buttons with cart state (defined in script.js)
                        if(typeof syncButtons === 'function') {
                            syncButtons();
                        }
                    } else {
                        $('#dynamic-menu-row').html('<div class="col-12 text-center">Failed to load menu.</div>');
                    }
                },
                error: function() {
                    $('#menu-loading').hide();
                    $('#dynamic-menu-row').html('<div class="col-12 text-center">Error connecting to server.</div>');
                }
            });
        });
    </script>
    <!-- Cart Overlay -->
    <div class="cart-overlay" id="cartOverlay"></div>

    <!-- Cart Drawer -->
    <div class="cart-drawer" id="cartDrawer">
        <div class="cart-header">
            <div class="cart-title-area">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 20a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" fill="#F28C28" />
                    <path d="M20 20a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" fill="#F28C28" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" stroke="#F28C28"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h2 class="cart-title">Your Order</h2>
            </div>
            <button class="cart-close" id="closeCart">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18M6 6l12 12" stroke="#1C1A17" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>

        <div class="cart-content" id="cartItems">
            <!-- Empty Cart Placeholder -->
            <div class="cart-empty-state">
                <p>Your cart is empty.</p>
                <p class="empty-hint">Add some delicious dishes from our menu!</p>
            </div>
        </div>

        <!-- Special Instructions -->
        <div class="cart-instructions">
            <label for="special-instructions">Special Instructions</label>
            <textarea id="special-instructions" placeholder="e.g. Allergies, spiciness level..."></textarea>
        </div>

        <div class="cart-footer">
            <div class="cart-summary">
                <div class="summary-line">
                    <span>Subtotal</span>
                    <span id="subtotal">0 FCFA</span>
                </div>
                <div class="summary-line">
                    <span>Delivery Fee</span>
                    <span id="delivery-fee">0 FCFA</span>
                </div>
                <div class="summary-line">
                    <span>Tax</span>
                    <span id="tax">0 FCFA</span>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span id="total">0 FCFA</span>
                </div>
            </div>
            <button class="btn-checkout">
                Proceed to Checkout
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 12h14M13 5l7 7-7 7" stroke="white" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
            <p class="secure-text">SECURE PAYMENT GUARANTEED</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>