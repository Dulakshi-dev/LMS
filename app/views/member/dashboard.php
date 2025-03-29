<?php
require_once "../../main.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Details</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>
    .book-card {
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      overflow: hidden;
      transition: transform 0.3s ease;
      height: 100%;
    }

    .book-card:hover {
      transform: scale(1.03);
    }

    .book-image img {
      width: 100%;
      height: 300px;
      object-fit: cover;
    }

    .book-title {
      font-weight: bold;
      margin: 10px 0 5px;
    }

    #success {
      color: rgba(21, 83, 28, 1);
      background: rgb(127, 221, 138);
    }

    #success:hover {
      background: rgb(69, 161, 80);
    }

    .reseve {
      color: red;
    }
  </style>
</head>

<body onload="loadDashboardBooks();">
  <?php require_once Config::getViewPath("member", "header.php"); ?>

  <div class="d-flex">
    <div class="nav-bar d-none d-md-block">
      <?php require_once Config::getViewPath("member", "sidepanel.php"); ?>
    </div>

    <div class="container-fluid p-4">

      <div class="">
        <div class="row my-2">
          <div class="col-lg-4 col-md-1">

          </div>

          <div class="col-lg-4 col-md-8">
            <div class="d-flex gap-2">
              <input type="text" class="form-control" placeholder="Search your favourite Book">
              <button class="btn"><i class="fa fa-search"></i></button>
            </div>
          </div>

          <div class="col-4 col-md-2 d-flex justify-content-end align-items-center ms-auto">
            <a href="#" class="text-decoration-none text-dark  d-flex align-items-center">
              <i class="fa fa-home me-1"></i> Dashboard
            </a>
          </div>
        </div>


      </div>

      <div class="row">
        <div class="col-md-12 ">
          <div class="bg-light rounded p-4 mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5>Recommended</h5>
              <a href="<?php echo Config::indexPathMember() ?>?action=showallbooks" class="text-decoration-none">See All <i class="fa fa-angle-right"></i></a>
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
    document.addEventListener("DOMContentLoaded", function() {

      const reserveBtn = document.getElementById("rbox");
      const rbox2 = document.getElementById("rbox-2");

      reserveBtn.addEventListener("click", function() {
rbox2.classList.remove("d-none");
        reserveBtn.style.display = "none";
      });

    });
  </script>


  <?php require_once Config::getViewPath("home", "footer.view.php"); ?>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="<?php echo Config::getJsPath("memberBook.js"); ?>"></script>
  <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>
</body>

</html>