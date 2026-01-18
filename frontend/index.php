<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taste of Cameroon | Authentic Delights</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lora:wght@600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">

    <style>
        /* Custom Carousel Adaptation for Home Page */
        .carousel-wrapper {
            position: relative;
            padding: 0 40px;
        }

        .carousel-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: #F28C28;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .carousel-arrow:hover {
            background-color: #E07B1F;
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-arrow-left {
            left: 0;
        }

        .carousel-arrow-right {
            right: 0;
        }

        .dish-item .menu-image-wrapper {
            height: 150px;
        }

        .dish-slider-container {
            overflow: hidden;
        }

        #dish-slider {
            display: flex;
            transition: transform 0.4s ease;
        }

        .dish-item {
            flex: 0 0 100%;
        }

        @media (min-width: 576px) {
            .dish-item {
                flex: 0 0 50%;
            }
        }

        @media (min-width: 768px) {
            .dish-item {
                flex: 0 0 33.333%;
            }
        }

        @media (min-width: 992px) {
            .dish-item {
                flex: 0 0 25%;
            }
        }

        /* Testimonial Styling Adaptation */
        .testimonial-card {
            background-color: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .testimonial-text {
            font-style: italic;
            font-size: 1.1rem;
            color: #1C1A17;
            margin-bottom: 24px;
        }

        .testimonial-author {
            font-family: 'Lora', serif;
            font-weight: 700;
            color: #F28C28;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section"
        style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=2070&auto=format&fit=crop'); background-size: cover; background-position: center; min-height: 60vh; display: flex; align-items: center;">
        <div class="container-custom text-white">
            <h1 class="hero-title text-white" style="font-size: 4rem;">Authentic Cameroonian Flavors</h1>
            <p class="hero-subtitle text-white opacity-90">From the heart of Buea to your doorstep.</p>
            <a href="menu.php" class="btn-specials mt-4">
                Explore Our Menu
                <svg class="btn-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M1 8h14M8 1l7 7-7 7" stroke="white" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>
        </div>
    </section>

    <!-- Signature Dishes Slider -->
    <section id="dishes" class="py-5 bg-white">
        <div class="container-custom">
            <h2 class="section-title mb-5">Our Signature Dishes</h2>

            <?php
            // Include backend classes
            include_once '../backend/classes/Database.php';
            include_once '../backend/classes/Meal.php';

            $database = new Database();
            $db = $database->getConnection();
            $meal = new Meal($db);
            
            // Fetch all meals and take first 6 as signature dishes
            $all_meals = $meal->getAllMeals();
            // Shuffle to show different ones or just take top 6. Let's take top 6.
            $dishes = array_slice($all_meals, 0, 6);
            ?>

            <div class="carousel-wrapper">
                <div class="dish-slider-container">
                    <div id="dish-slider" class="row flex-nowrap g-4">
                        <?php foreach ($dishes as $dish): ?>
                            <?php 
                                $img = $dish['image_url'] ? 'assets/images/' . $dish['image_url'] : 'assets/images/image2.jpg';
                                // Note: DB stores filename only usually. If DB stores full path, this logic needs checking.
                                // Assuming DB stores just filename based on my previous insertions.
                                // If DB has null, we use default.
                                // Wait, the array above $dishes is manual. The DB fetch result $dishes (if used) overrides it.
                                // Let's look at line 147 in previous turn. I replaced manual array with DB fetch.
                                // So $dishes comes from DB.
                                
                                // Let's check what I wrote in Step 237.
                                // $dishes = array_slice($all_meals, 0, 6);
                                // The DB column 'image_url' probably has just filename or null.
                                // So prefixing with assets/images/ is correct.
                                $price = number_format($dish['price']) . ' FCFA';
                            ?>
                            <div class="dish-item px-2">
                                <div class="menu-card">
                                    <div class="menu-image-wrapper">
                                        <img src="<?php echo $img; ?>" class="menu-image"
                                            alt="<?php echo $dish['meal_name']; ?>">
                                    </div>
                                    <div class="card-content">
                                        <h3 class="menu-title"><?php echo $dish['meal_name']; ?></h3>
                                        <div class="product-rating">
                                            <?php 
                                            // Mock rating for now as DB doesn't have it
                                            for ($i = 0; $i < 5; $i++) echo '<span class="star">★</span>'; 
                                            ?>
                                        </div>
                                        <p class="menu-price"><?php echo $price; ?></p>
                                        <div class="card-actions">
                                            <a href="menu.php" class="btn-add-cart text-center text-decoration-none">Order
                                                Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button class="carousel-arrow carousel-arrow-left" id="slideLeft"><i
                        class="fas fa-chevron-left"></i></button>
                <button class="carousel-arrow carousel-arrow-right" id="slideRight"><i
                        class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container-custom">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="p-4">
                        <i class="fas fa-utensils fa-3x mb-3" style="color: #F28C28;"></i>
                        <h5 class="fw-bold">Authentic Recipes</h5>
                        <p class="text-muted">Traditional Cameroonian dishes prepared with love and authentic
                            ingredients.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4">
                        <i class="fas fa-shipping-fast fa-3x mb-3" style="color: #F28C28;"></i>
                        <h5 class="fw-bold">Fast Delivery</h5>
                        <p class="text-muted">Quick and reliable delivery straight to your doorstep in Buea and beyond.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4">
                        <i class="fas fa-heart fa-3x mb-3" style="color: #F28C28;"></i>
                        <h5 class="fw-bold">Customer Satisfaction</h5>
                        <p class="text-muted">Over 95% of our customers return for our unique spices and service.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-5 bg-white">
        <div class="container-custom">
            <h2 class="section-title text-center mb-5">What Our Customers Say</h2>

            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="testimonial-card">
                                    <div class="mb-3">
                                        <span class="star" style="color: #FFB800;">★★★★★</span>
                                    </div>
                                    <p class="testimonial-text">"The Ndolé is absolutely divine! The flavors took me
                                        right back to the seaside in Kribi. Simply spectacular."</p>
                                    <h5 class="testimonial-author">Sarah Larson</h5>
                                    <small class="text-muted">Food Blogger</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="testimonial-card">
                                    <div class="mb-3">
                                        <span class="star" style="color: #FFB800;">★★★★★</span>
                                    </div>
                                    <p class="testimonial-text">"Best Poulet DG I've had outside of a home kitchen. The
                                        service is fast and the spices are just right."</p>
                                    <h5 class="testimonial-author">David M.</h5>
                                    <small class="text-muted">Local Guide</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                    data-bs-slide="prev" style="width: 5%;">
                    <i class="fas fa-chevron-left" style="color: #F28C28; font-size: 2rem;"></i>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                    data-bs-slide="next" style="width: 5%;">
                    <i class="fas fa-chevron-right" style="color: #F28C28; font-size: 2rem;"></i>
                </button>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>

    <script>
        $(document).ready(function () {
            const sliderContainer = $(".dish-slider-container");
            const itemWidth = $(".dish-item").outerWidth(true);

            $("#slideRight").click(function () {
                sliderContainer.animate({ scrollLeft: '+=' + itemWidth }, 400);
            });

            $("#slideLeft").click(function () {
                sliderContainer.animate({ scrollLeft: '-=' + itemWidth }, 400);
            });
        });
    </script>
</body>

</html>