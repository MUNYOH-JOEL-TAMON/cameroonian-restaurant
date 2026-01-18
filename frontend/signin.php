<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Food Bistro</title>
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
    <link rel="stylesheet" href="assets/css/auth.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="auth-container">
        <div class="auth-card fade-in">
                <div class="auth-header">
                    <div class="auth-icon mb-3">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2>Welcome Back</h2>
                    <p>Sign in to your account to continue</p>
                </div>

                <form id="signinForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control form-control-custom" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control form-control-custom" id="password" name="password"
                            required>
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        <a href="#" class="auth-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3 btn-auth">Sign In</button>
                    
                    <div id="alertMessage" class="mt-3"></div>

                    <div class="text-center">
                        <p class="mb-0 auth-footer-text">Don't have an account? <a href="signup.php"
                                class="auth-link">Sign
                                Up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#signinForm').on('submit', function(e) {
            e.preventDefault();
            
            const email = $('#email').val();
            const password = $('#password').val();
            const $btn = $('.btn-auth');
            const $alert = $('#alertMessage');
            
            // Disable button
            $btn.prop('disabled', true).text('Signing in...');
            $alert.html('');

            $.ajax({
                url: '../backend/api/login.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    email: email,
                    password: password
                }),
                success: function(response) {
                    if(response.success) {
                        $alert.html('<div class="alert alert-success">Login successful! Redirecting...</div>');
                        // Store user info if needed
                        localStorage.setItem('user', JSON.stringify(response.data));
                        setTimeout(function() {
                            window.location.href = 'index.php';
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).text('Sign In');
                    let msg = 'Login failed.';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $alert.html('<div class="alert alert-danger">' + msg + '</div>');
                }
            });
        });
    });
    </script>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>