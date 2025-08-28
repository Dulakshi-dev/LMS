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
$pageTitle = "Issued Books";
require_once Config::getViewPath("common", "head.php");
?>

<body onload="loadIssuedBooks();">

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
        <div class="container-fluid w-75">
            <div class="row">
                <nav class="navbar p-md-4 navbar-light bg-light w-100">
                    <div class="d-flex align-items-center w-100 justify-content-between">
                        <span class="mb-0 h5">Issued Books</span>

                        <div class="d-flex align-items-center">
                            <button id="generateReport" class="btn btn-outline-dark me-3 " onclick="generateIssuedBookReport();">
                                <i class="fa fa-print"></i> Generate Report
                            </button>

                            <a href="#" class="text-decoration-none h5">
                                <i class="fa fa-home"></i>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="bg-white ">

                <div class="row m-3">
                    <div class="col-md-4 my-3">
                        <input id="memberid" name="memberid" type="text" class="form-control" placeholder="Enter Member ID">
                    </div>
                    <div class="col-md-4 d-flex my-3">
                        <input id="bookid" name="bookid" type="text" class="form-control mx-3" placeholder="Enter Book ID">
                    </div>
                    <div class="col-md-2 d-flex my-3">
                        <button class="btn btn-primary ml-3 ms-4" onclick="loadIssuedBooks()"><i class="fa fa-search px-2"></i></button>

                    </div>
                    <div class="div col-md-2 d-flex my-3">
                        <select class="form-select" id="statusSelection">
                            <option value="status1">All</option>
                            <option value="status2">Returned</option>
                            <option value="status3">Borrowed</option>
                            <option value="status4">Over Due</option>
                        </select>
                    </div>


                </div>

                <div class="border border-secondary mb-4"></div>
                <div class="px-1 table-responsive">
                    <table class="table" id="issueBookTable">
                        <thead class="thead-light">
                            <tr>
                                <th>BorrowID</th>
                                <th>Book ID</th>
                                <th>Book Name</th>
                                <th>Member ID</th>
                                <th>Member Name</th>
                                <th>Issue Date</th>
                                <th>Return/Due Date</th>
                                <th>Fines</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody id="issueBookTableBody">

                        </tbody>
                    </table>

                </div>
                <div id="pagination"></div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="borrowBookAction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom border-1 border-danger">
                    <h5 class="modal-title">Return Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="d-none" id="borrowId" name="borrowId">
                    <input type="text" class="d-none" id="bookId" name="bookId">
                    <input type="text" class="d-none" id="memberId" name="memberId">
                    <input type="text" class="d-none" id="name" name="name">
                    <input type="text" class="d-none" id="title" name="title">
                    <input type="text" class="d-none" id="email" name="email">
                    <p id="finerate" name="finerate" class="d-none"><?= $fine ?></p>

                    <div class="mb-3 row align-items-center">
                        <label for="dueDate" class="col-sm-4 col-form-label">Due Date</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="dueDate" placeholder="Enter due date">
                        </div>
                        <span id="duedateerror" class="text-danger"></span>

                    </div>
                    <div class="mb-3 row align-items-center">
                        <label for="returnDate" class="col-sm-4 col-form-label">Return Date</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="returnDate" name="returnDate" placeholder="Enter return date" onchange="generateFineFromDOM();">
                        </div>
                        <span id="returndateerror" class="text-danger"></span>

                    </div>
                    <div class="mb-3 row align-items-center">
                        <label for="fines" class="col-sm-4 col-form-label">Fines(Rs)</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="fines" name="fines">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" id="btn" onclick="returnBook();">
                            <span id="btnText">Return Book</span>
                            <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status"></span>
                           
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php require_once Config::getViewPath("staff", "footer.php"); ?>

    <!-- Bootstrap and JavaScript -->
    <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>
    <script src="<?php echo Config::getJsPath("staffCirculation.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>