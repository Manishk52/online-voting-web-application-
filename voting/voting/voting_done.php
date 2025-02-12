<?php
session_start();
include 'conn.php';

if (empty($_SESSION['voter_name'])) {
    header("Location: index.php");
    exit();
}

$voter_id = $_SESSION['voter_id'];

// Debugging: check if the session is set correctly
// echo "Voter ID: " . htmlspecialchars($voter_id);

$check_vote = "SELECT * FROM votes WHERE voter_id='$voter_id'";
$check_vote_result = mysqli_query($con, $check_vote);

if ($check_vote_result->num_rows > 0) {
    // If the user has voted, fetch their vote details
    $vote_details = mysqli_fetch_assoc($check_vote_result);
    $candidate_id = $vote_details['can_id'];

    // Debugging: check if candidate_id is fetched correctly
    // echo "Candidate ID: " . htmlspecialchars($candidate_id);

    // Now fetch the candidate's details from the candidates table
    $candidate_query = "SELECT * FROM candidates WHERE can_id='$candidate_id'";
    $candidate_result = mysqli_query($con, $candidate_query);

    if ($candidate_result->num_rows > 0) {
        $candidate_details = mysqli_fetch_assoc($candidate_result);
        $real_can_id = $candidate_details['can_id'];
        $real_can_name = $candidate_details['can_name'];
        $real_can_image = $candidate_details['can_image'];
        $real_can_party_symbol = $candidate_details['can_party_symbol'];
    } else {
        $real_can_id = "N/A";
        $real_can_name = "No candidate found";
        $real_can_image = "No image available";
        $real_can_party_symbol = "No symbol available";
    }
} else {
    // If no vote is found, handle the case where the user hasn't voted yet
    $real_can_id = "N/A";
    $real_can_name = "No candidate selected";
    $real_can_image = "No image available";
    $real_can_party_symbol = "No symbol available";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Completed</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="text-center p-4 border rounded shadow bg-white" style="max-width: 600px; width: 100%;">
        <h2 class="mb-3">Voting Done!</h2>
        <p class="lead">Your vote has been successfully recorded.</p>

        <h4 class="mt-4"><u><b>Details of Your Vote:</b></u></h4>
        <p><strong>Candidate ID:</strong> <?php echo htmlspecialchars($real_can_id); ?></p>
        <p><strong>Candidate Name:</strong> <?php echo htmlspecialchars($real_can_name); ?></p>

        <!-- <?php if ($real_can_image != "No image available") { ?>
            <p><strong>Candidate Image:</strong><br>
                <img src="profile/<?php echo htmlspecialchars($real_can_image); ?>" alt="Candidate Image" width="100px">
            </p>
        <?php } ?>

        <?php if ($real_can_party_symbol != "No symbol available") { ?>
            <p><strong>Party Symbol:</strong><br>
                <img src="profile/<?php echo htmlspecialchars($real_can_party_symbol); ?>" alt="Party Symbol" width="100px">
            </p>
        <?php } ?> -->

        <a href="voting_page.php" class="btn btn-primary mb-2">Go back to voting page</a><br>
        <!-- Link to view the result -->
        <a href="result.php" class="btn btn-success">View Results</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</body>
</html>
