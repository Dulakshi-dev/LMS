<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .book-car {
            background: white;
            border-radius: 8px;
            box-shadow: 0px 4px 6px 3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class=" bg-light">
        <div class="p-5">
            <div class="container-fluid mt-4 p-4 border rounded book-car">
                <h3 class="text-start"><i class="fa fa-clock-o"></i> Time Setup</h3>
                <div class="row ">
                    <!-- Labels Column -->
                    <div class="col-md-2 mt-5 col-12">
                        <div><label class="fw-bold mb-5">Opening Hours</label></div>
                        <div><label class="fw-bold mb-5">Opening Hours</label></div>
                        <div><label class="fw-bold mb-5">Opening Hours</label></div>
                    </div>

                    <!-- First Column of Inputs -->
                    <div class="col-md-5 col-12">
                        <h5>Week Days</h5>
                        <div class="d-flex gap-3">
                            <div>
                                <label>From</label>
                                <input type="text" class="form-control">
                            </div>
                            <div>
                                <label>To</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="d-flex gap-3 mt-2">
                            <div>
                                <label>From</label>
                                <input type="text" class="form-control">
                            </div>
                            <div>
                                <label>To</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="d-flex gap-3 mt-2">
                            <div>
                                <label>From</label>
                                <input type="text" class="form-control">
                            </div>
                            <div>
                                <label>To</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Second Column of Inputs -->
                    <div class="col-md-5 col-12">
                        <h5 class="">WeekEnd Days</h5>
                        <div class="d-flex gap-3">
                            <div>
                                <label>From</label>
                                <input type="text" class="form-control">
                            </div>
                            <div>
                                <label>To</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="d-flex gap-3 mt-2">
                            <div>
                                <label>From</label>
                                <input type="text" class="form-control">
                            </div>
                            <div>
                                <label>To</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="d-flex gap-3 mt-2">
                            <div>
                                <label>From</label>
                                <input type="text" class="form-control">
                            </div>
                            <div>
                                <label>To</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <button class="btn btn-primary px-5">Save</h3></button>
                </div>
            </div>

            <!-- Navbar -->
            <div class="container-fluid mt-4 p-4 border rounded book-car">
                <h3 class="text-start"><i class="fa fa-newspaper-o"></i> Latest News and Updates</h3>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <!-- Select Box -->
                        <div class="d-flex align-items-center gap-3 m-3">
                            <label for="boxSelection" class="col-form-label fw-bold">Select_Box</label>
                            <select id="boxSelection" class="form-select">
                                <option value="" selected disabled>Choose an option</option>
                                <option value="box1">BOX-1</option>
                                <option value="box2">BOX-2</option>
                                <option value="box3">BOX-3</option>
                            </select>
                        </div>

                        <!-- Title -->
                        <div class="d-flex align-items-center gap-3 m-3">
                            <label for="title" class="col-form-label fw-bold">Title</label>
                            <input type="text" id="title" class="form-control">
                        </div>

                        <!-- Description -->
                        <div class="d-flex align-items-center gap-3 m-3">
                            <label for="description" class="col-form-label fw-bold">Description</label>
                            <input type="text" id="description" class="form-control">
                        </div>

                        <!-- Date -->
                        <div class="d-flex align-items-center gap-3 m-3">
                            <label for="date" class="col-form-label fw-bold">Date</label>
                            <input type="date" id="date" class="form-control">
                        </div>
                    </div>


                    <div class="col-12 col-md-6">
                        <div>
                            <label class="fw-bold my-4">Discription</label>
                            <textarea class="form-control" name="" id=""></textarea>
                        </div>
                        <div class="my-3 d-flex justify-content-center">
                            <input type="file" class="form-control w-75">
                        </div>
                        <div class="text-end pt-4">
                            <button class="btn px-5 btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-4 p-4 border rounded book-car">
                <h3 class="text-start"><i class="fa fa-envelope-o"></i> Send Email to All Users</h3>
                <div class="row">
                    <div class="col-12 col-md-6">

                        <div class="d-flex align-items-center py-3 gap-3 m-3">
                            <label for="title1" class="col-form-label fw-bold">Subject</label>
                            <input type="text" id="title1" class="form-control">
                        </div>
                    </div>


                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center gap-3 m-3">
                            <label for="description1" class="col-form-label fw-bold">Message</label>
                            <textarea class="form-control" name="" id="description1"></textarea>
                        </div>

                        <div class="text-end pt-4">
                            <button class="btn px-5 btn-primary">Send</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-4 p-4 border border rounded book-car">
                <h3 class="text-start mb-3"><i class="fa fa-money"></i>  Change Membership Fee</h3>
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <label for="title" class="col-form-label fw-bold">Fee</label>
                            <input type="text" id="fee" class="form-control" placeholder="Enter membership fee">
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-primary px-5">Save</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>