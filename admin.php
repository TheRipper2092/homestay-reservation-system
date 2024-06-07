<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'login/connect.php'; // Include the database connection

// Handle form submission to add or edit rooms
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['room_name'])) {
    $room_name = $_POST['room_name'];
    $room_description = $_POST['room_description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $image_path = 'uploads/' . basename($image['name']);
        if (move_uploaded_file($image['tmp_name'], $image_path)) {
            $image_url = $image_path;
        } else {
            $message = "Error uploading image.";
        }
    } else {
        $image_url = $_POST['existing_image_url'];
    }

    if (isset($_POST['room_id']) && !empty($_POST['room_id'])) {
        // Update existing room
        $room_id = $_POST['room_id'];
        $query = "UPDATE rooms SET room_name='$room_name', room_description='$room_description', price='$price', category_id='$category_id', image_url='$image_url' WHERE room_id='$room_id'";
    } else {
        // Insert new room
        $query = "INSERT INTO rooms (room_name, room_description, price, category_id, image_url) VALUES ('$room_name', '$room_description', '$price', '$category_id', '$image_url')";
    }

    if (mysqli_query($conn, $query)) {
        $message = "Room successfully saved.";
    } else {
        $message = "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}

// Handle delete request for rooms
if (isset($_GET['delete'])) {
    $room_id = $_GET['delete'];
    $query = "DELETE FROM rooms WHERE room_id='$room_id'";
    if (mysqli_query($conn, $query)) {
        $message = "Room successfully deleted.";
    } else {
        $message = "Error deleting room: " . mysqli_error($conn);
    }
}

// Handle status update for reservations
if (isset($_GET['approve'])) {
    $reservation_id = $_GET['approve'];
    $query = "UPDATE reservations SET status='confirmed' WHERE reservation_id='$reservation_id'";
    if (mysqli_query($conn, $query)) {
        $message = "Reservation successfully confirmed.";
    } else {
        $message = "Error confirming reservation: " . mysqli_error($conn);
    }
}

if (isset($_GET['cancel'])) {
    $reservation_id = $_GET['cancel'];
    $query = "UPDATE reservations SET status='cancelled' WHERE reservation_id='$reservation_id'";
    if (mysqli_query($conn, $query)) {
        $message = "Reservation successfully cancelled.";
    } else {
        $message = "Error cancelling reservation: " . mysqli_error($conn);
    }
}

// Fetch all rooms
$rooms_query = "SELECT * FROM rooms";
$rooms_result = mysqli_query($conn, $rooms_query);

// Fetch all reservations
$reservations_query = "SELECT reservations.*, rooms.room_name FROM reservations JOIN rooms ON reservations.room_id = rooms.room_id";
$reservations_result = mysqli_query($conn, $reservations_query);
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Panel - HomeStay Hub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
                    <h1 class="heading mb-3">Admin Panel</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="section pb-4">
        <div class="container">
            <h2 class="heading">Manage Rooms</h2>
            <p><?php if (isset($message)) echo $message; ?></p>
            <form action="admin.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="room_name">Room Name</label>
                    <input type="text" class="form-control" id="room_name" name="room_name" required>
                </div>
                <div class="form-group">
                    <label for="room_description">Room Description</label>
                    <textarea class="form-control" id="room_description" name="room_description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" id="price" name="price" required>
                </div>
                <div class="form-group">
                    <label for="category_id">Category ID</label>
                    <input type="text" class="form-control" id="category_id" name="category_id" required>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <input type="hidden" name="existing_image_url" id="existing_image_url">
                </div>
                <input type="hidden" name="room_id" id="room_id">
                <button type="submit" class="btn btn-primary">Save Room</button>
            </form>
        </div>
    </section>

    <section class="section bg-light">
        <div class="container">
            <h2 class="heading">Existing Rooms</h2>
            <div class="row">
                <?php
                if (mysqli_num_rows($rooms_result) > 0) {
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-bordered">';
                    echo '<thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Category</th><th>Image</th><th>Actions</th></tr></thead>';
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($rooms_result)) {
                        echo '<tr>';
                        echo '<td>' . $row['room_id'] . '</td>';
                        echo '<td>' . $row['room_name'] . '</td>';
                        echo '<td>' . $row['room_description'] . '</td>';
                        echo '<td>' . $row['price'] . '</td>';
                        echo '<td>' . $row['category_id'] . '</td>';
                        echo '<td><img src="' . $row['image_url'] . '" alt="Room Image" style="width:100px;height:auto;"></td>';
                        echo '<td>';
                        echo '<a href="admin.php?edit=' . $row['room_id'] . '" class="btn btn-warning">Edit</a> ';
                        echo '<a href="admin.php?delete=' . $row['room_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<p>No rooms found</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <section class="section pb-4">
        <div class="container">
            <h2 class="heading">Manage Reservations</h2>
            <div class="row">
                <?php
                if (mysqli_num_rows($reservations_result) > 0) {
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-bordered">';
                    echo '<thead><tr><th>ID</th><th>Room</th><th>User ID</th><th>Start Date</th><th>End Date</th><th>Status</th><th>Actions</th></tr></thead>';
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($reservations_result)) {
                        echo '<tr>';
                        echo '<td>' . $row['reservation_id'] . '</td>';
                        echo '<td>' . $row['room_name'] . '</td>';
                        echo '<td>' . $row['user_id'] . '</td>';
                        echo '<td>' . $row['check_in_date'] . '</td>';
                        echo '<td>' . $row['check_out_date'] . '</td>';
                        echo '<td>' . $row['status'] . '</td>';
                        echo '<td>';
                        if ($row['status'] == 'pending') {
                            echo '<a href="admin.php?approve=' . $row['reservation_id'] . '" class="btn btn-success" onclick="return confirm(\'Are you sure you want to confirm this reservation?\')">Confirm</a> ';
                            echo '<a href="admin.php?cancel=' . $row['reservation_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to cancel this reservation?\')">Cancel</a>';
                        } else {
                            echo 'No actions available';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<p>No reservations found</p>';
                }
                mysqli_close($conn);
                ?>
            </div>
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
