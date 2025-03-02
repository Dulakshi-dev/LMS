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


        .book-title {
            font-size: 18px;
            margin: 5px 0 0px;
        }

        .book-author {
            font-size: 14px;
            color: gray;
        }
    </style>
</head>

<body>

    <div class="container pt-4">
        <!-- Home Link -->
        <div class="d-flex justify-content-end align-items-center mb-4">
            <a href="#" class="page-link">
                <i class="fa fa-home"></i> Home
            </a>
        </div>

        <!-- Search Bar -->
        <div class="input-group mb-5">
            <input type="text" class="form-control" placeholder="Type Book ID" aria-label="Book ID">
            <input type="text" class="form-control mx-3" placeholder="Type Book Name" aria-label="Book Name">
            <input type="text" class="form-control" placeholder="Type Category" aria-label="Category">
            <button class="btn btn-primary ms-2">
                <i class="fa fa-search"></i> Search
            </button>
        </div>

        <!-- Book Information -->
        <div class="row my-5 border rounded">
            <!-- Book Image -->
            <div class="col-md-4 mb-4 mb-md-0 p-4 d-flex justify-content-center align-items-center">
                <div class="book-card text-center position-relative d-flex flex-column align-items-center">
                    <!-- Bookmark Icon -->
                    <span class="position-absolute top-0 end-0 mx-1">
                        <i class="fa fa-bookmark text-warning fs-5"></i>
                    </span>

                    <img src="../../../public/images/about.png" alt="James by Percival Everett" class="book-cover img-fluid">
                    <h2 class="book-title mt-3">James</h2>
                    <p class="book-author text-muted">Percival Everett</p>
                </div>
            </div>



            <!-- Book Details -->
            <div class="col-md-8 p-5">
                <h2 class="text-danger">ID 0000001A</h2>
                <p>
                    "James" by Percival Everett, the 2012 Barnes & Noble Book of the Year and National Book Award winner,
                    is a reimagining of Mark Twain's Adventures of Huckleberry Finn.
                    The story shifts the focus to Jim, the enslaved, literate individual who has used intelligence to service.
                    The novel explores his journey to Illinois to free his family,
                    offering a profound critique of systemic racism and expanding on Jim's character in a deeply moving narrative.
                </p>

                <!-- Star Rating -->
                <div class="d-flex fs-4 pt-4">
                    <span class="fa fa-star" data-value="1"></span>
                    <span class="fa fa-star" data-value="2"></span>
                    <span class="fa fa-star" data-value="3"></span>
                    <span class="fa fa-star" data-value="4"></span>
                    <span class="fa fa-star" data-value="5"></span>
                </div>
            </div>
        </div>
    </div>




    <script>
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
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>