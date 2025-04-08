<?php
?>
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
$member_id = $_SESSION["member"]["member_id"]

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyLibrary</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .book-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px 3px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 200px;
        }

        .book-cover {
            width: 100%;
            height: 200px;
            border-radius: 5px;
        }

        #success {
            color: rgba(21, 83, 28, 1);
            background: rgb(127, 221, 138);
        }

        #success:hover {
            background: rgb(69, 161, 80);
        }

        .book-title {
            font-size: 18px;
            margin: 5px 0 0px;
        }

        .book-author {
            font-size: 14px;
            color: gray;
        }

        .reseve {
            color: red;
        }
    </style>
</head>

<body onload="loadSavedBooks();">
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

        <div class="container pt-4">
            <!-- Home Link -->
            <div class="d-flex justify-content-end align-items-center mb-4">
                <a href="#" class="page-link">
                    <i class="fa fa-home"></i> Home
                </a>
            </div>

            <!-- Search Bar -->
            <div class="input-group mb-5">
                <input id="bookid" type="text" class="form-control" placeholder="Type Book ID" aria-label="Book ID">
                <input id="title" type="text" class="form-control mx-3" placeholder="Type Book Name" aria-label="Book Name">
                <input id="category" type="text" class="form-control" placeholder="Type Category" aria-label="Category">
                <button class="btn btn-primary ms-2" onclick="loadSavedBooks();">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>

            <div id="myLibraryBody">

            </div>
            <div id="pagination"></div>

        </div>
    </div>


  <script src="<?php echo Config::getJsPath("myLibrary.js"); ?>"></script>
  <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>


    <!-- <script>
        function showReserve2() {
            document.getElementById("reserve1").classList.add("d-none");
            document.getElementById("reserve2").classList.remove("d-none");
        }

        // Star rating functionality
        document.querySelectorAll('.fa-star').forEach(star => {
            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                document.querySelectorAll('.fa-star').forEach(s => {
                    s.classList.remove('text-warning');
                    if (s.getAttribute('data-value') <= value) {
                        s.classList.add('text-warning');
                    }
                });
            });
        });
    </script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>