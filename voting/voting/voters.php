<?php
session_start();
include 'conn.php';

// Check if the superadmin is already logged in
if (!isset($_SESSION['superadmin_name'])) {
    header('Location: index.php');
    exit();
}

// Query to fetch all voters
$voters_query = "SELECT * FROM voters";
$voters_result = mysqli_query($con, $voters_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter List</title>
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
        <h2>Voter List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Voter ID</th>
                    <th>Name</th>
                    <th>Age</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($voter = mysqli_fetch_assoc($voters_result)) {
                    echo "<tr>";
                    // Using correct column names from your table structure
                    echo "<td>" . $voter['voters_id'] . "</td>"; // Correct column name: 'voters_id'
                    echo "<td>" . $voter['name'] . "</td>"; // Correct column name: 'name'
                    echo "<td>" . $voter['age'] . "</td>"; // Correct column name: 'age'
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="superadmin.php" class="btn btn-primary">Back to Dashboard</a> <!-- Changed to btn-primary -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
