<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login/index.php");
    exit();
}

require 'login/connect.php'; // Include the database connection

$username = $_SESSION['username'];

// Prepare and bind
$stmt = $conn->prepare("SELECT user_id, username, email FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($id, $username, $email);
$stmt->fetch();
$stmt->close();

// Fetch user reservations
$reservations = [];
$reservation_query = "SELECT r.reservation_id, r.check_in_date, r.check_out_date, rm.room_name AS room_name FROM reservations r JOIN rooms rm ON r.room_id = rm.room_id WHERE r.user_id = ?";
$reservation_stmt = $conn->prepare($reservation_query);
$reservation_stmt->bind_param("i", $id);
$reservation_stmt->execute();
$reservation_result = $reservation_stmt->get_result();

if ($reservation_result->num_rows > 0) {
    while ($row = $reservation_result->fetch_assoc()) {
        $reservations[] = $row;
    }
}
$reservation_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">
    <link rel="stylesheet" href="css/fancybox.min.css">
    <link rel="stylesheet" href="fonts/ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="fonts/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include_once("header.php"); ?>

    <section class="site-hero inner-page overlay" style="background-image: url(images/hero_4.jpg)" data-stellar-background-ratio="0.5">
        <div class="container">
            <div class="row site-hero-inner justify-content-center align-items-center">
                <div class="col-md-10 text-center" data-aos="fade">
                    <h1 class="heading mb-3">User Profile Dashboard</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="section pb-4">
        <div class="container">
            <h2 class="heading">Profile Information</h2>
            <form id="profileForm" action="update_profile.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </section>

    <section class="section bg-light pb-4">
        <div class="container">
            <h2 class="heading">Your Reservations</h2>
            <?php if (count($reservations) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Check-in Date</th>
                                <th>Check-out Date</th>
                                <th>Room Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reservation['reservation_id']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['check_in_date']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['check_out_date']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['room_name']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No reservations found.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="section pb-4">
        <div class="container">
            <h2 class="heading">Actions</h2>
            <a href="reservation.php"><button type="button" class="btn btn-primary">Make a Reservation</button></a>
        </div>
    </section>

    <?php include_once("footer.php"); ?>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/jquery.fancybox.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/jquery.timepicker.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
