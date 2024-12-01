<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        
    </style>
</head>
<body>
    <div class="box-0 container my-5 ">
        <div class="bg-white border rounded shadow p-4">
            <div class="text-center border-bottom border-danger mb-4 pb-3">
                <h2>Add Books</h2>
            </div>
            
            <div class="row">
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <img id="book" style="height: 150px; width: 150px;" src="assets/profimg/user.jpg" alt="book Picture">
                    </div>
                    <div class="mb-3">
                        <input type="file" id="uploadimg" class="form-control">
                    </div>
                </div>

                <div class="col-md-9 p-5">
                    <form>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="first-name" class="form-label">IBSN</label>
                                <input id="first-name" class="form-control" type="text" placeholder="Enter IBSN">
                            </div>
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="last-name" class="form-label">Author Name</label>
                                <input id="last-name" class="form-control" type="text" placeholder="Enter Author Name">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Book Title</label>
                            <input id="address" class="form-control" type="text" placeholder="Enter Book Title"></input>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="nic" class="form-label">Book Category</label>
                                <input id="nic" class="form-control" type="text" placeholder="Enter Book Category">
                            </div>
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="dob" class="form-label">Publisher</label>
                                <input id="dob" class="form-control" type="date" placeholder="Enter Publisher">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="district" class="form-label">Quantity</label>
                                <input id="district" class="form-control" type="number">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Description</label>
                            <textarea id="address" class="form-control" rows="3" placeholder="Enter Book Description"></textarea>
                        </div>

                        <div class="d-flex justify-content-end">   
                            <button type="button" class="btn btn-primary px-5 mr-5">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
