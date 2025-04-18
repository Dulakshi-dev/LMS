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
$pageTitle = "All Books";
$pageCss = "member-all-books.css";
require_once Config::getViewPath("home","head.php");
?>

<body onload="viewallbooks();">
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

      <div class="row">
        <div class="col-lg-4 col-md-8 offset-lg-4 mb-3">
          <div class="input-group">
            <input type="text" id="title" class="form-control" placeholder="Search your favourite Book">
            <span class="input-group-text"><i class="fa fa-search"></i></span>
          </div>

        </div>
      </div>

      <div class="row align-items-center mb-3">
        <div class="offset-lg-1 col-5 col-md-5 d-flex justify-content-end">
          <select class="form-select" id="category">
            <option value="">...</option>
          </select>
        </div>
        <div class="col-5 col-md-5 d-flex justify-content-end">
          <select class="form-select" id="language">
            <option value="">...</option>
          </select>
        </div>

      </div>

      <div class="col-md-12">
        <div class="bg-light rounded p-4">


          <div class="row g-4" id="bookBody">

          </div>
          <div id="pagination"></div>

        </div>
      </div>
    </div>
  </div>
  </div>

  <div class="offcanvas offcanvas-end bg-dark text-white" tabindex="-1" id="bookDetailsCanvas" aria-labelledby="bookDetailsCanvasLabel" style="width: 320px;">
    <div class="offcanvas-header">
      <h5 id="bookDetailsCanvasLabel">Book Details</h5>
      <button type="button" class="btn-close bg-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>


    <div class="offcanvas-body">
      <div class="d-flex justify-content-end ">
        <button style="background: none; border: none; padding: 0; cursor: pointer;">
          <i id="save-btn" class="fa fa-bookmark text-white fs-4"></i>
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
        <button id="reserve-btn" class="btn btn-primary">Reserve</button>
      </div>
    </div>
  </div>
  <?php require_once Config::getViewPath("home", "footer.view.php"); ?>


  <script src="<?php echo Config::getJsPath("memberBook.js"); ?>"></script>
  <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>

</html>