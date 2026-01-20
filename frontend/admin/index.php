<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Dr T & Dr M</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .admin-header {
            background-color: #FAF7F3;
            padding: 20px 0;
            border-bottom: 1px solid #E0E0E0;
            margin-bottom: 30px;
        }
        .admin-table th {
            background-color: #F28C28;
            color: white;
            font-weight: 600;
        }
        .action-btn {
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            display: inline-block;
        }
        .btn-edit { background-color: #FFB800; }
        .btn-delete { background-color: #DC3545; }
        .btn-edit:hover, .btn-delete:hover { color: white; opacity: 0.9; }
    </style>
</head>
<body>

    <!-- Admin Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-custom w-100 d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center gap-2" href="../index.php">
                <span class="fw-bold" style="font-family: 'Lora', serif;">Dr T & <span style="color: #F28C28;">Dr M</span></span>
                <span class="badge bg-secondary ms-2">Admin</span>
            </a>
            <div>
                <a href="../index.php" class="btn btn-outline-secondary btn-sm">View Site</a>
                <button id="admin-logout" class="btn btn-outline-danger btn-sm ms-2">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container-custom py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Manage Meals</h1>
            <a href="add_meal.php" class="btn btn-primary" style="background-color: #F28C28; border: none;">+ Add New Meal</a>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table admin-table mb-0 table-hover">
                        <thead>
                            <tr>
                                <th class="p-3">Image</th>
                                <th class="p-3">Name</th>
                                <th class="p-3">Category</th>
                                <th class="p-3">Price</th>
                                <th class="p-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="meals-table-body">
                            <!-- Dynamic Content -->
                            <tr><td colspan="5" class="text-center p-4">Loading meals...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Check Auth (Simple local storage check for now, real app should verify session)
            const user = JSON.parse(localStorage.getItem('user'));
            if (!user) {
                alert("Please login first.");
                window.location.href = '../signin.php';
            }

            // Fetch Meals
            loadMeals();

            $('#admin-logout').click(function() {
                localStorage.removeItem('user');
                window.location.href = '../signin.php';
            });
        });

        function loadMeals() {
            $.ajax({
                url: '../../backend/api/get_meals.php',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const tbody = $('#meals-table-body');
                        tbody.empty();
                        
                        // Sort by newest first (assuming higher ID is newer)
                        const meals = response.data.sort((a, b) => b.meal_id - a.meal_id);
                        
                        meals.forEach(meal => {
                            let imgUrl = meal.image_url ? '../assets/images/' + meal.image_url : '../assets/images/image2.jpg';
                            
                            tbody.append(`
                                <tr>
                                    <td class="p-3">
                                        <img src="${imgUrl}" alt="${meal.meal_name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                    </td>
                                    <td class="p-3 fw-bold align-middle">${meal.meal_name}</td>
                                    <td class="p-3 align-middle"><span class="badge bg-light text-dark border">${meal.category}</span></td>
                                    <td class="p-3 align-middle">${parseFloat(meal.price).toLocaleString()} FCFA</td>
                                    <td class="p-3 text-end align-middle">
                                        <a href="edit_meal.php?id=${meal.meal_id}" class="action-btn btn-edit me-1">Edit</a>
                                        <button class="btn btn-sm btn-delete text-white" onclick="deleteMeal(${meal.meal_id})">Delete</button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        $('#meals-table-body').html('<tr><td colspan="5" class="text-center text-danger p-4">Failed to load data.</td></tr>');
                    }
                },
                error: function() {
                    $('#meals-table-body').html('<tr><td colspan="5" class="text-center text-danger p-4">Error connecting to server.</td></tr>');
                }
            });
        }

        function deleteMeal(id) {
            if(confirm('Are you sure you want to delete this meal?')) {
                $.ajax({
                    url: '../../backend/admin/delete_meal.php',
                    method: 'POST',
                    data: JSON.stringify({ meal_id: id }),
                    contentType: 'application/json',
                    success: function(response) {
                        if(response.success) {
                            loadMeals(); // Reload table
                        } else {
                            alert(response.message || 'Failed to delete');
                        }
                    },
                    error: function() {
                        alert('Error deleting meal');
                    }
                });
            }
        }
    </script>
</body>
</html>
