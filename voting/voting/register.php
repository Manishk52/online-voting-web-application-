<?php
session_start();
include 'conn.php'; // Ensure this file contains your database connection code

// Check if form is submitted
if (isset($_POST['register'])) {
    // Escape user inputs for security
    $voter_id = mysqli_real_escape_string($con, $_POST['voter_id']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $age = mysqli_real_escape_string($con, $_POST['age']);  // Retrieving age from form

    // Validate age (make sure it's a number and greater than 18)
    if (!is_numeric($age) || $age < 18) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Age must be a number and at least 18</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    } else {
        // Check if passwords match
        if ($password !== $confirm_password) {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Passwords do not match</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        } else {
            // Check if voter ID already exists
            $sql_check = "SELECT * FROM voters WHERE voters_id='$voter_id'";
            $result_check = mysqli_query($con, $sql_check);
            if (mysqli_num_rows($result_check) > 0) {
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Voter ID already exists</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert new voter details into the database
                $sql_insert = "INSERT INTO voters (voters_id, name, password, age) VALUES ('$voter_id', '$name', '$hashed_password', '$age')";
                if (mysqli_query($con, $sql_insert)) {
                    // Redirect to login page immediately after successful registration
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Registration successful!</strong> Redirecting to login page...</div>';
                    // Redirect to login page
                    header("Location: login.php");
                    exit();
                } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Error: ' . mysqli_error($con) . '</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVoting - Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('https://img.freepik.com/premium-photo/india-vote-country-national-flag-button-3d-illustration_839051-8729.jpg') no-repeat center center fixed; /* Add your image path here */
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #fff;
        }

        .register-form {
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            transition: transform 0 .3s ease-in-out;
        }

        .register-form:hover {
            transform: translateY(-10px);
        }

        .form-title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-title h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3436;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
        }

        input[type="text"], input[type="password"], input[type="number"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            color: #333;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus, input[type="number"]:focus {
            border-color: #6c5ce7;
            outline: none;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #6c5ce7;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #5a4ad1;
        }

        .alert {
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <div class="register-form">
        <div class="form-title">
            <h2>Voter's Registration</h2>
        </div>
        
        <!-- Registration Form -->
        <form action="" method="post">
            <div class="form-group">
                <label for="voter_id"><b>Voting Id</b></label>
                <input type="text" placeholder="Enter Voting Id" name="voter_id" id="voter_id" required>
            </div>
            
            <div class="form-group">
                <label for="name"><b>Name</b></label>
                <input type="text" placeholder="Enter Your Name" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label for="password"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password"><b>Confirm Password</b></label>
                <input type="password" placeholder="Confirm Password" name="confirm_password" id="confirm_password" required>
            </div>

            <div class="form-group">
                <label for="age"><b>Age</b></label>
                <input type="number" placeholder="Enter Your Age" name="age" id="age" required>
            </div>

            <button type="submit" name="register">Register</button>
        </form>
    </div>

    <!-- Bootstrap and jQuery scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>