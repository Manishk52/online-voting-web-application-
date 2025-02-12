<?php
session_start();
include 'conn.php';

// Check if user is logged in
if (empty($_SESSION['voter_name'])) {
    header("Location:index.php");
    exit();
}

// Fetch all candidates from the database
$candidate_query = "SELECT * FROM candidates";
$candidate_result = mysqli_query($con, $candidate_query);

// Initialize an array to store candidate vote counts
$candidate_votes = [];

// Fetch the total number of votes
$total_votes_query = "SELECT COUNT(*) AS total_votes FROM votes";
$total_votes_result = mysqli_query($con, $total_votes_query);
$total_votes_data = mysqli_fetch_assoc($total_votes_result);
$total_votes = $total_votes_data['total_votes'];

while ($candidate = mysqli_fetch_assoc($candidate_result)) {
    // Get the candidate's ID
    $candidate_id = $candidate['can_id'];

    // Get the number of votes for this candidate
    $vote_query = "SELECT COUNT(*) AS vote_count FROM votes WHERE can_id = '$candidate_id'";
    $vote_result = mysqli_query($con, $vote_query);
    $vote_count_data = mysqli_fetch_assoc($vote_result);

    // Calculate vote percentage
    $vote_percentage = $total_votes > 0 
        ? round(($vote_count_data['vote_count'] / $total_votes) * 100, 2) 
        : 0;

    // Store the candidate's details
    $candidate_votes[] = [
        'can_id' => $candidate['can_id'],
        'can_name' => $candidate['can_name'],
        'can_image' => $candidate['can_image'],
        'can_party_symbol' => $candidate['can_party_symbol'],
        'vote_count' => $vote_count_data['vote_count'],
        'vote_percentage' => $vote_percentage
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">Election Results</h2>

    <!-- Search bar -->
    <div class="mb-4">
        <input type="text" id="searchInput" class="form-control" placeholder="Search by candidate name or party">
    </div>

    <div class="row">
        <?php foreach ($candidate_votes as $candidate): ?>
            <div class="col-md-4 text-center mb-4">
                <div class="card">
                    <!-- Candidate image -->
                    <img src="profile/<?php echo htmlspecialchars($candidate['can_image']); ?>" 
                         class="card-img-top" 
                         alt="Image of <?php echo htmlspecialchars($candidate['can_name']); ?>" 
                         style="max-height: 200px; object-fit: cover;">
                    <div class="card-body">

                        <!-- Candidate name -->
                        <h5 class="card-title"><?php echo htmlspecialchars($candidate['can_name']); ?></h5>

                        <!-- Party symbol -->
                        <p class="card-text">
                            <strong>Party Symbol:</strong><br>
                            <img src="profile/<?php echo htmlspecialchars($candidate['can_party_symbol']); ?>" 
                                 alt="Party Symbol of <?php echo htmlspecialchars($candidate['can_party_symbol']); ?>" 
                                 width="80" 
                                 height="80">
                        </p>

                        <!-- Vote count -->
                        <p class="card-text"><strong>Votes:</strong> <?php echo $candidate['vote_count']; ?></p>

                        <!-- Vote percentage -->
                        <p class="card-text"><strong>Percentage:</strong> <?php echo $candidate['vote_percentage']; ?>%</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-5">
        <!-- Link back to the voting page -->
        <a href="voting_page.php" class="btn btn-primary">Go back to voting page</a>
    </div>
</div>

<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script>
    document.getElementById('searchInput').addEventListener('input', function () {
        const searchValue = this.value.toLowerCase();
        const cards = document.querySelectorAll('.card');

        cards.forEach(card => {
            const candidateName = card.querySelector('.card-title').textContent.toLowerCase();
            const partySymbol = card.querySelector('.card-text img').alt.toLowerCase();

            if (candidateName.includes(searchValue) || partySymbol.includes(searchValue)) {
                card.parentElement.style.display = 'block';
            } else {
                card.parentElement.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>
