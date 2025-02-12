<?php
session_start();
include 'conn.php';

// Check if the superadmin is already logged in
if (!isset($_SESSION['superadmin_name'])) {
    header('Location: index.php');
    exit();
}

// Query to fetch all candidates
$candidates_query = "SELECT * FROM candidates";
$candidates_result = mysqli_query($con, $candidates_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate List</title>
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
        .candidate-image {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Candidate List</h2>
        <?php if (mysqli_num_rows($candidates_result) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Party</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($candidate = mysqli_fetch_assoc($candidates_result)) {
                        // Fetch the correct column names
                        $candidate_id = $candidate['can_id']; // Correct column for ID
                        $candidate_name = $candidate['can_name']; // Candidate's name
                        $candidate_party = $candidate['can_party_name']; // Party name
                        
                        // Display row for each candidate
                        echo "<tr>";
                        echo "<td>" . $candidate_id . "</td>";
                        echo "<td>" . $candidate_name . "</td>";
                        echo "<td>" . $candidate_party . "</td>";
                        
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                No candidates found. Please add candidates to the database.
            </div>
        <?php endif; ?>
        <a href="superadmin.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
