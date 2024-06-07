<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'connect.php';

$username = $password = $role = "";
$username_err = $password_err = $role_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter your username.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check if role is selected
    if (empty($_POST["role"])) {
        $role_err = "Please select your role.";
    } else {
        $role = $_POST["role"];
    }
    
    // Validate credentials
    if (empty($username_err) && empty($password_err) && empty($role_err)) {
        // Prepare the SQL statement based on the role
        if ($role == 'admin') {
            $sql = "SELECT admin_id, username, password FROM admins WHERE username = ?";
        } else {
            $sql = "SELECT user_id, username, password FROM users WHERE username = ?";
        }
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            if ($stmt->execute()) {
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $username, $hashed_password);
                    $stmt->fetch();
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, so start a new session
                        $_SESSION["loggedin"] = true;
                        $_SESSION["user_id"] = $id;
                        $_SESSION["username"] = $username;
                        $_SESSION["role"] = $role;

                        // Redirect user based on role
                        if ($role == 'admin') {
                            header("location: ../admin.php");
                        } else {
                            header("location: ../profile.php");
                        }
                        exit;
                    } else {
                        // Display an error message if password is not valid
                        $password_err = "Invalid password.";
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close connection
    $conn->close();
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Login Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-5">
                <h2 class="heading-section">Login</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-10">
                <div class="wrap d-md-flex">
                    <div class="img" style="background-image: url(images/bg-1.jpg);"></div>
                    <div class="login-wrap p-4 p-md-5">
                        <div class="d-flex">
                            <div class="w-100">
                                <h3 class="mb-4">Sign In</h3>
                            </div>
                            <div class="w-100">
                                <p class="social-media d-flex justify-content-end">
                                    <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-facebook"></span></a>
                                    <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-twitter"></span></a>
                                </p>
                            </div>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="signin-form">
                            <div class="form-group mb-3">
                                <label class="label" for="username">Username</label>
                                <input type="text" class="form-control" name="username" placeholder="Enter Your Username" value="<?php echo htmlspecialchars($username); ?>" required>
                                <span class="error"><?php echo $username_err; ?></span>
                            </div>
                            <div class="form-group mb-3">
                                <label class="label" for="password">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Enter Your Password" required>
                                <span class="error"><?php echo $password_err; ?></span>
                            </div>
                            <div class="form-group mb-3">
                                <label class="label" for="role">Role</label>
                                <select class="form-control" name="role" required>
                                    <option value="user" <?php echo ($role == 'user') ? 'selected' : ''; ?>>User</option>
                                    <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                </select>
                                <span class="error"><?php echo $role_err; ?></span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary rounded submit px-3">Sign In</button>
                            </div>
                            <div class="form-group d-md-flex">
                                <div class="w-50 text-left">
                                    <label class="checkbox-wrap checkbox-primary mb-0">Remember Me
                                        <input type="checkbox" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="w-50 text-md-right">
                                    <a href="#">Forgot Password</a>
                                </div>
                            </div>
                        </form>
                        <p class="text-center">Not a member? <a href="signup.php">Sign Up</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
