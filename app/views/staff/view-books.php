<?php

if (!isset($_SESSION['staff'])) {
    header("Location: index.php");
    exit;
}

// Session Timeout (30 minutes)
if (isset($_SESSION['staff']['last_activity']) && (time() - $_SESSION['staff']['last_activity'] > 1800)) {
    session_unset();  // Clear session data
    session_destroy();
    header("Location: index.php");
    exit;
}

// Reset last activity time (only if user is active)
$_SESSION['staff']['last_activity'] = time();
?>


<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Books";
require_once Config::getViewPath("common","head.php");
?>

<body onload="loadBooks(1,'Active');">
    <?php include "dash_header.php"; ?>

    <div class="d-flex bg-light">
        <div>
        <div class="h-100 d-none d-lg-block">
                <?php include "dash_sidepanel.php"; ?>
            </div>

            <div class="h-100 d-block d-lg-none">
                <?php include "small_sidepanel.php"; ?>
            </div>

        </div>
        <div class="container-fluid w-75 ">

            <div class="row">
                <nav class="navbar p-md-4 navbar-light bg-light w-100">
                    <div class="d-flex align-items-center w-100 justify-content-between">
                        <span class="mb-0 h5">Active Books</span>

                        <div class="d-flex align-items-center">
                            <button id="generateReport" class="btn btn-outline-dark me-3" onclick="generateActiveBookReport();">
                                <i class="fa fa-print"></i> Generate Report
                            </button>

                            <a href="#" class="text-decoration-none h5">
                                <i class="fa fa-home"></i>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="bg-white">

                <div class="row align-items-center">
                    <div class="col-5 col-md-5 d-flex justify-content-end mt-3 ms-5">
                        <select class="form-select" id="category1" onchange="loadBooks(1, 'Active');">
                            <option value="">...</option>
                        </select>
                    </div>
                    <div class="col-5 col-md-5 d-flex justify-content-end mt-3">
                        <select class="form-select" id="language1" onchange="loadBooks(1, 'Active');">
                            <option value="">...</option>
                        </select>
                    </div>
                </div>

                <div class="row m-3">
                    <div class="col-md-4 ">
                        <input id="bookid" name="bookid" type="text" class="form-control" placeholder="Type Book ID">
                    </div>
                    <div class="col-md-4 d-flex ">
                        <input id="bname" name="title" type="text" class="form-control" placeholder="Type Book Name">
                    </div>
                    <div class="col-md-4 d-flex ">
                        <input id="isbn" name="isbn" type="text" class="form-control" placeholder="Type ISBN">
                        <button class="btn btn-primary ml-3 px-4 ms-2" onclick="loadBooks(1,'Active');"><i class="fa fa-search"></i></button>
                    </div>
                </div>

                <div class="border border-secondary mb-4"></div>
                <div class="px-1 table-responsive">
                    <table class="table" id="bookTable">
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
    </div>

    <!--edit book-->
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
                                <select class="form-select" id="category2">
                                    <option value="">...</option>
                                </select>
                                <span id="category_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="language" class="form-label">Language</label>
                                <select class="form-select" id="language2">
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
    <?php require_once Config::getViewPath("staff", "footer.php"); ?>


    <!-- Bootstrap and JavaScript -->
    <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>

    <script src="<?php echo Config::getJsPath("staffBook.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>