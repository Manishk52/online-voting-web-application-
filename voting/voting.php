<?php
session_start();
include 'conn.php';

// Redirect if session does not have a voter name
if(empty($_SESSION['voter_name'])){
    header("Location:index.php");
    exit();
}

// Check if the user has already voted after they click "I agree"
$voter_id = $_SESSION['voter_id'];
$check_vote = "SELECT * FROM votes WHERE voter_id='$voter_id'";
$check_vote_result = mysqli_query($con, $check_vote);

// Handle the form submission (i.e., when the user clicks "I agree")
if (isset($_POST['agree'])) {
    if($check_vote_result->num_rows > 0) {
        // If the user has already voted, redirect them to voting_done.php
        header("Location:voting_done.php");
        exit();
    } else {
        // Otherwise, process the vote and redirect to the voting done page
        // You can add code here to insert the vote into the database
        // Example: INSERT INTO votes (voter_id, can_id) VALUES ('$voter_id', '$selected_candidate_id');
        header("Location:voting_page.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <title>EVoting</title>
    <style>
        fieldset {
            background-color: #eeeeee;
        }
        legend {
            background-color: gray;
            color: white;
            padding: 5px 10px;
        }
        input {
            margin: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">EVoting</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="vlogout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="container mt-3 border rounded">
        <h3><u><b>Terms and Conditions:</u></b></h3>
        <p class="text-justify">
            I hereby acknowledge that I have thoroughly read and understood the Terms and Conditions for participating in the online voting process. By accepting these terms, I confirm my agreement to abide by all rules and regulations set forth, including the eligibility requirements, privacy policies, and security measures designed to protect my personal information and ensure the integrity of my vote
        </p>
        <input type="checkbox" name="checkbox" id="checkbox"><b>I agree to the Terms and Conditions</b>
        
        <!-- Form to submit the agreement -->
        <form method="POST" action="">
            <div class="text-center">
                <button type="submit" class="btn btn-primary mb-3" id="agree" name="agree" disabled>I agree</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#agree').attr('disabled', true); // Disable the button by default

            // Enable the button when the checkbox is checked
            $('#checkbox').click(function(){
                if($(this).prop("checked") == true){
                    $('#agree').attr('disabled', false); // Enable the button
                }
                else {
                    $('#agree').attr('disabled', true); // Disable the button
                }
            });
        });
    </script>
</body>
</html>
