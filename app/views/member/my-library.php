<?php
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
    </style>
</head>

<body>
    <?php require_once Config::getViewPath("member", "header.php"); ?>

    <div class="d-flex">
        <div class="nav-bar">
            <?php require_once Config::getViewPath("member", "sidepanel.php"); ?>
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
                <input type="text" class="form-control" placeholder="Type Book ID" aria-label="Book ID">
                <input type="text" class="form-control mx-3" placeholder="Type Book Name" aria-label="Book Name">
                <input type="text" class="form-control" placeholder="Type Category" aria-label="Category">
                <button class="btn btn-primary ms-2">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>

            <?php
            if (empty($books)) {
                echo "<tr><td colspan='7'>No Books found</td></tr>";
            } else {
                foreach ($books as $row) {
            ?>
                    <div class="row m-4 border rounded d-flex align-items-center">
                        <!-- Book Image -->
                        <div class="col-md-4 mb-4 mb-md-0 p-2 d-flex justify-content-center align-items-center">
                            <div class="book-card text-center position-relative d-flex flex-column align-items-center">
                                <!-- Bookmark Icon -->
                                <a href="index.php?action=unsave&book_id=<?php echo $row["book_id"]; ?>" class="position-absolute top-0 end-0 mx-1">
                                    <i class="fa fa-bookmark text-warning fs-5"></i>
                                </a>
                                <img class="book-cover img-fluid" src="<?php echo Config::indexPath() ?>?action=serveimage&image=<?php echo urlencode(basename($row['cover_page'])); ?>" alt="Book Cover">

                                <h2 class="book-title mt-3"><?php echo $row["title"]; ?></h2>
                                <p class="book-author text-muted"><?php echo $row["author"]; ?></p>
                            </div>
                        </div>
                        <div class="col-md-8 p-5">
                            <h2 class="text-danger">Book ID : <?php echo $row["book_id"]; ?></h2>
                            <p class="col-10 text-justify mt-3">
                                <?php echo $row["description"]; ?>
                            </p>
                            <p class="text-danger">
                                <?php
                                if ($row["available_qty"] > 0) {
                                ?>
                            <p class="text-success fw-bold">Available</p>
                        <?php
                                } else {
                        ?>

                            <p class="text-danger fw-bold">Not Available</p>
                        <?php
                                }
                        ?>
                        </p>

                        <button class="btn" id="success"
                            onclick="window.location.href='<?php echo Config::indexPathMember(); ?>?action=reserve&book_id=<?php echo $row['book_id']; ?>&member_id=<?php echo $_SESSION['member']['member_id']; ?>'">
                            Reserve
                        </button>
                        </div>
                    </div>
            <?php


                }
            }
            ?>

            <div class="d-flex justify-content-between align-items-center entries offset-5">

                <nav aria-label="Page navigation example" class="">
                    <ul class="pagination d-flex justify-content-center">
                        <!-- Previous Button -->
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= Config::indexPathMember() ?>?action=savedbooks&page=<?= max(1, $page - 1) ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <!-- Page Numbers -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="<?= Config::indexPathMember() ?>?action=savedbooks&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next Button -->
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= Config::indexPathMember() ?>?action=savedbooks&page=<?= min($totalPages, $page + 1) ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
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