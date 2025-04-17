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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .nav-bar {
            height: 100%;
        }
    </style>
</head>

<body onload="loadAllCategories(); loadLanguages();">
    <?php include "dash_header.php"; ?>
    <div class="d-flex bg-light">
        <!-- Side Panel -->
        <div class="d-none d-lg-block" style="flex-shrink: 0;">
            <?php include "dash_sidepanel.php"; ?>
        </div>

        <!-- Main Content Area -->
        <div class="box-0 container m-md-5" style="flex-grow: 1;">
            <div class="bg-white p-2">
                <div class="text-center border-bottom border-danger border-4 mb-4 mt-2 pb-3">
                    <h2>Add Books</h2>
                </div>
                <div class="row add-book-form">
                    <div class="col-md-3 text-center mt-5">
                        <div class="mb-3">
                            <img id="book" style="height: 200px; width: 150px;" src="" alt="Cover Page">
                        </div>
                        <div class="mb-3">
                            <input type="file" id="coverpage" name="coverpage" class="form-control" onchange="showImagePreview()">
                            <span id="coverpage-error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-9 px-md-5">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input id="isbn" name="isbn" class="form-control" type="text" placeholder="Enter ISBN">
                                <span id="isbn-error" class="text-danger"></span>
                            </div>
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="author" class="form-label">Author Name</label>
                                <input id="author" name="author" class="form-control" type="text" placeholder="Enter Author Name">
                                <span id="author-error" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Book Title</label>
                            <input id="title" name="title" class="form-control" type="text" placeholder="Enter Book Title">
                            <span id="title-error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="category" class="form-label">Book Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">No Categories</option>
                                </select>
                                <span id="category-error" class="text-danger"></span>
                            </div>
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="language" class="form-label">Language</label>
                                <select class="form-select" id="language" name="language">
                                    <option value="">No languages</option>
                                </select>
                                <span id="language-error" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="pub" class="form-label">Published Year</label>
                                <input id="pub" name="pub" class="form-control" type="text" placeholder="Enter Published Year">
                                <span id="pub-error" class="text-danger"></span>
                            </div>
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="qty" class="form-label">Quantity</label>
                                <input id="qty" name="qty" class="form-control" type="number">
                                <span id="qty-error" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="des" class="form-label">Description</label>
                            <textarea id="des" name="des" class="form-control" rows="3" placeholder="Enter Book Description"></textarea>
                            <span id="des-error" class="text-danger"></span>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary px-5 mb-4 mr-5" onclick="addBook();">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Files -->
    <script src="<?php echo Config::getJsPath("book.js"); ?>"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>