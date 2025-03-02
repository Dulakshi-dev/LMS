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
    <div class="nav-bar">
      <?php require_once Config::getViewPath("member", "sidepanel.php"); ?>
    </div>
    <div class="container bg-white mt-4 p-4 rounded shadow-sm">
      <!-- Header -->
      <div class="d-flex justify-content-end align-items-center mb-4">
        <a href="#" class="page-link">
          <i class="fa fa-home"></i> Home
        </a>
      </div>

      <!-- Search Bar -->
      <div class="input-group mb-5">
        <input type="text" class="form-control" placeholder="Type Book ID" aria-label="Book ID">
        <input type="text" class="form-control mx-2" placeholder="Type Book Name" aria-label="Book Name">
        <input type="text" class="form-control" placeholder="Type Category" aria-label="Category">
        <button class="btn btn-primary ms-2">
          <i class="fa fa-search"></i> Search
        </button>
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
          </tr>
        </thead>
        <tbody>


        <?php
          if (empty($books)) {
            echo "<tr><td colspan='8'>No Books found</td></tr>";
          } else {
            foreach ($books as $row) {
          ?>
              <tr>
                <td><?php echo $row["reservation_id"]; ?></td>
                <td><?php echo $row["book_id"]; ?></td>
                <td>
                <img src="<?php echo Config::indexPath() ?>?action=serveimage&image=<?php echo urlencode(basename($row['cover_page'])); ?>" alt="Book Cover" style="width: 50px; height: 75px; object-fit: cover;">
                </td>
                <td><?php echo $row["title"]; ?></td>
                <td><?php echo $row["reservation_date"]; ?></td>
                <td><?php echo $row["expiration_date"]; ?></td>
               
              </tr>
          <?php
            }
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>