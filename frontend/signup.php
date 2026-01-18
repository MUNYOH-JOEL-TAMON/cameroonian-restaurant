<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Food Bistro</title>
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
        <div class="auth-card signup-wide fade-in">
                <div class="auth-header">
                    <div class="auth-icon mb-3">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h2>Create Account</h2>
                    <p>Join us to start ordering delicious Cameroonian meals</p>
                </div>

                <form id="signupForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control form-control-custom" id="firstName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control form-control-custom" id="lastName" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control form-control-custom" id="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control form-control-custom" id="phone" 
                            placeholder="+237" required>
                    </div>
                    
                    <div class="mb-3"> <!-- Added Address Field -->
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control form-control-custom" id="address" placeholder="City, neighborhood" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control form-control-custom" id="password" required>
                        <small class="form-text-muted">At least 8 characters</small>
                    </div>

                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control form-control-custom" id="confirmPassword"
                            required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="#" class="auth-link">Terms & Conditions</a>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3 btn-auth">Create Account</button>
                    
                    <div id="alertMessage" class="mt-3"></div>

                    <div class="text-center">
                        <p class="mb-0 auth-footer-text">Already have an account? <a href="signin.php"
                                class="auth-link">Sign In</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#signupForm').on('submit', function(e) {
            e.preventDefault();
            
            const firstName = $('#firstName').val();
            const lastName = $('#lastName').val();
            const full_name = firstName + ' ' + lastName; // Combine for backend
            
            const email = $('#email').val();
            const phone = $('#phone').val();
            const password = $('#password').val();
            const confirmPassword = $('#confirmPassword').val();
            const address = $('#address').val();
            
            const $btn = $('.btn-auth');
            const $alert = $('#alertMessage');
            
            $alert.html('');
            
            if (password !== confirmPassword) {
                $alert.html('<div class="alert alert-danger">Passwords do not match.</div>');
                return;
            }
            
            $btn.prop('disabled', true).text('Creating Account...');

            $.ajax({
                url: '../backend/api/register.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    full_name: full_name,
                    email: email,
                    phone: phone,
                    password: password,
                    address: address
                }),
                success: function(response) {
                    if(response.success) {
                        $alert.html('<div class="alert alert-success">Registration successful! Redirecting to login...</div>');
                        setTimeout(function() {
                            window.location.href = 'signin.php';
                        }, 2000);
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).text('Create Account');
                    let msg = 'Registration failed.';
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