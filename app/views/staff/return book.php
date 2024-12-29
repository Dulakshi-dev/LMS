<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom border-1 border-danger">
                <h5 class="modal-title">Return Book Management</h5>
                <button type="button" class="btn"><i class="fa fa-close"></i></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3 row align-items-center">
                        <label for="returnDate" class="col-sm-4 col-form-label">Return Date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="returnDate" placeholder="Enter return date">
                        </div>
                    </div>
                    <div class="mb-3 row align-items-center">
                        <label for="amount" class="col-sm-4 col-form-label">Rs</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="amount" placeholder="Enter amount">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Return Book</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
