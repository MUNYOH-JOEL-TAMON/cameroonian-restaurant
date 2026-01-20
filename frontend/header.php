<header class="header">
    <div class="container-custom">
        <nav class="custom-navbar">
            <!-- Logo -->
            <a href="menu.php" class="custom-navbar-brand">
                <span class="logo-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke="#F28C28" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9 22V12h6v10" stroke="#F28C28" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </span>
                Food <span class="brand-highlight">Bistro</span>
            </a>

            <!-- Navigation Links -->
            <ul class="custom-navbar-nav">
                <?php
                $currentPage = basename($_SERVER['SCRIPT_NAME']);
                ?>
                <li><a href="index.php" class="custom-nav-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="menu.php" class="custom-nav-link <?php echo $currentPage == 'menu.php' ? 'active' : ''; ?>">Menu</a></li>
                <li><a href="#" class="custom-nav-link">Specials</a></li>
                <li><a href="contact.php" class="custom-nav-link <?php echo $currentPage == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
                <li class="mobile-signin"><a href="signin.php" class="custom-nav-link">Sign in</a></li>
                <li class="mobile-signin"><a href="signup.php" class="btn-signin">Sign up</a></li>
            </ul>

            <!-- Right Actions -->
            <div class="header-actions">

                <button class="btn-icon-header">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4h2l2 10h12l1.5-6h-13.5" stroke="#F28C28" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <circle cx="9" cy="19" r="1.5" fill="#F28C28" />
                        <circle cx="17" cy="19" r="1.5" fill="#F28C28" />
                    </svg>
                    <span class="cart-badge"></span>
                </button>
                <!-- Auth Buttons -->
                <div id="auth-buttons" class="d-none d-lg-flex align-items-center gap-3">
                    <a href="signin.php" class="custom-nav-link">Sign in</a>
                    <a href="signup.php" class="btn-signin text-decoration-none">Sign up</a>
                </div>

                <!-- User Profile (Hidden by default) -->
                <div id="user-profile" class="d-none align-items-center gap-3">
                    <span class="fw-bold text-dark" id="user-name"></span>
                    <button id="btn-logout" class="btn-icon-header" title="Logout">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="#F28C28" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 17l5-5-5-5" stroke="#F28C28" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21 12H9" stroke="#F28C28" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>

                <!-- Hamburger Menu Button -->
                <button class="hamburger-menu">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 12h18M3 6h18M3 18h18" stroke="#1C1A17" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </nav>
    </div>
</header>