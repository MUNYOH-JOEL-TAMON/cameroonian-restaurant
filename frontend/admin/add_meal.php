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
            <div class="col-md-8 col-lg-6">
                <div class="d-flex align-items-center mb-4">
                    <a href="index.php" class="text-decoration-none me-3" style="color: #F28C28;">&larr; Back</a>
                    <h2 class="m-0">Add New Meal</h2>
                </div>

                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form id="add-meal-form">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Meal Name</label>
                                <input type="text" class="form-control" name="meal_name" required placeholder="e.g. Ndole">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Price (FCFA)</label>
                                    <input type="number" class="form-control" name="price" required placeholder="e.g. 2500">
                                </div>
                                <div class="col-md-6 mb-3">
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
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Brief description of the dish..."></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Image Filename</label>
                                <input type="text" class="form-control" name="image_url" placeholder="e.g. ndole.jpg (must exist in assets/images/)">
                                <div class="form-text">Currently, please manually upload images to <code>frontend/assets/images/</code> and enter filename here.</div>
                            </div>

                            <button type="submit" class="btn w-100 text-white fw-bold py-3" style="background-color: #F28C28;">Add Meal</button>
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

            $('#add-meal-form').on('submit', function(e) {
                e.preventDefault();
                
                // Convert form data to JSON object
                const formData = {};
                $(this).serializeArray().forEach(item => {
                    formData[item.name] = item.value;
                });

                $.ajax({
                    url: '../../backend/admin/add_meal.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(formData),
                    success: function(response) {
                        if (response.success) {
                            alert('Meal added successfully!');
                            window.location.href = 'index.php';
                        } else {
                            alert(response.message || 'Failed to add meal.');
                        }
                    },
                    error: function() {
                        alert('Error connecting to server.');
                    }
                });
            });
        });
    </script>
</body>
</html>
