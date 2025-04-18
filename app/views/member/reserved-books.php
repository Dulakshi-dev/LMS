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

<?php
$pageTitle = "Reserved Books";
require_once Config::getViewPath("common","head.php");
?>

<body class="bg-white">
  <?php require_once Config::getViewPath("member", "header.php"); ?>

  <div class="d-flex">
    <!-- Side Panel -->
    <div>
      <div class="nav-bar d-block d-lg-none">
        <?php require_once Config::getViewPath("member", "sm_sidepanel.php"); ?>
      </div>
      <div class="nav-bar d-none d-lg-block">
        <?php require_once Config::getViewPath("member", "sidepanel.php"); ?>
      </div>
    </div>
    <div class="container bg-light w-75 mt-md-4 p-md-4 rounded shadow-sm">
      <!-- Header -->
      <div class="d-flex justify-content-end align-items-center mb-4">
        <a href="#" class="page-link">
          <i class="fa fa-home"></i>
        </a>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-bordered text-center">
          <thead class="table-white">
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
  </div>
  <?php require_once Config::getViewPath("common", "footer.view.php"); ?>


  <!-- Bootstrap JS -->
  <script src="<?php echo Config::getJsPath("memberReservation.js"); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>