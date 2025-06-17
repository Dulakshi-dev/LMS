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
$pageTitle = "Dashboard";
$pageCss = "member-dashboard.css";
require_once Config::getViewPath("common","head.php");
?>


<body onload="loadDashboardBooks();">
  <?php require_once Config::getViewPath("member", "header.php"); ?>

  <div class="d-flex">
    <div>
      <div class="nav-bar d-block d-lg-none">
        <?php require_once Config::getViewPath("member", "sm_sidepanel.php"); ?>
      </div>
      <div class="nav-bar d-none d-lg-block">
        <?php require_once Config::getViewPath("member", "sidepanel.php"); ?>
      </div>
    </div>


    <div class="container-fluid p-4">

      <div class="">
        <div class="row my-2">
          <div class="col-lg-4 col-md-1">

          </div>

          <div class="col-lg-4 col-md-8">
            <div class="d-flex gap-2">
              <input id="title" type="text" class="form-control" placeholder="Search your favourite Book" oninput="searchBook();">
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 py-2 d-flex justify-content-end">
          <a href="<?php echo Config::indexPathMember() ?>?action=showallbooks" class="text-decoration-none me-5">
            See All Books <i class="fa fa-angle-right"></i>
          </a>
        </div>
      </div>
      <div class="row">

        <div class="col-md-12 d-none" id="searchResults">
          <div class="bg-light rounded p-4 mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5>Search Results</h5>
            </div>
            <div class="row g-4" id="searchBody">

            </div>
          </div>
        </div>

        <div class="col-md-12 ">
          <div class="bg-light rounded p-4 mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5>Recommended</h5>
            </div>
            <div class="row g-4" id="recommendedBooks">

            </div>
          </div>
        </div>

        <div class="col-md-12">
          <div class="bg-light rounded p-4 mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5>New Arrivals</h5>
            </div>
            <div class="row g-4" id="latestBooks">

            </div>
          </div>
        </div>

        <div class="col-md-12">
          <div class="bg-light rounded p-4 mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5>Top Books</h5>
            </div>
            <div class="row g-4" id="topBooks">

            </div>
          </div>
        </div>


      </div>
    </div>
  </div>

  <div class="offcanvas offcanvas-end bg-dark text-white" tabindex="-1" id="bookDetailsCanvas" aria-labelledby="bookDetailsCanvasLabel" style="width: 350px;">
    <div class="offcanvas-header">
      <h5 id="bookDetailsCanvasLabel">Book Details</h5>
      <button type="button" class="btn-close bg-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
      <div class="d-flex justify-content-end ">
        <button style="background: none; border: none; padding: 0; cursor: pointer;" class="">
          <i id="save-btn" class="fa fa-bookmark text-warning fs-4"></i>
        </button>
      </div>
      <div class="book-details">
        <div class="bg-white rounded m-3 d-flex flex-column align-items-center justify-content-center"
          style="width: 160px; height: 250px;">
          <img src="" alt="">
          <p class="text-warning mt-2 text-center"><strong>ID:</strong> <span id="book-id"></span></p>
        </div>
        <h5 class="my-2 text-warning" id="book-title"></h5>
        <p id="book-author"></p>
        <p> <span id="book-availability"></span></p>

        <p style="text-align: justify;"><strong>Description:</strong> <span id="book-description"></span></p>
        <button id="rbox" class="btn btn-primary">Reserve</button>

        <div id="rbox-2" class="d-none">
          <p>
            Your book reservation is <span class="reseve">valid for one week</span> from the reserved date.
            If the book is not collected within this period,
            the reservation will be automatically canceled
          </p>
          <div class="d-flex justify-content-center my-2">
            <button class="btn btn-primary reserve-btn" id="reserve-btn">Confirm Reservation</button>
          </div>
        </div>

      </div>
    </div>
  </div>
  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const reserveBtn = document.getElementById("rbox");
    const rbox2 = document.getElementById("rbox-2");
    const confirmBtn = document.getElementById("reserve-btn");

    reserveBtn.addEventListener("click", function () {
      rbox2.classList.remove("d-none");
      reserveBtn.style.display = "none";
    });

    confirmBtn.addEventListener("click", function () {
      // You can optionally show a success alert here
      Swal.fire({
        timer: 2000,
        showConfirmButton: false
      });

      // Reset buttons after confirmation
      rbox2.classList.add("d-none");
      reserveBtn.style.display = "block";
    });
  });
</script>


  <?php require_once Config::getViewPath("common", "footer.php"); ?>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="<?php echo Config::getJsPath("memberBook.js"); ?>"></script>
  <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>
</body>

</html>