<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'login/connect.php'; // Include the database connection

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
            <h1 class="heading mb-3">Rooms</h1>
            <ul class="custom-breadcrumbs mb-4">
              <li><a href="index.html">Home</a></li>
              <li>&bullet;</li>
              <li>Rooms</li>
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

    <section class="section pb-4">
      <div class="container">
        <div class="row check-availabilty" id="next">
          <div class="block-32" data-aos="fade-up" data-aos-offset="-200">
            <form action="" method="post">
              <div class="row">
                <div class="col-md-6 mb-3 mb-lg-0 col-lg-3">
                  <label for="checkin_date" class="font-weight-bold text-black">Check In</label>
                  <div class="field-icon-wrap">
                    <div class="icon"><span class="icon-calendar"></span></div>
                    <input type="text" id="checkin_date" class="form-control" name="checkin_date">
                  </div>
                </div>
                <div class="col-md-6 mb-3 mb-lg-0 col-lg-3">
                  <label for="checkout_date" class="font-weight-bold text-black">Check Out</label>
                  <div class="field-icon-wrap">
                    <div class="icon"><span class="icon-calendar"></span></div>
                    <input type="text" id="checkout_date" class="form-control" name="checkout_date">
                  </div>
                </div>
                <div class="col-md-6 mb-3 mb-md-0 col-lg-3">
                  <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
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
                    <div class="col-md-6 mb-3 mb-md-0">
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
                </div>
                <div class="col-md-6 col-lg-3 align-self-end">
                  <button type="submit" class="btn btn-primary btn-block text-white">Check Availability</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <section class="section bg-image overlay" style="background-image: url('images/hero_4.jpg');">
      <div class="container">
        <div class="row align-items-center"></div>
      </div>
    </section>

    <section class="section bg-light">
      <div class="container">
        <div class="row">
          <div class="col-md-12" data-aos="fade-up">
            <h2 class="heading">Our Rooms</h2>
            <p class="mb-5">Rooms Categories which are offered by our website.</p>
             
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
              $checkin_date = $_POST['checkin_date'];
              $checkout_date = $_POST['checkout_date'];
              $adults = $_POST['adults'];
              $children = $_POST['children'];

              // Check if the dates are provided
              if (empty($checkin_date) || empty($checkout_date)) {
                echo '<p>Please provide both check-in and check-out dates.</p>';
              } else {
                $query = "SELECT * FROM rooms";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                  echo '<div class="row">';
                  while($row = mysqli_fetch_assoc($result)) {
                    $room_id = $row['room_id'];
                    $query2 = "SELECT * FROM reservations WHERE room_id = '$room_id' AND check_in_date <= '$checkout_date' AND check_out_date >= '$checkin_date'";
                    $result2 = mysqli_query($conn, $query2);
                    if (mysqli_num_rows($result2) == 0) {
                      echo '<div class="col-md-4 mb-5" data-aos="fade-up">';
                      echo '<a href="reservation.php?room_id=' . $room_id . '" class="room">';
                      echo '<figure class="img-wrap">';
                      echo '<img src="' . $row['image_url'] . '" alt="Free website template" class="img-fluid mb-3">';
                      echo '</figure>';
                      echo '<div class="p-3 text-center room-info">';
                      echo '<h2>' . $row['room_name'] . '</h2>';
                      echo '<span class="text-uppercase letter-spacing-1">&#x20B9; ' . $row['price'] . ' / per night</span>';
                      echo '</div>';
                      echo '</a>';
                      echo '</div>';
                    }
                  }
                  echo '</div>';
                } else {
                  echo '<p>No rooms available</p>';
                }
                mysqli_close($conn);
              }
            }
            ?>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
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
  </body>
</html>
