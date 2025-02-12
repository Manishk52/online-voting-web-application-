<?php
session_start();
include 'conn.php';

// Check if the superadmin is already logged in
if (!isset($_SESSION['superadmin_name'])) {
    header('Location: index.php');
    exit();
}

// Query to get the total number of voters
$voters_query = "SELECT COUNT(*) AS total_voters FROM voters";
$voters_result = mysqli_query($con, $voters_query);
$voters_row = mysqli_fetch_assoc($voters_result);
$total_voters = $voters_row['total_voters'];

// Query to get the total number of candidates
$candidates_query = "SELECT COUNT(*) AS total_candidates FROM candidates";
$candidates_result = mysqli_query($con, $candidates_query);
$candidates_row = mysqli_fetch_assoc($candidates_result);
$total_candidates = $candidates_row['total_candidates'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eVoting Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007BFF;
            --secondary-color: #F8F9FA;
            --text-color: #343A40;
            --card-bg: #fff;
            --border-radius: 12px;
            --shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            --hover-color: #0056b3;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: url('https://www.atulhost.com/wp-content/uploads/2019/07/indian-flag-4k-gulaal-effect-scaled.jpg') no-repeat center center fixed; 
            background-size: cover;
            color: var(--text-color);
            line-height: 1.6;
        }

        header {
            background: var(--primary-color);
            color: white;
            padding: 2.5rem 1rem;
            text-align: center;
            border-bottom: 5px solid #0056b3;
        }

        header h1 {
            font-size: 2.8rem;
            font-weight: 600;
        }

        header p {
            font-size: 1.2rem;
            margin-top: 0.5rem;
        }

        .container {
            max-width: 1100px;
            margin: 3rem auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2.5rem;
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card h3 {
            font-size: 1.7rem;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
        }

        .card p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .card button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s, transform 0.3s;
        }

        .card button:hover {
            background: var(--hover-color);
            transform: scale(1.05);
        }

        footer {
            text-align: center;
            padding: 1.5rem;
            background: var(--primary-color);
            color: white;
            margin-top: 3rem;
        }

        footer p {
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>EVoting Dashboard</h1>
        <p>Welcome</p>
    </header>

    <div class="container">
        <div class="card">
            <h3>Total Voters</h3>
            <p><?php echo $total_voters . " voters have registered."; ?></p>
            <button onclick="window.location.href='voters.php'">View Voters</button>
        </div>

        <div class="card">
            <h3>Total Candidates</h3>
            <p><?php echo $total_candidates . " candidates are running."; ?></p>
            <button onclick="window.location.href='candidates.php'">View Candidates</button>
        </div>

        <div class="card">
            <h3>Add New Position</h3>
            <p>Click the button below to add a new position.</p>
            <button onclick="window.location.href='add_position.php'">Add Position</button>
        </div>

        <div class="card">
            <h3>Control Panel</h3>
            <p>Adding Candidates.</p>
            <form action="control.php" method="post">
                <button type="submit">Go to Control Panel</button>
            </form>
        </div>

        <div class="card">
            <h3>Logout</h3>
            <p>Sign out securely from the eVoting system.</p>
            <form action="vlogout.php" method="post">
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 eVoting System. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>