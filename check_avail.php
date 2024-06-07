<?php
require 'login/connect.php'; // Include the database connection

if(isset($_POST['checkin_date']) && isset($_POST['checkout_date']) && isset($_POST['adults']) && isset($_POST['children'])){
  $checkin_date = $_POST['checkin_date'];
  $checkout_date = $_POST['checkout_date'];
  $adults = $_POST['adults'];
  $children = $_POST['children'];

  // Connect to the database
  include 'login/connect.php';
  // Query to retrieve the rooms
  $query = "SELECT * FROM rooms";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    echo '<!DOCTYPE HTML>
    <html>
      <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Available Rooms</title>
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
        <section class="section bg-light">
          <div class="container">
            <div class="row">';
            
    while($row = mysqli_fetch_assoc($result)) {
      $room_id = $row['room_id'];
      $query2 = "SELECT * FROM reservations WHERE room_id = '$room_id' AND check_in_date <= '$checkout_date' AND check_out_date >= '$checkin_date'";
      $result2 = mysqli_query($conn, $query2);
      if (mysqli_num_rows($result2) == 0) {
        echo '<div class="col-md-4 mb-5" data-aos="fade-up">';
        echo '<a href="reservation.php?room_id=' . $room_id . '" class="room">';
        echo '<figure class="img-wrap">';
        echo '<img src="' . $row['image_url'] . '" alt="Room Image" class="img-fluid mb-3">';
        echo '</figure>';
        echo '<div class="p-3 text-center room-info">';
        echo '<h2>' . $row['room_name'] . '</h2>';
        echo '<span class="text-uppercase letter-spacing-1">&#x20B9; ' . $row['price'] . ' / per night</span>';
        echo '</div></a></div>';
      }
    }
    echo '</div></div></section>';
    include_once("footer.php");
    echo '<script src="js/jquery-3.3.1.min.js"></script>
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
      </body></html>';
  } else {
    echo '<p>No rooms available</p>';
  }
  mysqli_close($conn);
} else {
  echo 'Invalid request';
}
?>
