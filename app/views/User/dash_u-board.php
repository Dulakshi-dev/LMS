<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bookstore</title>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    rel="stylesheet"
  />
  <style>
    .book-card {
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      overflow: hidden;
      transition: transform 0.3s ease;
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
    .success{
      color: rgba(21, 83, 28, 1);
      background: rgba(185, 247, 192, 1);
    }
    .success:hover{
      background: rgb(69, 161, 80);
    }

  </style>
</head>
<body class="">

  <!-- Recommended Section -->
  <div class="containe bg-light rounded p-4 m-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5>Recommended</h5>
      <a href="#" class="text-decoration-none">See All <i class="fa fa-angle-right"></i></a>
    </div>
    <div class="row g-5">
      <div class="col-md-3 col-sm-6">
        <div class="book-card">
          <div class="book-image">
            <img src="../../../public/images/about.png" alt="Book Cover">
          </div>
          <div class="p-3 d-flex justify-content-between align-items-center">
            <div class="x text-start">
              <div class="book-title">James</div>
              <div>Percival Everett</div>
            </div>
            <button class="btn success btn-sm">View Details</button>
          </div>          
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="book-card">
          <div class="book-image">
            <img src="../../../public/images/about.png" alt="Book Cover">
          </div>
          <div class="p-3 d-flex justify-content-between align-items-center">
            <div class="x text-start">
              <div class="book-title">James</div>
              <div>Percival Everett</div>
            </div>
            <button class="btn success btn-sm">View Details</button>
          </div>          
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="book-card">
          <div class="book-image">
            <img src="../../../public/images/about.png" alt="Book Cover">
          </div>
          <div class="p-3 d-flex justify-content-between align-items-center">
            <div class="x text-start">
              <div class="book-title">James</div>
              <div>Percival Everett</div>
            </div>
            <button class="btn success btn-sm">View Details</button>
          </div>          
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="book-card">
          <div class="book-image">
            <img src="../../../public/images/about.png" alt="Book Cover">
          </div>
          <div class="p-3 d-flex justify-content-between align-items-center">
            <div class="text-start">
              <div class="book-title">James</div>
              <div>Percival Everett</div>
            </div>
            <button class="btn success btn-sm">View Details</button>
          </div>          
        </div>
      </div>
      <!-- Repeat for other books -->
    </div>
  </div>

  <!-- Categories Section -->
  <div class="m-5 bg-light p-4 rounded">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="mb-4">Categories</h5>
      <a href="" class="btn btn-outline-primary mx-4"><i class='fas fa-bars'></i></a>
    </div>
    <div class="d-flex flex-wrap my-4">
      <button class="btn btn-primary mx-3">All</button>
      <button class="btn btn-outline-primary mx-3">Sci-Fi</button>
      <button class="btn btn-outline-primary mx-3">Fantasy</button>
      <button class="btn btn-outline-primary mx-3">Education</button>
      <button class="btn btn-outline-primary mx-3">Drama</button>
      <button class="btn btn-outline-primary mx-3">Geography</button>
    </div>
    <div class="row g-5">
      <div class="col-md-4 col-sm-6">
        <div class="book-card">
          <div class="book-image">
            <img src="../../../public/images/about.png" alt="Book Cover">
          </div>
          <div class="p-3 d-flex justify-content-between align-items-center">
            <div class="text-start">
              <div class="book-title">James</div>
              <div>Percival Everett</div>
            </div>
            <button class="btn success btn-sm">View Details</button>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="book-card">
          <div class="book-image">
            <img src="../../../public/images/about.png" alt="Book Cover">
          </div>
          <div class="p-3 d-flex justify-content-between align-items-center">
            <div class="text-start">
              <div class="book-title">James</div>
              <div>Percival Everett</div>
            </div>
            <button class="btn success btn-sm">View Details</button>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="book-card">
          <div class="book-image">
            <img src="../../../public/images/about.png" alt="Book Cover">
          </div>
          <div class="p-3 d-flex justify-content-between align-items-center">
            <div class="text-start">
              <div class="book-title">James</div>
              <div>Percival Everett</div>
            </div>
            <button class="btn success btn-sm">View Details</button>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="book-card">
          <div class="book-image">
            <img src="../../../public/images/about.png" alt="Book Cover">
          </div>
          <div class="p-3 d-flex justify-content-between align-items-center">
            <div class="text-start">
              <div class="book-title">James</div>
              <div>Percival Everett</div>
            </div>
            <button class="btn success btn-sm">View Details</button>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="book-card">
          <div class="book-image">
            <img src="../../../public/images/about.png" alt="Book Cover">
          </div>
          <div class="p-3 d-flex justify-content-between align-items-center">
            <div class="text-start">
              <div class="book-title">James</div>
              <div>Percival Everett</div>
            </div>
            <button class="btn success btn-sm">View Details</button>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="book-card">
          <div class="book-image">
            <img src="../../../public/images/about.png" alt="Book Cover">
          </div>
          <div class="p-3 d-flex justify-content-between align-items-center">
            <div class="text-start">
              <div class="book-title">James</div>
              <div>Percival Everett</div>
            </div>
            <button class="btn success btn-sm">View Details</button>
          </div>
        </div>
      </div>
      
      <!-- Repeat for other books -->
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
