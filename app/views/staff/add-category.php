<?php
if (!isset($_SESSION['staff'])) {
    header("Location: index.php");
    exit;
}

if (isset($_SESSION['staff']['last_activity']) && (time() - $_SESSION['staff']['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

$_SESSION['staff']['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Category";
require_once Config::getViewPath("common", "head.php");
?>

<body onload="loadCategory();">
    <?php require_once Config::getViewPath("staff", "dash_header.php"); ?>

    <div class="d-flex bg-light flex-wap">
        <!-- Side Panel -->
        <div>
            <div class="h-100 d-none d-lg-block">
                <?php include "dash_sidepanel.php"; ?>
            </div>
            <div class="h-100 d-block d-lg-none">
                <?php include "small_sidepanel.php"; ?>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 container-fluid py-4">
            <div class="bg-white rounded shadow p-4">
                <!-- Header -->
                <div class="text-center border-bottom border-danger border-3 mb-4 pb-2">
                    <h2 class="fw-bold">Add New Category</h2>
                </div>

                <!-- Form -->
                <div class="row justify-content-center mb-4">
                    <div class="col-lg-8 col-md-10 col-sm-12">
                        <form>
                            <div class="mb-3">
                                <label for="category" class="form-label fw-bold fs-5">Category Name</label>
                                <input id="category" name="category" class="form-control" type="text" required placeholder="Enter Category Name">
                                <span id="Category-error" class="text-danger"></span>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-dark rounded-pill px-5" onclick="addCategory();">Add</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Category Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th>No of Books</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTableBody">
                            <!-- Dynamically loaded content -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php require_once Config::getViewPath("staff", "footer.php"); ?>

    <!-- JS -->
    <script src="<?php echo Config::getJsPath("staffBook.js"); ?>"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
