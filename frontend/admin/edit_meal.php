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
    <title>Edit Meal - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

    <div class="container-custom py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex align-items-center mb-4">
                    <a href="index.php" class="text-decoration-none me-3" style="color: #F28C28;">&larr; Back</a>
                    <h2 class="m-0">Edit Meal</h2>
                </div>

                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <div id="loading" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <form id="edit-meal-form" style="display: none;">
                            <input type="hidden" name="meal_id" id="meal_id">
                            <input type="hidden" name="existing_image_url" id="existing_image_url">

                            <div class="row g-5">
                                <!-- Left Column: Image -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Meal Image</label>
                                        
                                        <!-- Current Image Preview -->
                                        <div class="mb-3 text-center">
                                            <img id="current-image-preview" src="#" alt="Current Image" class="img-fluid rounded shadow-sm border" style="max-height: 200px; object-fit: cover; width: 100%;">
                                        </div>

                                        <div class="image-upload-area p-3 text-center border rounded-3 bg-light" style="border-style: dashed !important; border-color: #dee2e6;">
                                            <label class="form-label small text-muted mb-2">Change Image</label>
                                            <input type="file" class="form-control form-control-sm" name="image_file" accept="image/*" id="imageInput">
                                            <div class="form-text mt-1" style="font-size: 0.75rem;">Supported: JPG, PNG, WEBP.</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Details -->
                                <div class="col-md-8">
                                    <h5 class="mb-4 text-muted">Meal Information</h5>
                                    
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Meal Name</label>
                                            <input type="text" class="form-control form-control-lg" name="meal_name" id="meal_name" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Price (FCFA)</label>
                                            <input type="number" class="form-control" name="price" id="price" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Category</label>
                                            <select class="form-select" name="category" id="category" required>
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
                                            <textarea class="form-control" name="description" id="description" rows="4"></textarea>
                                        </div>

                                        <div class="col-12 mt-4">
                                            <button type="submit" class="btn text-white fw-bold py-3 w-100" style="background-color: #F28C28;">Update Meal</button>
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

            // Get ID from URL
            const urlParams = new URLSearchParams(window.location.search);
            const mealId = urlParams.get('id');

            if (!mealId) {
                alert("No meal ID specified.");
                window.location.href = 'index.php';
                return;
            }

            // Fetch Meal Data
            $.ajax({
                url: '../../backend/api/get_meals.php',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const meal = response.data.find(m => m.meal_id == mealId);
                        if (meal) {
                            $('#meal_id').val(meal.meal_id);
                            $('#meal_name').val(meal.meal_name);
                            $('#price').val(meal.price);
                            $('#category').val(meal.category);
                            $('#description').val(meal.description);
                            $('#existing_image_url').val(meal.image_url);
                            
                            // Handle image preview
                            let imgUrl = meal.image_url ? '../assets/images/' + meal.image_url : '../assets/images/image2.jpg';
                            $('#current-image-preview').attr('src', imgUrl);

                            $('#loading').hide();
                            $('#edit-meal-form').fadeIn();
                        } else {
                            alert("Meal not found.");
                            window.location.href = 'index.php';
                        }
                    }
                },
                error: function() {
                    alert("Error fetching data.");
                }
            });

            // Image Preview on file select
            $('#imageInput').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#current-image-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            $('#edit-meal-form').on('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);

                $.ajax({
                    url: '../../backend/admin/update_meal.php',
                    method: 'POST',
                    data: formData,
                    processData: false, 
                    contentType: false, 
                    success: function(response) {
                        try {
                            const res = typeof response === 'object' ? response : JSON.parse(response);
                            if (res.success) {
                                alert('Meal updated successfully!');
                                window.location.href = 'index.php';
                            } else {
                                alert(res.message || 'Failed to update meal.');
                            }
                        } catch(e) {
                             console.error("Parsing error", e);
                             alert("Unexpected response from server.");
                        }
                    },
                    error: function(xhr) {
                        alert('Error connecting to server.');
                    }
                });
            });
        });
    </script>
</body>
</html>
