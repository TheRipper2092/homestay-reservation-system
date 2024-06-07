<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Dummy login logic for demonstration purposes
// In a real application, replace this with actual authentication logic
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = ''; // Simulating a user login for testing
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HomeStay Hub</title>
  <!-- <link rel="stylesheet" href="h.css">
  <script src="header.js" defer></script> -->
</head>
<body>
  <header class="site-header js-site-header">
    <div class="container-fluid">
      <div class="row align-items-center">
        <!-- Logo Section -->
        <div class="col-6 col-lg-4 site-logo" data-aos="fade">
          <a href="index.php">HomeStay Hub</a>
        </div>
        <div class="col-6 col-lg-8">
          <!-- Menu Toggle Button for Mobile View -->
          <div class="site-menu-toggle js-site-menu-toggle" data-aos="fade">
            <span></span>
            <span></span>
            <span></span>
          </div>
          <!-- END menu-toggle -->
          <div class="site-navbar js-site-navbar">
            <nav role="navigation">
              <div class="container">
                <div class="row full-height align-items-center">
                  <div class="col-md-6 mx-auto">
                    <ul class="list-unstyled menu">
                      <li class="active"><a href="index.php">Home</a></li>
                      <li><a href="rooms.php">Rooms</a></li>
                      <li><a href="about.php">About</a></li>
                      <li><a href="contact.php">Contact</a></li>
                      <li><a href="reservation.php">Reservation</a></li>
                      <?php if (empty($_SESSION['username'])): ?>
                        <li><a href="login/index.php"><button type="button" class="btn btn-primary font-weight-bold">Login</button></a></li>
                      <?php else: ?>
                        <li>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></li>
                        <li><a href="<?php echo ($_SESSION['role'] == 'admin') ? 'admin.php' : 'profile.php'; ?>"><button type="button" class="btn btn-primary font-weight-bold"><?php echo ($_SESSION['role'] == 'admin') ? 'Admin Dashboard' : 'Profile'; ?></button></a></li>
                        <li><a href="login/logout.php"><button type="button" class="btn btn-primary font-weight-bold">Logout</button></a></li>
                      <?php endif; ?>
                
                    </ul>
                  </div>
                </div>
               
              </div>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </header>
</body>
</html>
