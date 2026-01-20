<?php
include_once '../../backend/utils/session.php';
if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../signin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Meal - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

    <div class="container-custom py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10"> <!-- Wider container -->
                <div class="d-flex align-items-center mb-4">
                    <a href="index.php" class="text-decoration-none me-3" style="color: #F28C28;">&larr; Back</a>
                    <h2 class="m-0">Add New Meal</h2>
                </div>

                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form id="add-meal-form">
                            <div class="row g-5"> <!-- Grid with gap -->
                                
                                <!-- Left Column: Image Upload -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Meal Image</label>
                                        <div class="image-upload-area p-4 text-center border rounded-3 bg-light" style="border-style: dashed !important; border-color: #dee2e6;">
                                            <div class="mb-3">
                                                 <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-secondary">
                                                    <path d="M12 16v-8m0 0l-3 3m3-3l3 3m6 13H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h3l2-3h6l2 3h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                            <input type="file" class="form-control" name="image_file" accept="image/*" id="imageInput">
                                            <div class="form-text mt-2">Supported: JPG, PNG, WEBP.</div>
                                        </div>
                                        <!-- Preview -->
                                        <div class="mt-3 text-center" id="preview-container" style="display: none;">
                                            <img id="image-preview" src="#" alt="Preview" class="img-fluid rounded shadow-sm" style="max-height: 200px; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Form Details -->
                                <div class="col-md-8">
                                    <h5 class="mb-4 text-muted">Meal Details</h5>
                                    
                                    <div class="row g-3"> <!-- Inner Grid -->
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Meal Name</label>
                                            <input type="text" class="form-control form-control-lg" name="meal_name" required placeholder="e.g. Ndole with Plantains">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Price (FCFA)</label>
                                            <input type="number" class="form-control" name="price" required placeholder="2500">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Category</label>
                                            <select class="form-select" name="category" required>
                                                <option value="">Select Category</option>
                                                <option value="Main Course">Main Course</option>
                                                <option value="Appetizer">Appetizer</option>
                                                <option value="Dessert">Dessert</option>
                                                <option value="Drinks">Drinks</option>
                                                <option value="Side Dish">Side Dish</option>
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-bold">Description</label>
                                            <textarea class="form-control" name="description" rows="4" placeholder="Describe the flavors, ingredients, and portion size..."></textarea>
                                        </div>
                                        
                                        <div class="col-12 mt-4">
                                             <button type="submit" class="btn text-white fw-bold py-3 w-100" style="background-color: #F28C28;">Add Meal to Menu</button>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
             // Auth Check
             if (!localStorage.getItem('user')) {
                window.location.href = '../signin.php';
            }

            // Image Preview
            $('#imageInput').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview').attr('src', e.target.result);
                        $('#preview-container').fadeIn();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#preview-container').hide();
                }
            });

            $('#add-meal-form').on('submit', function(e) {
                e.preventDefault();
                
                // Use FormData for file upload
                const formData = new FormData(this);

                $.ajax({
                    url: '../../backend/admin/add_meal.php',
                    method: 'POST',
                    data: formData,
                    processData: false, // Important for FormData
                    contentType: false, // Important for FormData
                    success: function(response) {
                        try {
                            // If response is already JSON, use it, otherwise parse
                            const res = typeof response === 'object' ? response : JSON.parse(response);
                            if (res.success) {
                                alert('Meal added successfully!');
                                window.location.href = 'index.php';
                            } else {
                                alert(res.message || 'Failed to add meal.');
                            }
                        } catch(e) {
                             console.error("Parsing error", e);
                             alert("Unexpected response from server.");
                        }
                    },
                    error: function(xhr) {
                        alert('Error connecting to server: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText));
                    }
                });
            });
        });
    </script>
</body>
</html>
