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
          <th>Book ID</th>
          <th>Book</th>
          <th>Book Name</th>
          <th>Issued Date</th>
          <th>Date Due</th>
          <th>returnDate</th>
          <th>Fine</th>
        </tr>
      </thead>
      <tbody>
        
        
        <tr>
          <td colspan="6">No more books found.</td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
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

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

