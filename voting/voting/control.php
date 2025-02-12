<?php
// Include the database connection file
include('conn.php');

// Add Candidate
if (isset($_POST['add_candidate'])) {
    $can_id = mysqli_real_escape_string($con, $_POST['can_id']);
    $can_name = mysqli_real_escape_string($con, $_POST['can_name']);
    $can_party_name = mysqli_real_escape_string($con, $_POST['can_party_name']);

    // Handle file uploads
    $can_image = $_FILES['can_image']['name'];
    $can_party_symbol = $_FILES['can_party']['name'];
    $target_dir = "profile/";

    // Set the file paths
    $target_image_file = $target_dir . basename($can_image);
    $target_symbol_file = $target_dir . basename($can_party_symbol);

    // Move the uploaded files to the target directory
    move_uploaded_file($_FILES['can_image']['tmp_name'], $target_image_file);
    move_uploaded_file($_FILES['can_party']['tmp_name'], $target_symbol_file);

    // Insert the candidate data into the database
    $insert_candidate = "INSERT INTO candidates (can_id, can_name, can_party_name, can_image, can_party_symbol)
                         VALUES ('$can_id', '$can_name', '$can_party_name', '$can_image', '$can_party_symbol')";

    if (mysqli_query($con, $insert_candidate)) {
        echo "<script>alert('Candidate added successfully!');</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Edit Candidate
if (isset($_POST['edit_candidate'])) {
    $can_id = mysqli_real_escape_string($con, $_POST['can_id']);
    
    // Get the current values to retain if no new values are provided
    $existing_can_name = $_POST['existing_can_name'];
    $existing_can_party_name = $_POST['existing_can_party_name'];
    $existing_can_image = $_POST['existing_can_image'];
    $existing_can_party_symbol = $_POST['existing_can_party_symbol'];

    // Update only if a new value is provided
    $can_name = !empty($_POST['can_name']) ? mysqli_real_escape_string($con, $_POST['can_name']) : $existing_can_name;
    $can_party_name = !empty($_POST['can_party_name']) ? mysqli_real_escape_string($con, $_POST['can_party_name']) : $existing_can_party_name;

    // Handle file uploads for image and party symbol only if new files are uploaded
    $can_image = $_FILES['can_image']['name'] ? $_FILES['can_image']['name'] : $existing_can_image;
    $can_party_symbol = $_FILES['can_party']['name'] ? $_FILES['can_party']['name'] : $existing_can_party_symbol;

    // Set the target directory for file uploads
    $target_dir = "profile/";

    // If new images are uploaded, move them to the target directory
    if ($_FILES['can_image']['name']) {
        move_uploaded_file($_FILES['can_image']['tmp_name'], $target_dir . basename($can_image));
    }

    if ($_FILES['can_party']['name']) {
        move_uploaded_file($_FILES['can_party']['tmp_name'], $target_dir . basename($can_party_symbol));
    }

    // Build the SQL query dynamically, only updating the fields that have been modified
    $update_candidate = "UPDATE candidates SET ";

    // Add the updated values to the query if they are different from the existing values
    $update_fields = [];

    if ($can_name !== $existing_can_name) {
        $update_fields[] = "can_name = '$can_name'";
    }

    if ($can_party_name !== $existing_can_party_name) {
        $update_fields[] = "can_party_name = '$can_party_name'";
    }

    if ($can_image !== $existing_can_image) {
        $update_fields[] = "can_image = '$can_image'";
    }

    if ($can_party_symbol !== $existing_can_party_symbol) {
        $update_fields[] = "can_party_symbol = '$can_party_symbol'";
    }

    // If no fields are updated, we don’t execute the update
    if (count($update_fields) > 0) {
        $update_candidate .= implode(", ", $update_fields);
        $update_candidate .= " WHERE can_id = '$can_id'";

        // Execute the update query
        if (mysqli_query($con, $update_candidate)) {
            echo "<script>alert('Candidate updated successfully!');</script>";
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "<script>alert('No changes were made to the candidate details.');</script>";
    }
}

// Delete Candidate
if (isset($_POST['delete_candidate'])) {
    $can_id = $_POST['can_id']; // Candidate ID to delete

    // Delete candidate from database
    $delete_candidate = "DELETE FROM candidates WHERE can_id = '$can_id'";

    if (mysqli_query($con, $delete_candidate)) {
        echo "<script>alert('Candidate deleted successfully!');</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVoting System - Candidate Management</title>
    <!-- Bootstrap and FontAwesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* Custom CSS */
        body {
            background-image: url('images/background.jpg'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff;
        }

        .card-body {
            padding: 2rem;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .candidate-image {
            width: 50px;
            height: auto;
        }

        .navbar {
            background: rgba(0, 0, 0, 0.6);
        }

        .table th {
            background-color: rgba(0, 0, 0, 0.7);
        }

        .table tr:hover {
            background-color: rgba(255, 255, 255, 0.7);
        }

        .modal-content {
            background: rgba(18, 18, 18, 0.8);
            border-radius: 10px;
        }

        /* Custom CSS for Candidate Management heading */
        .card-body h2 {
            font-family: 'Arial', sans-serif;
            color: #ff5733;
            font-weight: bold;
            font-size: 2rem;
            text-align: center;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">EVoting System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="superadmin.php"><i class="fa fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#addcandidatemodal"><i class="fa fa-user-plus"></i> Add Candidate</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="alogout.php"><i class="fa fa-sign-out"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Add Candidate Modal -->
    <div class="modal fade" id="addcandidatemodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-user-plus"></i> Add New Candidate</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label><i class="fa fa-id-card"></i> Candidate ID:</label>
                            <input type="text" name="can_id" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-user"></i> Candidate Name:</label>
                            <input type="text" name="can_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-university"></i> Party Name:</label>
                            <input type="text" name="can_party_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-flag"></i> Party Symbol:</label>
                            <input type="file" name="can_party" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-image"></i> Candidate Photo:</label>
                            <input type="file" name="can_image" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        <button type="submit" name="add_candidate" class="btn btn-primary"><i class="fa fa-plus"></i> Add Candidate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Candidate Modal -->
    <div class="modal fade" id="editcandidatemodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-edit"></i> Edit Candidate</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="can_id" id="edit_can_id">
                        <div class="form-group">
                            <label><i class="fa fa-user"></i> Candidate Name:</label>
                            <input type="text" name="can_name" id="edit_can_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-university"></i> Party Name:</label>
                            <input type="text" name="can_party_name" id="edit_can_party_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-flag"></i> Party Symbol:</label>
                            <input type="file" name="can_party" class="form-control">
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-image"></i> Candidate Photo:</label>
                            <input type="file" name="can_image" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        <button type="submit" name="edit_candidate" class="btn btn-info"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

  <!-- Delete Candidate Modal -->
    <div class="modal fade" id="deletecandidatemodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-trash"></i> Delete Candidate</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="can_id" id="delete_can_id">
                        <p>Are you sure you want to delete this candidate?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                        <button type="submit" name="delete_candidate" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Candidate Table -->
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2><i class="fa fa-users"></i> Manage Candidates</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Candidate ID</th>
                                <th>Name</th>
                                <th>Party</th>
                                <th>Photo</th>
                                <th>Party Symbol</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch candidates from the database
                            $result = mysqli_query($con, "SELECT * FROM candidates");
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <td>{$row['can_id']}</td>
                                    <td>{$row['can_name']}</td>
                                    <td>{$row['can_party_name']}</td>
                                    <td><img src='profile/{$row['can_image']}' class='candidate-image' alt='Candidate Image'></td>
                                    <td><img src='profile/{$row['can_party_symbol']}' class='candidate-image' alt='Party Symbol'></td>
                                    <td>
                                        <button class='btn btn-info btn-sm' data-toggle='modal' data-target='#editcandidatemodal' 
                                                onclick='editCandidate(\"{$row['can_id']}\", \"{$row['can_name']}\", \"{$row['can_party_name']}\", \"{$row['can_image']}\", \"{$row['can_party_symbol']}\")'>
                                            <i class='fa fa-edit'></i> Edit
                                        </button>
                                        <button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deletecandidatemodal' 
                                                onclick='deleteCandidate(\"{$row['can_id']}\")'>
                                            <i class='fa fa-trash'></i> Delete
                                        </button>
                                    </td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // JavaScript for handling Edit and Delete Candidate modals
        function editCandidate(can_id, can_name, can_party_name, can_image, can_party_symbol) {
            document.getElementById('edit_can_id').value = can_id;
            document.getElementById('edit_can_name').value = can_name;
            document.getElementById('edit_can_party_name').value = can_party_name;
        }

        function deleteCandidate(can_id) {
            document.getElementById('delete_can_id').value = can_id;
        }
    </script>

</body>
</html>
