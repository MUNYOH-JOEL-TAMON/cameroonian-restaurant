<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Food Bistro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
                <span class="fw-bold" style="font-family: 'Lora', serif;">Food <span style="color: #F28C28;">Bistro</span></span>
                <span class="badge bg-secondary ms-2">Admin</span>
            </a>
            <div>
                    <h2 class="m-0">Dashboard</h2>
                </div>
                <div>
                <a href="messages.php" class="btn btn-outline-primary btn-sm me-2">
                    <i class="fas fa-envelope"></i> Messages
                </a>
                <a href="../index.php" class="btn btn-outline-secondary btn-sm">View Site</a>
                <button id="admin-logout" class="btn btn-outline-danger btn-sm ms-2">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 px-lg-5 py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold mb-1">Manage Meals</h2>
                <p class="text-muted m-0">Overview of all dishes in the menu</p>
            </div>
            <a href="add_meal.php" class="btn text-white fw-bold px-4 py-2 shadow-sm" style="background-color: #F28C28; border: none; border-radius: 8px;">
                <span class="fs-5 align-middle me-1">+</span> Add New Meal
            </a>
        </div>

        <div id="meals-grid" class="row g-4">
            <!-- Dynamic Cards -->
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
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
                        const container = $('#meals-grid');
                        container.empty();
                        
                        // Sort by newest first
                        const meals = response.data.sort((a, b) => b.meal_id - a.meal_id);
                        
                        meals.forEach(meal => {
                            let imgUrl = meal.image_url ? '../assets/images/' + meal.image_url : '../assets/images/image2.jpg';
                            
                            container.append(`
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div class="card border-0 shadow-sm h-100 overflow-hidden">
                                        <div class="card-body p-0 d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="${imgUrl}" alt="${meal.meal_name}" style="width: 90px; height: 100%; min-height: 90px; object-fit: cover;">
                                            </div>
                                            <div class="p-2 flex-grow-1 d-flex flex-column justify-content-between">
                                                <div>
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <h6 class="fw-bold mb-1 text-dark text-truncate small" style="max-width: 100px;" title="${meal.meal_name}">${meal.meal_name}</h6>
                                                        <span class="text-primary fw-bold small" style="font-size: 0.8rem;">${parseFloat(meal.price).toLocaleString()}</span>
                                                    </div>
                                                    <span class="badge bg-light text-secondary border fw-normal" style="font-size: 0.7rem; padding: 2px 6px;">${meal.category}</span>
                                                </div>
                                                
                                                <div class="d-flex justify-content-end gap-1 mt-2">
                                                    <a href="edit_meal.php?id=${meal.meal_id}" class="btn btn-outline-primary p-0 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 10px;" title="Edit">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <button class="btn btn-outline-danger p-0 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 10px;" onclick="deleteMeal(${meal.meal_id})" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                    } else {
                        $('#meals-grid').html('<div class="col-12 text-center text-danger p-4">Failed to load data.</div>');
                    }
                },
                error: function() {
                    $('#meals-grid').html('<div class="col-12 text-center text-danger p-4">Error connecting to server.</div>');
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
