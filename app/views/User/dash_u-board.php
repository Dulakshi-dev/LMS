<?php
require_once "../main.php";
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

  <div class="bg-light rounded p-4 m-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5>Recommended</h5>
      <a href="#" class="text-decoration-none">See All <i class="fa fa-angle-right"></i></a>
    </div>
    <div class="row g-5">
      <div class="col-md-3 col-sm-6">
        <div class="book-card">
          <div class="book-image">
            <img src="<?php echo Config::getImagePath("about.png"); ?>" alt="Book Cover">
          </div>
          <div class="p-3 d-flex justify-content-between align-items-center">
            <div class="x text-start">
              <div class="book-title">James</div>
              <div>Percival Everett</div>
            </div>
            <button class="btn success btn-sm view-details"
              data-title="James"
              data-author="Percival Everett"
              data-id="0000001A"
              data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
              data-rating="4.8">
              View Details
            </button>
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
            <button class="btn success btn-sm view-details"
              data-title="James"
              data-author="Percival Everett"
              data-id="0000001A"
              data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
              data-rating="4.8">
              View Details
            </button>
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
            <button class="btn success btn-sm view-details"
              data-title="James"
              data-author="Percival Everett"
              data-id="0000001A"
              data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
              data-rating="4.8">
              View Details
            </button>
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
            <button class="btn success btn-sm view-details"
              data-title="James"
              data-author="Percival Everett"
              data-id="0000001A"
              data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
              data-rating="4.8">
              View Details
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="bg-light rounded p-4 m-5">
    <div class="row">
      <div class="col-12">
        <h5>Categories</h5>
        <div class="d-flex flex-wrap my-3">
          <button class="btn btn-primary mx-2">All</button>
          <button class="btn btn-outline-primary mx-2">Sci-Fi</button>
          <button class="btn btn-outline-primary mx-2">Fantasy</button>
          <button class="btn btn-outline-primary mx-2">Education</button>
          <button class="btn btn-outline-primary mx-2">Drama</button>
          <button class="btn btn-outline-primary mx-2">Geography</button>
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
                <button class="btn success btn-sm view-details"
                  data-title="James"
                  data-author="Percival Everett"
                  data-id="0000001A"
                  data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                  data-rating="4.8">
                  View Details
                </button>
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
                <button class="btn success btn-sm view-details"
                  data-title="James"
                  data-author="Percival Everett"
                  data-id="0000001A"
                  data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                  data-rating="4.8">
                  View Details
                </button>
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
                <button class="btn success btn-sm view-details"
                  data-title="James"
                  data-author="Percival Everett"
                  data-id="0000001A"
                  data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                  data-rating="4.8">
                  View Details
                </button>
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
                <button class="btn success btn-sm view-details"
                  data-title="James"
                  data-author="Percival Everett"
                  data-id="0000001A"
                  data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                  data-rating="4.8">
                  View Details
                </button>
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
                <button class="btn success btn-sm view-details"
                  data-title="James"
                  data-author="Percival Everett"
                  data-id="0000001A"
                  data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                  data-rating="4.8">
                  View Details
                </button>
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
                <button class="btn success btn-sm view-details"
                  data-title="James"
                  data-author="Percival Everett"
                  data-id="0000001A"
                  data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                  data-rating="4.8">
                  View Details
                </button>
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
                <button class="btn success btn-sm view-details"
                  data-title="James"
                  data-author="Percival Everett"
                  data-id="0000001A"
                  data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                  data-rating="4.8">
                  View Details
                </button>
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
                <button class="btn success btn-sm view-details"
                  data-title="James"
                  data-author="Percival Everett"
                  data-id="0000001A"
                  data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                  data-rating="4.8">
                  View Details
                </button>
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
                <button class="btn success btn-sm view-details"
                  data-title="James"
                  data-author="Percival Everett"
                  data-id="0000001A"
                  data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                  data-rating="4.8">
                  View Details
                </button>
              </div>
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
              <button class="btn success btn-sm view-details" 
                      data-title="James"
                      data-author="Percival Everett"
                      data-id="0000001A"
                      data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                      data-rating="4.8">
                View Details
              </button>
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
                <div class="book-title">hello</div>
                <div>Percival Everett</div>
              </div>
              <button class="btn success btn-sm view-details" 
                      data-title="James"
                      data-author="Percival Everett"
                      data-id="0000001A"
                      data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                      data-rating="4.8">
                View Details
              </button>
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
              <button class="btn success btn-sm view-details" 
                      data-title="James"
                      data-author="Percival Everett"
                      data-id="0000001A"
                      data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                      data-rating="4.8">
                View Details
              </button>
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
              <button class="btn success btn-sm view-details" 
                      data-title="James"
                      data-author="Percival Everett"
                      data-id="0000001A"
                      data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                      data-rating="4.8">
                View Details
              </button>
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
              <button class="btn success btn-sm view-details" 
                      data-title="James"
                      data-author="Percival Everett"
                      data-id="0000001A"
                      data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                      data-rating="4.8">
                View Details
              </button>
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
              <button class="btn success btn-sm view-details" 
                      data-title="James"
                      data-author="Percival Everett"
                      data-id="0000001A"
                      data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                      data-rating="4.8">
                View Details
              </button>
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
              <button class="btn success btn-sm view-details" 
                      data-title="James"
                      data-author="Percival Everett"
                      data-id="0000001A"
                      data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                      data-rating="4.8">
                View Details
              </button>
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
              <button class="btn success btn-sm view-details" 
                      data-title="James"
                      data-author="Percival Everett"
                      data-id="0000001A"
                      data-description="James by Percival Everett is a compelling novel exploring themes of identity, community, and resilience."
                      data-rating="4.8">
                View Details
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="offcanvas offcanvas-end bg-dark text-white" tabindex="-1" id="bookDetailsCanvas" aria-labelledby="bookDetailsCanvasLabel" style="width: 300px;">
    <div class="offcanvas-header">
      <h5 id="bookDetailsCanvasLabel">Book Details</h5>
      <button type="button" class="btn-close bg-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="book-details">
        <img src="" alt="">
        <h5 id="book-title"></h5>
        <p id="book-author"></p>
        <p><strong>ID:</strong> <span id="book-id"></span></p>
        <p><strong>Description:</strong> <span id="book-description"></span></p>
        <p><strong>Rating:</strong> <span id="book-rating"></span></p>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

  <script>
    document.querySelectorAll('.view-details').forEach(button => {
      button.addEventListener('click', function() {
        const title = this.getAttribute('data-title');
        const author = this.getAttribute('data-author');
        const id = this.getAttribute('data-id');
        const description = this.getAttribute('data-description');
        const rating = this.getAttribute('data-rating');

        document.getElementById('book-title').textContent = title;
        document.getElementById('book-author').textContent = author;
        document.getElementById('book-id').textContent = id;
        document.getElementById('book-description').textContent = description;
        document.getElementById('book-rating').textContent = rating;

        const offcanvas = new bootstrap.Offcanvas(document.getElementById('bookDetailsCanvas'));
        offcanvas.show();
      });
    });
  </script>

</body>

</html>