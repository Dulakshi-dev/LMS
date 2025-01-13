<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <div class="container mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-end align-items-center mb-4">
      <a href="#" class="btn btn-light">
      <i class='fa fa-home'></i>
      </a>
    </div>

    <!-- Search Bar -->
    <div class="input-group d-flex justify-content-between">
      <input type="text" class="form-control m-3" placeholder="Type Book ID" aria-label="Book ID">
      <input type="text" class="form-control m-3" placeholder="Type Book Name" aria-label="Book Name">
      <input type="text" class="form-control m-3" placeholder="Type Category" aria-label="Category">
      <button class="btn btn-primary m-3 px-3 align-item-center d-flex"><i class='fa fa-search'></i></button>
    </div>

    <!-- Table -->
    <table class="table table-bordered text-center align-middle">
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
          <td><img src="https://via.placeholder.com/50x70" alt="Book Cover" class="img-fluid"></td>
          <td>Quicksilver</td>
          <td>2025-01-01</td>
          <td>2025-01-15</td>
          <td>
            <button class="btn btn-warning">
              <img src="https://img.icons8.com/material-rounded/20/000000/send.png" alt="Action">
            </button>
          </td>
        </tr>
        <tr>
          <td colspan="6">No more books found.</td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center">
        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#">Next</a></li>
      </ul>
    </nav>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
