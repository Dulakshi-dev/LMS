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

<body>

    <?php include "dash_header.php"; ?>



    <div class="d-flex bg-light">
        <div class="nav-bar vh-100">
            <?php include "dash_sidepanel.php"; ?>
        </div>
        <div class="container-fluid bg-white m-5">
            <form method="POST" action="<?php echo Config::indexPath() ?>?action=searchBorrowBooks">

                <div class="row m-3">
                    <div class="col-md-6 my-3">
                        <input id="memberid" name="memberid" type="text" class="form-control" placeholder="Enter Member ID">
                    </div>
                    <div class="col-md-6 d-flex my-3">
                        <input id="bookid" name="bookid" type="text" class="form-control mx-3" placeholder="Enter Book ID">
                        <button class="btn btn-primary ml-3 px-4"><i class="fa fa-search px-2"></i></button>
                    </div>
                </div>
            </form>

            <div class="border border-secondary mb-4"></div>

            <div class="px-1">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>BorrowID</th>
                            <th>Book ID</th>
                            <th>Book Name</th>
                            <th>Member ID</th>
                            <th>Member Name</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Fines</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($books)) {
                            echo "<tr><td colspan='7'>No Books found</td></tr>";
                        } else {

                            foreach ($books as $row) {
                                $return_date = $row["return_date"];

                        ?>
                                <tr>
                                    <td><?php echo $row["borrow_id"]; ?></td>
                                    <td><?php echo $row["book_id"]; ?></td>
                                    <td><?php echo $row["title"]; ?></td>
                                    <td><?php echo $row["member_id"]; ?></td>
                                    <td><?php echo $row["fname"] . " " . $row["lname"]; ?></td>
                                    <td><?php echo $row["borrow_date"]; ?></td>
                                    <td><?php echo $row["due_date"]; ?></td>
                                    <td><?php echo $row["return_date"]; ?></td>
                                    <td><?php echo $row["amount"]; ?></td>


                                    <td><?php
                                        if ($return_date == NULL) {
                                        ?>
                                            <div class="m-1">
                                                <button class="btn btn-success my-1 btn-sm" 
                                                data-due-date="<?php echo $row["due_date"]; ?>" 
                                                data-borrow-id="<?php echo $row["borrow_id"]; ?>" 
                                                data-book-id="<?php echo $row["book_id"]; ?>"
                                                data-memberId="<?php echo $row["id"]; ?>" onclick="returnButtonClick(this)" data-bs-toggle="modal" data-bs-target="#borrowBookAction">
                                                    <i class="fas fa-edit"></i></span>
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <p class="text-danger">Book Returned</p>
                                        <?php

                                        }
                                        ?>


                                    </td>
                                </tr>
                        <?php
                            }
                        }

                        ?>
                    </tbody>
                </table>

            </div>
            <nav aria-label="Page navigation example" class="">
                <ul class="pagination d-flex justify-content-center">
                    <!-- Previous Button -->
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= Config::indexPath() ?>?action=viewissuebook&page=<?= max(1, $page - 1) ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= Config::indexPath() ?>?action=viewissuebook&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next Button -->
                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= Config::indexPath() ?>?action=viewissuebook&page=<?= min($totalPages, $page + 1) ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
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
                    <form action="<?php echo Config::indexPath() ?>?action=returnbook" method="POST" onsubmit="return validateReturnForm();">
                        <input type="text" class="d-none" id="borrowId" name="borrowId">
                        <input type="text" class="d-none" id="bookId" name="bookId">
                        <input type="text" class="d-none" id="memberId" name="memberId">


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
                                <input type="date" class="form-control" id="returnDate" name="returnDate" placeholder="Enter return date" onchange="generateFine();">
                            </div>
                            <span id="returndateerror" class="text-danger"></span>

                        </div>
                        <div class="mb-3 row align-items-center">
                            <label for="amount" class="col-sm-4 col-form-label">Fines(Rs)</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="fines" name="fines">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Return Book</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap and JavaScript -->
    <script src="<?php echo Config::getJsPath("borrow.js"); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>