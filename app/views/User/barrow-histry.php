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
      font-size: 1.5rem;
    }
    .btn-send {
      background-color: #4c00ff; /* Blue color for the send button */
      color: #fff;
    }
    .btn-send:hover {
      background-color: #3a00cc;
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
  </style>
</head>
<body class="bg-light">
  <div class="container bg-white mt-4 p-4 rounded shadow-sm vh-100">
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
          <th>Book ID</th>
          <th>Book</th>
          <th>Book Name</th>
          <th>Issued Date</th>
          <th>Date Due</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>001</td>
          <td><img src="../../../public/images/about.png" style="width: 100px;" alt="Book Cover"></td>
          <td>Quicksilver</td>
          <td>2025-01-01</td>
          <td>2025-01-15</td>
          <td>
            <button class="btn btn-warning notify-btn">
              <i class="fa fa-envelope"></i> Notify
            </button>
          </td>
        </tr>
        <tr>
          <td>002</td>
          <td><img src="../../../public/images/about.png" style="width: 100px;" alt="Book Cover"></td>
          <td>Inferno</td>
          <td>2025-01-10</td>
          <td>2025-01-25</td>
          <td>
            <button class="btn btn-warning notify-btn">
              <i class="fa fa-envelope"></i> Notify
            </button>
          </td>
        </tr>
        <tr>
          <td colspan="6">No more books found.</td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center entries">
      <div>
        Showing 1 to 20 of 40 entries
      </div>
      <nav aria-label="Page navigation">
        <ul class="pagination mb-0">
          <li class="page-item"><a class="page-link" href="#">Previous</a></li>
          <li class="page-item active"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
      </nav>
    </div>
  </div>

  <!-- Email Form -->
  <div class="box">
    <div class="container d-flex justify-content-center align-items-center vh-100">
      <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%;">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="mb-0">Send Email</h5>
          <button type="button" class="btn-close" aria-label="Close"></button>
        </div>

        <!-- Form -->
        <form>
          <!-- From -->
          <div class="mb-3">
            <label for="emailFrom" class="form-label">From</label>
            <input type="email" class="form-control" id="emailFrom" placeholder="Enter your email">
          </div>

          <!-- To -->
          <div class="mb-3">
            <label for="emailTo" class="form-label">To</label>
            <input type="email" class="form-control" id="emailTo" placeholder="Enter recipient's email">
          </div>

          <!-- Subject -->
          <div class="mb-3">
            <label for="emailSubject" class="form-label">Subject</label>
            <input type="text" class="form-control" id="emailSubject" placeholder="Enter email subject">
          </div>

          <!-- Message -->
          <div class="mb-3">
            <label for="emailMessage" class="form-label">Message</label>
            <textarea class="form-control" id="emailMessage" rows="4" placeholder="Type your message"></textarea>
          </div>

          <!-- Send Button -->
          <div class="text-end">
            <button type="submit" class="btn btn-send">Send</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    document.querySelectorAll('.notify-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelector('.box').style.display = 'flex';
      });
    });

    document.querySelector('.btn-close').addEventListener('click', () => {
      document.querySelector('.box').style.display = 'none';
    });
  </script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
