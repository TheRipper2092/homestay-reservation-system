<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    echo json_encode(['status' => 'redirect', 'location' => 'login/index.php']);
    exit();
}

require 'login/connect.php'; // Include the database connection

$username = $_SESSION['username'];

// Fetch user ID
$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Fetch room categories
$categories = [];
$category_query = "SELECT category_id, category_name FROM room_categories";
$category_result = $conn->query($category_query);
while ($row = $category_result->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch rooms
$rooms = [];
$room_query = "SELECT room_id, room_name, category_id FROM rooms";
$room_result = $conn->query($room_query);
while ($row = $room_result->fetch_assoc()) {
    $rooms[] = $row;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $adults = $_POST['adults'];
    $children = $_POST['children'];
    $room_id = $_POST['room_id'];
    $notes = $_POST['message'];

    // Validate inputs
    if (empty($name) || empty($phone) || empty($email) || empty($checkin_date) || empty($checkout_date) || empty($adults) || empty($children) || empty($room_id)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    // Check if the room is already reserved for the selected dates
    $check_query = "SELECT COUNT(*) FROM reservations WHERE room_id = ? AND check_in_date <= ? AND check_out_date >= ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("iss", $room_id, $checkout_date, $checkin_date);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(['status' => 'error', 'message' => 'This room is already reserved for the selected dates.']);
        exit();
    } else {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO reservations (user_id, name, phone, email, check_in_date, check_out_date, adults, children, room_id, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssiss", $user_id, $name, $phone, $email, $checkin_date, $checkout_date, $adults, $children, $room_id, $notes);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Reservation made successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>HomeStay Hub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=|Roboto+Sans:400,700|Playfair+Display:400,700">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">
    <link rel="stylesheet" href="css/fancybox.min.css">

    <link rel="stylesheet" href="fonts/ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="fonts/fontawesome/css/font-awesome.min.css">

    <!-- Theme Style -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include_once("header.php") ?>
<!-- END head -->

<section class="site-hero inner-page overlay" style="background-image: url(images/hero_4.jpg)" data-stellar-background-ratio="0.5">
    <div class="container">
        <div class="row site-hero-inner justify-content-center align-items-center">
            <div class="col-md-10 text-center" data-aos="fade">
                <h1 class="heading mb-3">Reservation Form</h1>
                <ul class="custom-breadcrumbs mb-4">
                    <li><a href="index.php">Home</a></li>
                    <li>&bullet;</li>
                    <li>Reservation</li>
                </ul>
            </div>
        </div>
    </div>

    <a class="mouse smoothscroll" href="#next">
        <div class="mouse-icon">
            <span class="mouse-wheel"></span>
        </div>
    </a>
</section>
<!-- END section -->

<section class="section contact-section" id="next">
    <div class="container">
        <div class="row">
            <div class="col-md-7" data-aos="fade-up" data-aos-delay="100">

                <form id="reservationForm" class="bg-white p-md-5 p-4 mb-5 border">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="text-black font-weight-bold" for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="text-black font-weight-bold" for="phone">Phone</label>
                            <input type="text" id="phone" name="phone" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="text-black font-weight-bold" for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="text-black font-weight-bold" for="checkin_date">Date Check In</label>
                            <input type="text" id="checkin_date" name="checkin_date" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="text-black font-weight-bold" for="checkout_date">Date Check Out</label>
                            <input type="text" id="checkout_date" name="checkout_date" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="adults" class="font-weight-bold text-black">Adults</label>
                            <div class="field-icon-wrap">
                                <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                <select name="adults" id="adults" class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4+">4+</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="children" class="font-weight-bold text-black">Children</label>
                            <div class="field-icon-wrap">
                                <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                <select name="children" id="children" class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4+">4+</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="room_category" class="font-weight-bold text-black">Room Category</label>
                            <div class="field-icon-wrap">
                                <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                <select name="room_category" id="room_category" class="form-control">
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="room_id" class="font-weight-bold text-black">Room</label>
                            <div class="field-icon-wrap">
                                <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                <select name="room_id" id="room_id" class="form-control">
                                    <option value="">Select Room</option>
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?php echo $room['room_id']; ?>" data-category="<?php echo $room['category_id']; ?>"><?php echo $room['room_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="text-black font-weight-bold" for="message">Notes</label>
                            <textarea name="message" id="message" class="form-control" cols="30" rows="8"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <input type="submit" value="Reserve Now" class="btn btn-primary text-white">
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>

<?php include_once("footer.php") ?>
<!-- END footer -->

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
<script>
    $(document).ready(function () {
        $('#room_category').change(function () {
            var selectedCategory = $(this).val();
            $('#room_id option').each(function () {
                if ($(this).data('category') == selectedCategory) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            $('#room_id').val('');
        }).change();

        $('#reservationForm').submit(function (event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'reservation.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        $('#reservationForm')[0].reset();
                    } else if (response.status === 'error') {
                        alert(response.message);
                    } else if (response.status === 'redirect') {
                        window.location.href = response.location;
                    }
                },
                error: function (xhr, status, error) {
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>
</body>
</html>
