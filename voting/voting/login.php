<?php
session_start();
include 'conn.php'; // Include database connection
?>

<!DOCTYPE html>
<html>
<head>
    <title>EVoting - Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: url('https://thumbs.dreamstime.com/z/indian-people-hand-voting-sign-showing-general-election-india-illustration-indian-people-hand-voting-sign-showing-143420393.jpg') no-repeat center center fixed;
        }
        form {
            border: 3px solid #f1f1f1; 
            padding: 20px; 
            border-radius: 5px;
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        #button {
            background-color: rgb(43, 76, 223);
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
            border-radius: 4px;
        }
        #button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <h2 class="text-center my-3">Voter's Login</h2>

    <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
            $voter_id = mysqli_real_escape_string($con, $_POST['voter_id']);
            $password = $_POST['password']; // No need to escape password

            // Prepare the SQL statement
            $stmt = $con->prepare("SELECT * FROM voters WHERE voters_id = ?");
            $stmt->bind_param("s", $voter_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $voter = $result->fetch_assoc();
                // Verify the password
                if (password_verify($password, $voter['password'])) {
                    $_SESSION['voter_id'] = $voter['voters_id'];
                    $_SESSION['voter_name'] = $voter['name'];
                    header("Location: voting.php");
                    exit();
                } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Invalid Voter ID or Password</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                          </div>';
                }
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Invalid Voter ID or Password</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
            }
            $stmt->close(); // Close the statement
        }
        ?>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="post">
                    <label for="voter_id"><b>Voter ID</b></label>
                    <input type="text" name="voter_id" id="voter_id" placeholder="Enter Voter ID" required>

                    <label for="password"><b>Password</b></label>
                    <input type="password" name="password" id="password" placeholder="Enter Password" required>

                    <button type="submit" id="button" name="login">Login</button>
                </form>

                <div class="text-center mt-3">
                    <a href="register.php" class="btn btn-link">Don't have an account? Register here</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>