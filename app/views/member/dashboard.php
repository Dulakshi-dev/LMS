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
    .success {
      color: rgba(21, 83, 28, 1);
      background: rgba(185, 247, 192, 1);
    }
    .success:hover {
      background: rgb(69, 161, 80);
    }
  </style>
</head>

<body>
  <?php require_once Config::getViewPath("member", "header.php"); ?>

  <div class="d-flex">
    <div class="nav-bar">
      <?php require_once Config::getViewPath("member", "sidepanel.php"); ?>
    </div>

    <div class="container-fluid p-4">
      <div class="row">
        <div class="col-md-12">
          <div class="bg-light rounded p-4 mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5>Recommended</h5>
              <a href="#" class="text-decoration-none">See All <i class="fa fa-angle-right"></i></a>
            </div>
            <div class="row g-4">
              <?php if (!empty($recbooks)) {
                foreach ($recbooks as $row) { ?>
                  <div class="col-md-3 col-sm-6">
                    <div class="book-card">
                      <div class="book-image">
                        <img src="<?php echo Config::indexPath() ?>?action=serveimage&image=<?php echo urlencode(basename($row['cover_page'])); ?>" alt="Book Cover">
                      </div>
                      <div class="p-3 d-flex justify-content-between align-items-center">
                        <div class="text-start">
                          <div class="book-title"><?php echo $row["title"]; ?></div>
                          <div><?php echo $row["author"]; ?></div>
                        </div>
                        <button class="btn success btn-sm view-details" 
                          data-title="<?php echo $row["title"]; ?>" 
                          data-author="<?php echo $row["author"]; ?>" 
                          data-id="<?php echo $row["book_id"]; ?>" 
                          data-description="<?php echo $row["description"]; ?>">
                          View Details
                        </button>
                      </div>
                    </div>
                  </div>
              <?php }
              } else {
                echo "<p>No Books found</p>";
              } ?>
            </div>
          </div>
        </div>

        <div class="col-md-12">
          <div class="bg-light rounded p-4">
            <h5>Categories</h5>
            <div class="d-flex flex-wrap my-3">
              <button class="btn btn-primary mx-2">All</button>
              <button class="btn btn-outline-primary mx-2">Sci-Fi</button>
              <button class="btn btn-outline-primary mx-2">Fantasy</button>
              <button class="btn btn-outline-primary mx-2">Education</button>
              <button class="btn btn-outline-primary mx-2">Drama</button>
              <button class="btn btn-outline-primary mx-2">Geography</button>
            </div>

            <div class="row g-4">
              <?php if (!empty($books)) {
                foreach ($books as $row) { ?>
                  <div class="col-md-4 col-sm-6">
                    <div class="book-card">
                      <div class="book-image">
                        <img src="<?php echo Config::indexPath() ?>?action=serveimage&image=<?php echo urlencode(basename($row['cover_page'])); ?>" alt="Book Cover">
                      </div>
                      <div class="p-3 d-flex justify-content-between align-items-center">
                        <div class="text-start">
                          <div class="book-title"><?php echo $row["title"]; ?></div>
                          <div><?php echo $row["author"]; ?></div>
                        </div>
                        <button class="btn success btn-sm view-details" 
                          data-title="<?php echo $row["title"]; ?>" 
                          data-author="<?php echo $row["author"]; ?>" 
                          data-id="<?php echo $row["book_id"]; ?>" 
                          data-description="<?php echo $row["description"]; ?>">
                          View Details
                        </button>
                      </div>
                    </div>
                  </div>
              <?php }
              } else {
                echo "<p>No Books found</p>";
              } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.querySelectorAll('.view-details').forEach(button => {
      button.addEventListener('click', function() {
        const title = this.getAttribute('data-title');
        const author = this.getAttribute('data-author');
        const id = this.getAttribute('data-id');
        const description = this.getAttribute('data-description');

        document.getElementById('book-title').textContent = title;
        document.getElementById('book-author').textContent = author;
        document.getElementById('book-id').textContent = id;
        document.getElementById('book-description').textContent = description;
        document.getElementById('reserve-btn').onclick = function() {
          window.location.href = `<?php echo Config::indexPathMember(); ?>?action=reserve&book_id=${id}&member_id=${memberId}`;
        };

        document.getElementById('save-btn').onclick = function() {
          window.location.href = `<?php echo Config::indexPathMember(); ?>?action=save&book_id=${id}&member_id=${memberId}`;
        };

        const offcanvas = new bootstrap.Offcanvas(document.getElementById('bookDetailsCanvas'));
        offcanvas.show();
      });
    });
  </script>
</body>
</html>
