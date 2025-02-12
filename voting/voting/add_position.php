<?php
session_start();
include 'conn.php';

// Check if the superadmin is already logged in
if (!isset($_SESSION['superadmin_name'])) {
    header('Location: index.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_position'])) {
    // Get form data
    $can_id = mysqli_real_escape_string($con, $_POST['can_id']);
    $position_name = mysqli_real_escape_string($con, $_POST['position_name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);

    // Check if the candidate exists using the correct column name 'can_id'
    $candidate_check_query = "SELECT * FROM candidates WHERE can_id = '$can_id'";
    $candidate_result = mysqli_query($con, $candidate_check_query);

    if (mysqli_num_rows($candidate_result) > 0) {
        // Check if the candidate already has this position
        $position_check_query = "SELECT * FROM positions WHERE can_id = '$can_id' AND position_name = '$position_name'";
        $position_result = mysqli_query($con, $position_check_query);

        if (mysqli_num_rows($position_result) > 0) {
            // Candidate already has this position
            echo "<script>alert('Error: This candidate is already contesting for this position.');</script>";
        } else {
            // Insert the position
            $insert_position_query = "INSERT INTO positions (can_id, position_name, description) VALUES ('$can_id', '$position_name', '$description')";
            
            // Try to execute the insert query
            try {
                if (mysqli_query($con, $insert_position_query)) {
                    echo "<script>alert('Position added successfully!'); window.location.href='superadmin.php';</script>";
                }
            } catch (mysqli_sql_exception $e) {
                echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Error: Candidate ID does not exist.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Position</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #343a40;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 3rem auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Position</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="can_id" class="form-label">Select Candidate</label>
                <select class="form-select" id="can_id" name="can_id" required>
                    <option value="">Select a candidate</option>
                    <?php
                    // Query to fetch all candidates
                    $candidates_query = "SELECT * FROM candidates";
                    $candidates_result = mysqli_query($con, $candidates_query);
                    while ($candidate = mysqli_fetch_assoc($candidates_result)) {
                        echo "<option value='" . $candidate['can_id'] . "'>" . $candidate['can_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="position_name" class="form-label">Position Name</label>
                <input type="text" class="form-control" id="position_name" name="position_name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <button type="submit" name="add_position" class="btn btn-primary">Add Position</button>
            <a href="superadmin.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
