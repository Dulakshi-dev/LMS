<?php
require_once "../../main.php";
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

<body onload="loadBooks();">
    <?php include "dash_header.php"; ?>

    <div class="d-flex bg-light">
        <div class="nav-bar vh-100">
            <?php include "dash_sidepanel.php"; ?>
        </div>
        <div class="container-fluid bg-white m-5">

                <div class="row m-3">
                    <div class="col-md-4 my-3">
                        <input id="bookid" name="bookid" type="text" class="form-control" placeholder="Type Book ID">
                    </div>
                    <div class="col-md-4 d-flex my-3">
                        <input id="bname" name="title" type="text" class="form-control" placeholder="Type Book Name">
                    </div>
                    <div class="col-md-4 d-flex my-3">
                        <input id="isbn" name="isbn" type="text" class="form-control" placeholder="Type ISBN">
                        <button class="btn btn-primary ml-3 px-4 ms-2" onclick="loadBooks();"><i class="fa fa-search"></i></button>
                    </div>
                </div>
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
                            <th>Language</th>
                            <th>Qty</th>
                            <th>Borrowed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="bookTableBody">
                        
                    </tbody>
                </table>

            </div>
            <div id="pagination"></div>
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
                                <span id="book_id_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input id="isbn_no" class="form-control" type="text" placeholder="Enter ISBN" />
                                <span id="isbn_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="title" class="form-label">Book Title</label>
                                <input id="title" class="form-control" type="text" placeholder="Enter Book Title" />
                                <span id="title_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Author Name</label>
                            <input id="author" class="form-control" type="text" placeholder="Enter Author Name" />
                            <span id="author_error" class="text-danger"></span> <!-- Error message span -->
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="category" class="form-label">Book Category</label>
                                <select class="form-select" id="category">
                                    <option value="">...</option>
                                </select>
                                <span id="category_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="language" class="form-label">Language</label>
                                <select class="form-select" id="language">
                                    <option value="">...</option>
                                </select>
                                <span id="language_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="pub_year" class="form-label">Published Year</label>
                                <input id="pub_year" class="form-control" type="text" placeholder="Enter Publisher" />
                                <span id="pub_year_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="qty" class="form-label">Quantity</label>
                                <input id="qty" class="form-control" type="number" />
                                <span id="qty_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="des" class="form-label">Description</label>
                            <textarea id="des" class="form-control" rows="3" placeholder="Enter Book Description"></textarea>
                            <span id="des_error" class="text-danger"></span> <!-- Error message span -->
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
    <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>

    <script src="<?php echo Config::getJsPath("book.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>