<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

    <div class="container-custom py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <a href="index.php" class="text-decoration-none me-3" style="color: #F28C28;">&larr; Dashboard</a>
                <h2 class="m-0">User Messages</h2>
            </div>
            <div>
                <button class="btn btn-outline-secondary btn-sm" onclick="fetchMessages()">
                    Refresh
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Date</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody id="messages-table-body">
                            <!-- Messages will be loaded here -->
                        </tbody>
                    </table>
                </div>
                <div id="loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="no-messages" class="text-center py-5 d-none">
                    <p class="text-muted mb-0">No messages found.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Details Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSubject">Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <small class="text-muted">From:</small>
                        <div class="fw-bold" id="modalFrom"></div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Date:</small>
                        <div id="modalDate"></div>
                    </div>
                    <hr>
                    <div class="mt-3">
                        <div id="modalBody" style="white-space: pre-wrap;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary" onclick="replyToEmail()">Reply</button> -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
             // Auth Check
             if (!localStorage.getItem('user')) {
                window.location.href = '../signin.php';
            }

            fetchMessages();
        });

        function fetchMessages() {
            $('#loading').show();
            $('#messages-table-body').empty();
            $('#no-messages').addClass('d-none');

            $.ajax({
                url: '../../backend/admin/get_messages.php', // Adjusted path
                method: 'GET',
                success: function(response) {
                    $('#loading').hide();
                    
                    let data = response;
                    // Handle if response is string or object
                    if (typeof response === 'string') {
                        try { data = JSON.parse(response); } catch(e) {}
                    }

                    if (data.records && data.records.length > 0) {
                        data.records.forEach(msg => {
                            const date = new Date(msg.created_at).toLocaleDateString() + ' ' + new Date(msg.created_at).toLocaleTimeString();
                            const row = `
                                <tr>
                                    <td class="ps-4 text-muted small">${date}</td>
                                    <td class="fw-bold">${msg.name}</td>
                                    <td><a href="mailto:${msg.email}" class="text-decoration-none">${msg.email}</a></td>
                                    <td>${msg.subject}</td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-primary" 
                                            onclick='viewMessage(${JSON.stringify(msg).replace(/'/g, "&#39;")})'>
                                            View
                                        </button>
                                    </td>
                                </tr>
                            `;
                            $('#messages-table-body').append(row);
                        });
                    } else {
                        $('#no-messages').removeClass('d-none');
                    }
                },
                error: function() {
                    $('#loading').hide();
                    alert('Error loading messages.');
                }
            });
        }

        function viewMessage(msg) {
            $('#modalSubject').text(msg.subject);
            $('#modalFrom').html(`${msg.name} <span class="text-muted fw-normal">&lt;${msg.email}&gt;</span>`);
            $('#modalDate').text(new Date(msg.created_at).toLocaleString());
            $('#modalBody').text(msg.message);
            
            const modal = new bootstrap.Modal(document.getElementById('messageModal'));
            modal.show();
        }
    </script>
</body>
</html>
