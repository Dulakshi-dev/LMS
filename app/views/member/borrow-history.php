<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    .modal-header {
      border-bottom: 1px solid #ddd;
    }

    .btn-close {
      font-size: 1.2rem;
    }

    .entries {
      margin-top: 100px;
    }

    .box {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1000;
    }

    .card {
      background-color: #fff;
    }

    .error {
      color: red;
      font-size: 0.875rem;
    }
  </style>
</head>

<body class="bg-light" onload="loadBorrowBooks();">
  <?php require_once Config::getViewPath("member", "header.php"); ?>

  <div class="d-flex">
    <!-- Side Panel -->
    <div class="nav-bar d-none d-md-block">
      <?php require_once Config::getViewPath("member", "sidepanel.php"); ?>
    </div>
    <div class="container bg-white mt-4 p-4 rounded shadow-sm vh-100">
      <!-- Header -->
      <div class="d-flex justify-content-end align-items-center mb-4">
        <a href="#" class="page-link">
          <i class="fa fa-home"></i> Home
        </a>
      </div>

      <!-- Search Bar -->
      <div class="input-group mb-5">
        <input id="bookid" type="text" class="form-control" placeholder="Type Book ID" aria-label="Book ID">
        <input id="title" type="text" class="form-control mx-2" placeholder="Type Book Name" aria-label="Book Name">
        <input id="category" type="text" class="form-control" placeholder="Type Category" aria-label="Category">
        <button class="btn btn-primary ms-2" onclick="loadBorrowBooks();">
          <i class="fa fa-search"></i> Search
        </button>
      </div>

      <!-- Table -->
      <table class="table table-bordered text-center">
        <thead class="table-light">
          <tr>
            <th>Borrow ID</th>
            <th>Book ID</th>
            <th>Book</th>
            <th>Book Name</th>
            <th>Issued Date</th>
            <th>Date Due</th>
            <th>Date Returned</th>
          </tr>
        </thead>
        <tbody id="bookTableBody">

        </tbody>
      </table>

      <!-- Pagination -->
      <div class="d-flex justify-content-between align-items-center entries offset-5">

      <div id="pagination"></div>

      </div>
    </div>
  </div>


  <!-- JavaScript -->
  <!-- <script>
    document.querySelectorAll('.notify-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelector('.box').style.display = 'flex';
      });
    });

    document.querySelector('.btn-close').addEventListener('click', () => {
      document.querySelector('.box').style.display = 'none';
    });

    function validateForm() {
      let isValid = true;

      // Clear previous error messages
      document.querySelectorAll('.error').forEach(error => {
        error.textContent = '';
      });

      // From email validation
      const emailFrom = document.getElementById('emailFrom').value;
      if (!emailFrom) {
        document.getElementById('fromError').textContent = 'From email is required.';
        isValid = false;
      }

      // To email validation
      const emailTo = document.getElementById('emailTo').value;
      if (!emailTo) {
        document.getElementById('toError').textContent = 'To email is required.';
        isValid = false;
      }

      // Subject validation
      const emailSubject = document.getElementById('emailSubject').value;
      if (!emailSubject) {
        document.getElementById('subjectError').textContent = 'Subject is required.';
        isValid = false;
      }

      // Message validation
      const emailMessage = document.getElementById('emailMessage').value;
      if (!emailMessage) {
        document.getElementById('messageError').textContent = 'Message is required.';
        isValid = false;
      }

      return isValid;
    }
  </script> -->

  <!-- Bootstrap JS -->
  <script src="<?php echo Config::getJsPath("borrowHistory.js"); ?>"></script>
  <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>