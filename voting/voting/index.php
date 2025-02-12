<?php
session_start();
include 'conn.php'; // Ensure this file contains your database connection code

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if superadmin
    $superadmin_query = "SELECT * FROM superadmin WHERE username = ?";
    $stmt = $con->prepare($superadmin_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $superadmin_result = $stmt->get_result();

    if ($superadmin_result->num_rows == 1) {
        $superadmin_data = $superadmin_result->fetch_assoc();
        // Check if the password matches
        if ($password === $superadmin_data['password']) { // Change this to password_verify if hashed
            $_SESSION['superadmin_name'] = $username;
            header("Location: superadmin.php"); // Redirect to superadmin dashboard
            exit();
        }
    }

    // Check if voter
    $voter_query = "SELECT * FROM voters WHERE voters_id = ?";
    $stmt = $con->prepare($voter_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $voter_result = $stmt->get_result();

    if ($voter_result->num_rows == 1) {
        $voter_data = $voter_result->fetch_assoc();
        // Check if the password matches
        if (password_verify($password, $voter_data['password'])) { // Use password_verify for hashed passwords
            $_SESSION['voter_name'] = $voter_data['name'];
            $_SESSION['voter_id'] = $voter_data['voters_id'];
            header("Location: voting_page.php"); // Redirect to voting page
            exit();
        }
    }

    // If neither
    echo "<script>alert('Invalid credentials!');</script>";
}

// Registration logic for new users
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name']; // Assuming you want to collect the name during registration

    // Check if username already exists in both tables
    $check_superadmin = "SELECT * FROM superadmin WHERE username = ?";
    $check_voter = "SELECT * FROM voters WHERE voters_id = ?";

    $stmt = $con->prepare($check_superadmin);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $superadmin_result = $stmt->get_result();

    $stmt = $con->prepare($check_voter);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $voter_result = $stmt->get_result();

    if ($superadmin_result->num_rows > 0 || $voter_result->num_rows > 0) {
        echo "<script>alert('Username already exists!');</script>";
    } else {
        // Insert into voters table (default)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $insert_query = "INSERT INTO voters (voters_id, name, password) VALUES (?, ?, ?)";
        $stmt = $con->prepare($insert_query);
        $stmt->bind_param("sss", $username, $name, $hashed_password);
        
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! You can now login.'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Registration failed!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVoting - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr .net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: url('https://thumbs.dreamstime.com/z/indian-people-hand-voting-sign-showing-general-election-india-illustration-indian-people-hand-voting-sign-showing-143420393.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input {
            margin-bottom: 10px;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        p {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>