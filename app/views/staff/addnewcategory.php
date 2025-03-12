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

<body>
    <div class="d-flex bg-light">

        <div class="box-0 container my-5">
            <div class="bg-white vh-100">
                <div class="text-center border-bottom border-danger border-4 mb-4 pb-3">
                    <h2>Add New Category</h2>
                </div>
                <form action="">
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-4 p-5">
                            <label for="Category" class="form-label fw-bold fs-5 mb-2">Category Name</label>
                            <input id="Category" name="Category" class="form-control" type="text" placeholder="Enter Category Name">
                            <span id="Category-error" class="text-danger"></span>
                            <div class="d-flex justify-content-end mt-4">
                                <button class="btn btn-dark rounded-pill px-5">Add</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script src="<?php echo Config::getJsPath("book.js"); ?>"></script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>