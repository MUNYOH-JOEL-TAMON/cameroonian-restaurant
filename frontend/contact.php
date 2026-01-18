<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Cameroonian Bistro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/contact.css">
</head>

<body>

    <?php include 'header.php'; ?>

    <main class="contact-wrapper py-5">
        <div class="container container-custom">
            <div class="contact-header text-center mb-5">
                <h1>Get In Touch</h1>
                <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
            </div>

            <div class="row mb-5">
                <div class="col-md-4 mb-4">
                    <div class="info-box">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h5>Visit Us</h5>
                        <p>123 Culinary Avenue<br>Douala, Cameroon</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="info-box">
                        <div class="info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h5>Call Us</h5>
                        <p>+237 6XX XXX XXX<br>Mon-Sun: 10AM - 10PM</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="info-box">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h5>Email Us</h5>
                        <p>info@cameroonianbistro.com<br>support@cameroonianbistro.com</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="contact-card">
                        <h3 class="mb-4 text-center">Send Us a Message</h3>
                        <form id="contact-form">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control chk-input" id="name" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control chk-input" id="email" name="email" required>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control chk-input" id="phone" name="phone">
                                </div>
                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <select class="form-select chk-select" id="subject" name="subject" required>
                                        <option value="">Choose a subject...</option>
                                        <option value="general">General Inquiry</option>
                                        <option value="reservation">Reservation</option>
                                        <option value="catering">Catering Services</option>
                                        <option value="feedback">Feedback</option>
                                        <option value="complaint">Complaint</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control chk-input" id="message" name="message" rows="6" required
                                    placeholder="Tell us how we can help you..."></textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-place-order w-auto px-5">
                                    <i class="fas fa-paper-plane me-2"></i> Send Message
                                </button>
                            </div>
                        </form>

                        <div class="success-message text-center" id="success-message" style="display: none;">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5>Thank You!</h5>
                            <p>Your message has been sent successfully. We'll get back to you soon.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        $(document).ready(function () {
            $('#contact-form').on('submit', function (e) {
                e.preventDefault();

                // Show success message
                $('#contact-form').fadeOut(300, function () {
                    $('#success-message').fadeIn(300);
                });

                // Reset form after 3 seconds
                setTimeout(function () {
                    $('#success-message').fadeOut(300, function () {
                        $('#contact-form')[0].reset();
                        $('#contact-form').fadeIn(300);
                    });
                }, 3000);
            });
        });
    </script>
</body>

</html>