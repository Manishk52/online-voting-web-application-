<?php
    session_start();
    include 'conn.php';

    if (empty($_SESSION['voter_name'])) {
        header("Location: index.php");
        exit();
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet"> <!-- Modern Font -->
    <title>EVoting - Voting</title>
    <style>
        /* Background Image */
        body {
            background: url('https://wallpaperaccess.com/full/4475187.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff; /* White text color for readability */
            font-family: 'Poppins', sans-serif; /* Modern font */
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        /* Navbar */
        .navbar {
            background-color: #212121;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.8rem;
            color: #fff;
        }

        .navbar-nav .nav-link {
            color: #fff;
        }

        .navbar-nav .nav-link:hover {
            color: #d3d3d3;
        }

        /* Table Styles */
        .container {
            max-width: 1100px;
            margin: 30px auto;
        }

        table {
            background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent dark background */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        th, td {
            padding: 20px;
            text-align: center;
        }

        th {
            font-size: 1.2rem;
            font-weight: 600;
            color: #fff;
        }

        td {
            font-size: 1rem;
            color: #fff;
        }

        /* Hover effect for table rows */
        tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: scale(1.03);
            transition: all 0.3s ease;
        }

        /* Candidate image styling */
        .candidate-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        /* Candidate name styling */
        .candidate-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #f5f5f5;
        }

        /* Vote Button */
        .vote-btn {
            background-color: #28a745;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .vote-btn:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .vote-btn img {
            width: 30px;
            height: 30px;
        }

        /* Unique Heading Style */
        .candidate-heading {
            font-size: 2rem;
            font-weight: 700;
            color:rgb(45, 34, 255);
            text-decoration: underline;
            text-align: center;
            margin-top: 30px;
        }

        /* Modal Styles */
        .modal-content {
            background-color: #fff;
            color: #333;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Footer */
        footer {
            text-align: center;
            color: #fff;
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 10px;
            background-color: #212121;
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
                    <a class="nav-link" href="">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_url; ?>voting_done.php">View Results</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_url; ?>vlogout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Voting Modal -->
    <div class="modal fade" id="submitvotemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Submit Vote</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <p>Are you sure you want to vote for this candidate?</p>
                        <p>This action is irreversible.</p>
                        <input type="hidden" name="candidate_id" id="candidate_id">
                        <input type="hidden" name="voter_id" id="voter_id" value="<?php echo $_SESSION['voter_id']; ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" name="submit_vote" class="btn btn-info">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
        if (isset($_POST['submit_vote'])) {
            $candidate_id = $_POST['candidate_id'];
            $voter_id = $_POST['voter_id'];

            try {
                $insert_vote = "INSERT INTO votes (voter_id, can_id) VALUES ('$voter_id', '$candidate_id')";
                $insert_vote_result = mysqli_query($con, $insert_vote);

                if ($insert_vote_result) {
                    echo '<script>alert("Voted Successfully");</script>';
                    echo '<script>window.location = "voting_done.php";</script>';
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() === 45000) { // Trigger error code for duplicate vote
                    echo '<script>alert("You have already voted!");</script>';
                } else {
                    echo '<script>alert("Failed to vote: ' . $e->getMessage() . '");</script>';
                }
            }
        }
    ?>

    <h1 class="candidate-heading"><u>Candidate List</u></h1>
    <div class="container mt-3">
        <table class="table table-striped table-hover text-center">
            <thead>
                <tr>
                    <th>Candidate ID</th>
                    <th>Party Symbol</th>
                    <th>Candidate Image</th>
                    <th>Name</th>
                    <th>Party Name</th>
                    <th>Position</th>
                    <th>Vote</th>
                </tr>
            </thead>
            <tbody>
                <?php
               $fetch_candidate_data = "SELECT candidates.*, positions.position_name 
               FROM candidates
               INNER JOIN positions ON candidates.can_id = positions.can_id";
               $result_candidate_data = mysqli_query($con, $fetch_candidate_data);

                if (mysqli_num_rows($result_candidate_data) > 0) {
                    while ($res = mysqli_fetch_array($result_candidate_data)) {
                ?>
                    <tr>
                        <td class="pt-4"><h1><?php echo $res['can_id']; ?></h1></td>
                        <td><img src="<?php echo $base_url; ?>profile/<?php echo $res['can_party_symbol']; ?>" alt="Party Symbol" width="100"></td>
                        <td><img src="<?php echo $base_url; ?>profile/<?php echo $res['can_image']; ?>" alt="Candidate" width="100"></td>
                        <td><h3 class="pt-4"><?php echo $res['can_name']; ?></h3></td>
                        <td><h3 class="pt-4"><?php echo $res['can_party_name']; ?></h3></td>
                        <td><h3 class="pt-4"><?php echo $res['position_name']; ?></h3></td>
                        <td><a href="#" data-toggle="modal" data-target="#submitvotemodal" data-candidate_id="<?php echo $res['can_id']; ?>" data-voter_id="<?php echo $_SESSION['voter_id']; ?>">
                            <img src="http://icons.iconarchive.com/icons/iconarchive/blue-election/256/Election-Vote-2-icon.png" alt="Vote" width="100">
                        </a></td>
                    </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="5">No Data Available</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#submitvotemodal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var candidate_id = button.data('candidate_id');
                
                var modal = $(this);
                modal.find('.modal-body #candidate_id').val(candidate_id);
            });
        });
    </script>
</body>
</html>
