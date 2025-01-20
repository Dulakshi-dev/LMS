<?php
require_once "../main.php";


require_once Config::getControllerPath("usercontroller.php");

$userController = new UserController();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php include "dash_header.php"; ?>

    <div class="d-flex bg-light">
        <div class="nav-bar vh-100">
            <?php include "dash_sidepanel.php"; ?>
        </div>
        <div class="container-fluid bg-white m-5">
            <form method="POST" action="<?php echo Config::indexPath() ?>?action=searchBooks">

                <div class="row m-3">
                    <div class="col-md-4 my-3">
                        <input id="bid" name="bid" type="text" class="form-control" placeholder="Type Book ID">
                    </div>
                    <div class="col-md-4 d-flex my-3">
                        <input id="bname" name="title" type="text" class="form-control" placeholder="Type Book Name">
                    </div>
                    <div class="col-md-4 d-flex my-3">
                        <input id="isbn" name="isbn" type="text" class="form-control" placeholder="Type ISBN">
                        <button class="btn btn-primary ml-3 px-4 ms-2"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
            <div class="border border-secondary mb-4"></div>
            <div class="px-1">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>Book ID</th>
                            <th>ISBN</th>
                            <th>Cover Page</th>
                            <th>Book Name</th>
                            <th>Author</th>
                            <th>Published Year</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Borrowed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($books)) {
                            echo "<tr><td colspan='7'>No Books found</td></tr>";
                        } else {

                            foreach ($books as $row) {

                                if ($row["status"] == 'Active') {

                        ?>
                                    <tr>
                                        <td><?php echo $row["book_id"]; ?></td>
                                        <td><?php echo $row["isbn"]; ?></td>
                                        <td>
                                            <img src="<?php echo Config::indexPath() ?>?action=serveimage&image=<?php echo urlencode(basename($row['cover_page'])); ?>" alt="Book Cover" style="width: 50px; height: 75px; object-fit: cover;">

                                        </td>
                                        <td><?php echo $row["title"]; ?></td>
                                        <td><?php echo $row["author"]; ?></td>
                                        <td><?php echo $row["pub_year"]; ?></td>
                                        <td><?php echo $row["category_name"]; ?></td>
                                        <td><?php echo $row["qty"]; ?></td>
                                        <td><?php echo $row["qty"] - $row["available_qty"]; ?></td>
                                        <td>
                                            <div class="m-1">
                                                <span class="btn btn-success my-1 btn-sm" data-bs-toggle="modal" data-bs-target="#updateBookDetailsModal" onclick="loadBookDataUpdate('<?php echo $row['book_id']; ?>'); loadAllCategories();"><i class="fas fa-edit"></i></span>
                                                <span class="btn btn-danger my-1 btn-sm"><i class="fas fa-trash-alt"></i></span>
                                            </div>
                                        </td>
                                    </tr>


                        <?php
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>

            </div>
            <nav aria-label="Page navigation example" class="" >
                <ul class="pagination d-flex justify-content-center">
                    <!-- Previous Button -->
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= Config::indexPath() ?>?action=viewBook&page=<?= max(1, $page - 1) ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= Config::indexPath() ?>?action=viewBook&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next Button -->
                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= Config::indexPath() ?>?action=viewBook&page=<?= min($totalPages, $page + 1) ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>



    <div class="modal fade" id="updateBookDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Books</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="book_id" class="form-label">Book ID</label>
                                <input id="book_id" class="form-control" type="text" disabled />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input id="isbn_no" class="form-control" type="text" placeholder="Enter ISBN" />
                            </div>
                            <div class="col-lg-6 col-sm-12 mb-3">

                                <label for="title" class="form-label">Book Title</label>
                                <input id="title" class="form-control" type="text" placeholder="Enter Book Title" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Author Name</label>
                            <input id="author" class="form-control" type="text" placeholder="Enter Author Name" />
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="category" class="form-label">Book Category</label>
                                <select class="form-select" id="category">
                                    <option value="">...</option>

                                </select>
                            </div>
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="pub_year" class="form-label">Published Year</label>
                                <input id="pub_year" class="form-control" type="text" placeholder="Enter Publisher" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="qty" class="form-label">Quantity</label>
                                <input id="qty" class="form-control" type="number" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="des" class="form-label">Description</label>
                            <textarea id="des" class="form-control" rows="3" placeholder="Enter Book Description"></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" onclick="updateBookDetails();">Update Book Details</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap and JavaScript -->

    <script src="<?php echo Config::getJsPath("book.js"); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>