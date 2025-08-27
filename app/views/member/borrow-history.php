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
$pageTitle = "Borrow History";
$pageCss = "borrow-history.css";
require_once Config::getViewPath("common","head.php");
?>

<body class="bg-white" onload="loadBorrowBooks();">
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
    <div class="container bg-light mt-md-4 p-md-4 w-75 rounded shadow-sm vh-100">
      <!-- Header -->
      <div class="d-flex justify-content-end align-items-center mb-4">
        <a href="#" class="page-link">
          <i class="fa fa-home"></i> 
        </a>
      </div>

      <!-- Search Bar -->
      <div class="row mb-5 g-3">
                <div class="col-12 col-md-4">
                    <input id="bookid" type="text" class="form-control" placeholder="Type Book ID" aria-label="Book ID">
                </div>
                <div class="col-12 col-md-4">
                    <input id="title" type="text" class="form-control" placeholder="Type Book Name" aria-label="Book Name">
                </div>
                <div class="col-10 col-md-3">
                    <input id="category" type="text" class="form-control" placeholder="Type Category" aria-label="Category">
                </div>
                <div class=" col-1 d-grid">
                    <button class="btn btn-primary" onclick="loadBorrowBooks();">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>

      <!-- Table -->
      <div class="table-responsive">
    <table class="table table-bordered text-center">
        <thead class="table-white">
            <tr>
                <th>Borrow ID</th>
                <th>Book ID</th>
                <th>Book</th>
                <th>Book Name</th>
                <th>Issued Date</th>
                <th>Date Due</th>
                <th>Borrow Status</th>
            </tr>
        </thead>
        <tbody id="bookTableBody">
            <!-- Table rows will be inserted dynamically here -->
        </tbody>
    </table>
</div>


      <!-- Pagination -->
      <div class="d-flex justify-content-between align-items-center entries offset-5">

      <div id="pagination"></div>

      </div>
    </div>
  </div>
  <?php require_once Config::getViewPath("common", "footer.php"); ?>

  <script src="<?php echo Config::getJsPath("borrowHistory.js"); ?>"></script>
  <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>