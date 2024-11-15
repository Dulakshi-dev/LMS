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
    <div class="box-0 container my-5 d-none">
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


<div class="box-1 container-fluid my-5 d-none">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 my-3">
                <input type="text" class="form-control" placeholder="Type Book Name">
            </div>
            <div class="col-md-6 d-flex my-3">
            <input type="text" class="form-control" placeholder="Type IBSM">
            <button class="btn btn-primary ml-3 px-4"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>ISBN</th>
                    <th>Cover Page</th>
                    <th>Book Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Borrowed</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1234567890</td>
                    <td><img src="assets/book-cover.jpg" alt="Cover Page" style="height: 100px; width: auto;"></td>
                    <td>Cold Case</td>
                    <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                    <td>Thriller</td>
                    <td>10</td>
                    <td>5</td>
                    <td class="text-center">
                        <span class="btn btn-success my-1 btn-sm"><i class="fas fa-edit"></i></span>
                        <span class="btn btn-danger my-1 btn-sm"><i class="fas fa-trash-alt"></i></span>
                    </td>
                </tr>
              
                <tr>
                <td>1234567890</td>
                    <td><img src="assets/book-cover.jpg" alt="Cover Page" style="height: 100px; width: auto;"></td>
                    <td>Cold Case</td>
                    <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                    <td>Thriller</td>
                    <td>10</td>
                    <td>5</td>
                    <td class="text-center">
                    <span class="btn btn-success my-1 btn-sm"><i class="fas fa-edit"></i></span>
                    <span class="btn btn-danger my-1 btn-sm"><i class="fas fa-trash-alt"></i></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pt-3" style="position: fixed; bottom: 0; width: 100%;">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end mx-5">
                <li class="mx-1 page-item disabled"><a class="page-link bg-light" href="#">Previous</a></li>
                <li class=" mx-1 page-item active"><a class="page-link px-4 bg-lght" href="#">1</a></li>
                <li class="mx-1 page-item"><a class="page-link px-4 bg-light" href="#">2</a></li>
                <li class="mx-1 page-item"><a class="page-link px-4 bg-light" href="#">3</a></li>
                <li class="mx-1 page-item"><a class="page-link px-4 bg-light" href="#">Next</a></li>
            </ul>
        </nav>
    </div>

</div>


<div class="box-2 container my-5 dnone">
        <div class="bg-white border rounded shadow p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Edit Books</h3>
                <i class="fa fa-close text-black" style="cursor: pointer;" data-bs-dismiss="modal" aria-label="Close"></i>
            </div>

            <div class="border border-dark"></div>
            
            <div class="row justify-content-end">

                <div class="col-md-9">
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
                            <button type="button" class="btn btn-primary">Update Book Details</button>
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
