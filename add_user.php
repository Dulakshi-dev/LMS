<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .btn-yellow{
            background: rgba(221, 182, 53, 1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row m-4">
            <div class="col-md-3 mt-2">
                <input class="form-control" type="text" placeholder="Type Membership ID">
            </div>
            <div class="col-md-3 mt-2">
                <input class="form-control" type="text" placeholder="Type NIC">
            </div>
            <div class="col-md-6 mt-2">
                <div class="d-flex">
                <input class="form-control" type="text" placeholder="Type User Name">
                <button class="btn btn-primary mx-3 px-3"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>

        <div class="px-5">
            <table>
                        <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                    <th>Membership ID</th>
                    <th>NIC</th>
                    <th>User's Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Receipt</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td>001</td>
                    <td>123456789V</td>
                    <td>John Doe</td>
                    <td>123 Main St</td>
                    <td>+123456789</td>
                    <td>johndoe@example.com</td>
                    <td><div style="width: 50px; height: 50px;">
                        <img src="" alt="">
                    </div></td>
                    <td class="d-flex justify-content-around">
                        <div class="">
                            <div class="m-1">
                            <button class="btn btn-success"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-yellow"><i class="fa fa-envelope"></i></button>
                            </div>
                            <div class="m-1">
                            <button class="btn btn-info"><i class="fa fa-check"></i></button>
                            <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </td>
                    <tr>
                </tbody>
            </table>
        </div>
    </div>

    -------------

    <div class="container border shadow">
    <div class="d-flex justify-content-between align-items-center m-3">
        <h3 class="mb-0">Send Email</h3>
        <i class="fa fa-close text-black" style="cursor: pointer;"></i>
    </div>    
    <div class="border border-2"></div>
    <div class="row">
        <div class="col-md-8 m-5">
            <form>
                <div class="form-group">
                    <label for="membershipID">from</label>
                    <input type="text" class="form-control" id="From">
                </div>
                <div class="form-group">
                    <label for="nic">To</label>
                    <input type="text" class="form-control" id="nic">
                </div>
                <div class="form-group">
                    <label for="userName">Subject</label>
                    <input type="text" class="form-control" id="userName">
                </div>
                <div class="form-group">
                    <label for="address">Message</label>
                    <textarea class="form-control" id="address" rows="3"></textarea>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary mt-3 px-4">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

-------------------------
<div class="container border shadow">
    <div class="d-flex justify-content-between align-items-center m-3">
        <h3 class="mb-0">Edit User Detail</h3>
        <i class="fa fa-close text-black" style="cursor: pointer;"></i>
    </div>    
    <div class="border border-2"></div>
    <div class="row justify-content-end">
        <div class="col-md-8 m-5">
            <form>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="membershipID">Membership ID</label>
                        <input type="text" class="form-control" id="membershipID" placeholder="Enter Membership ID">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="nic">NIC</label>
                        <input type="text" class="form-control" id="nic" placeholder="Enter NIC">
                    </div>
                </div>
                <div class="form-group">
                    <label for="userName">User's Name</label>
                    <input type="text" class="form-control" id="userName" placeholder="Enter User's Name">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter Email">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="phoneNumber">Phone Number</label>
                        <input type="tel" class="form-control" id="phoneNumber" placeholder="Enter Phone Number">
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" rows="3" placeholder="Enter Address"></textarea>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary mt-3 px-4">Update User Details</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>