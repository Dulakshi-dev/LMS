
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

$book_id = isset($_POST['book_id']) ? htmlspecialchars($_POST['book_id']) : '';
$member_id = isset($_POST['member_id']) ? htmlspecialchars($_POST['member_id']) : '';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Update</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <?php include "dash_header.php"; ?>
    <div class="d-flex">


    <div>
            <!-- Large and Medium Screens -->
            <div class="d-none d-md-block">
                <?php include "dash_sidepanel.php"; ?>
            </div>

            <!-- Small Screens Only -->
            <div class="d-block d-md-none">
                <?php include "small_sidepanel.php"; ?>
            </div>
        </div>
        <div class="form w-100 bg-light px-5 ">
            <!-- Navbar -->
            <nav class="navbar py-4 navbar-light bg-light">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1">
                        Issue Book
                    </span>
                    <a href="#" class="text-decoration-none h5">
                        <i class="fa fa-home"></i> Home
                    </a>
                </div>
            </nav>

            <!-- Update Details Section -->
            <div id="box1">
                <div class="container-fluid fw-bold bg-white d-flex justify-content-center">
                    <div class="row m-4 w-100">
                        <!-- Title -->
                        <div class="text-center border-bottom border-danger border-4">
                            <h2 class="p-2">Issue Book</h2>
                        </div>
                        <div class="row">


                            <!-- Update Form -->
                            <div>
                                    <!-- Name -->
                                    <div class="row my-4 gap-5">
                                        <div class="col-lg-5 form-group">
                                            <label for="book_id">Books ID</label>
                                            <input id="book_id" name="book_id" class="form-control" type="text" value="<?php echo $book_id; ?>" onchange="loadBookData();">
                                            <span id="book_id_error" class="text-danger"></span>
                                        </div>

                                        <div class="col-lg-5 form-group">
                                            <label for="member_id">Member ID</label>
                                            <input id="member_id" name="member_id" class="form-control" type="text" value="<?php echo $member_id; ?>" onchange="loadMemberData();">
                                            <span id="member_id_error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <!-- Address -->
                                    <div class="row my-3 gap-5">
                                        <div class="form-group col-lg-5">
                                            <label for="isbn">ISBN</label>
                                            <input id="isbn" class="form-control" type="text" disabled>
                                        </div>
                                        <div class="col-lg-5 form-group">
                                            <label for="nic">NIC</label>
                                            <input id="nic" class="form-control" type="text" disabled>
                                        </div>
                                    </div>
                                    <div class="row my-3 gap-5">
                                        <div class="col-lg-5 form-group">
                                            <label for="title">Book Title</label>
                                            <input id="title" class="form-control" type="text" disabled>
                                        </div>
                                        <div class="col-lg-5 form-group">
                                            <label for="memName">Member Name</label>
                                            <input id="memName" class="form-control" type="text" disabled>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-10 form-group">
                                            <label for="author">Author</label>
                                            <input id="author" class="form-control" type="text" disabled>
                                        </div>
                                    </div>

                                    <!-- NIC and DOB -->
                                    <div class="row gap-5">
                                        <div class="col-lg-5 my-3 form-group">
                                            <label for="issueDate">Date Issued</label>
                                            <input id="issueDate" name="borrow_date" class="form-control" type="date">
                                            <span id="issueDate_error" class="text-danger"></span>
                                        </div>
                                        <div class="col-lg-5 my-3 form-group">
                                            <label for="returnDate">Due Date</label>
                                            <input id="returnDate" name="due_date" class="form-control" type="date">
                                            <span id="returnDate_error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div>
                                        <img src="" alt="">
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary px-4" type="button" onclick="issueBook();">Issue Book</button>
                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
    // Check if book_id and member_id are not empty before calling functions
    if (document.getElementById("book_id").value.trim() !== "") {
        loadBookData();
    }
    if (document.getElementById("member_id").value.trim() !== "") {
        loadMemberData();
    }
});

    </script>



    <script src="<?php echo Config::getJsPath("borrow.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>