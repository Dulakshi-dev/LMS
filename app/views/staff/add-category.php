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
</head>

<body onload="loadCategory();">
    <?php require_once Config::getViewPath("staff", "dash_header.php"); ?>

    <div class="d-flex bg-light">
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
        <div class="box-0 container my-5 ">
            <div class="bg-white vh-100">
                <div class="text-center border-bottom border-danger border-4 mb-4 pb-3">
                    <h2>Add New Category</h2>
                </div>
                <div class="col-8 offset-2">
                    <div class="row">
                        <form action="">

                            <div class="col-lg-12 col-md-6 col-sm-4 p-5">
                                <label for="Category" class="form-label fw-bold fs-5 mb-2">Category Name</label>
                                <input id="category" name="category" class="form-control" type="text" placeholder="Enter Category Name">
                                <span id="Category-error" class="text-danger"></span>
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn btn-dark rounded-pill px-5" onclick="addCategory();">Add</button>

                                </div>
                            </div>


                        </form>
                    </div>
                    <div class="row">

                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Category</th>
                                    <th>No of Books</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="categoryTableBody">

                            </tbody>
                        </table>


                    </div>

                </div>



            </div>
        </div>
    </div>
    <script src="<?php echo Config::getJsPath("book.js"); ?>"></script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>