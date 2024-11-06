<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                    <td class="d-flex justify-content-around py-4">
                        <div class="">
                            <div class="m-1">
                            <button class="btn btn-success"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-primary"><i class="fa fa-envelope"></i></button>
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

</body>
</html>