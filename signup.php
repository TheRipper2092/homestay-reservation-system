<!doctype html>
<html lang="en">
<head>
    <title>Register Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
error_reporting(0);
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Get the role from the form
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Determine which table to insert into based on role
    if ($role == 'admin') {
        $sql = "INSERT INTO admins (email, username, password) VALUES (?, ?, ?)";
    } else {
        $sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
    }
    
    // Prepare and bind
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $email, $username, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Registration successful.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-5">
                <h2 class="heading-section">Register</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-10">
                <div class="wrap d-md-flex">
                    <div class="img" style="background-image: url(images/bg-1.jpg);"></div>
                    <div class="login-wrap p-4 p-md-5">
                        <div class="d-flex">
                            <div class="w-100">
                                <h3 class="mb-4">Sign Up</h3>
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
                                <label class="label" for="email">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Your Email" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="label" for="username">Username</label>
                                <input type="text" class="form-control" name="username" placeholder="Enter Your Username" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="label" for="password">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Enter Your Password" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="label" for="role">Role</label>
                                <select class="form-control" name="role" required>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary rounded submit px-3">Sign Up</button>
                            </div>
                        </form>
                        <p class="text-center">Already a member? <a href="index.php">Sign In</a></p>
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
