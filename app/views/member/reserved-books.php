<?php

if (!isset($_SESSION['member'])) {
  header("Location: index.php?action=login"); 
  exit;
}

// Session Timeout (30 minutes)
if (isset($_SESSION['member']['last_activity']) && (time() - $_SESSION['member']['last_activity'] > 1800)) {
    session_unset();  // Clear session data
    session_destroy(); 
    header("Location: index.php?action=login"); 
    exit;
}

// Reset last activity time (only if user is active)
$_SESSION['member']['last_activity'] = time();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="bg-light">
  <?php require_once Config::getViewPath("member", "header.php"); ?>

  <div class="d-flex">
    <!-- Side Panel -->
    <div class="nav-bar d-none d-md-block">
      <?php require_once Config::getViewPath("member", "sidepanel.php"); ?>
    </div>
    <div class="container bg-white mt-4 p-4 rounded shadow-sm">
      <!-- Header -->
      <div class="d-flex justify-content-end align-items-center mb-4">
        <a href="#" class="page-link">
          <i class="fa fa-home"></i> Home
        </a>
      </div>

      <!-- Table -->
      <table class="table table-bordered text-center">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Book ID</th>
            <th>Book</th>
            <th>Book Name</th>
            <th>Reservation Date</th>
            <th>Expiration Date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="reservationTableBody">
        </tbody>
      </table>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="<?php echo Config::getJsPath("memberReservation.js"); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>