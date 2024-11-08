<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body onload="loadUsers(1);">
    <?php include "dash_header.php"; ?>

    <div class="d-flex">
        <div class="nav-bar">
            <?php include "dash_sidepanel.php"; ?>
        </div>
        <div class="container-fluid">
            <div class="row m-4">
                <!-- Search Inputs -->
                <div class="col-md-3 mt-2">
                    <input id ="memberId" class="form-control" type="text" placeholder="Type Membership ID">
                </div>
                <div class="col-md-3 mt-2">
                    <input id="nic" class="form-control" type="text" placeholder="Type NIC">
                </div>
                <div class="col-md-6 mt-2">
                    <div class="d-flex">
                        <input id="userName" class="form-control" type="text" placeholder="Type User Name">
                        <button class="btn btn-primary mx-3 px-3" onclick="searchUsers();"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="px-1" id="content">
                <!-- Content will be loaded here from loadUser function -->
            </div>
        </div>
    </div>

    <!-- Bootstrap and JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="path_to_your_js_file.js"></script> <!-- Add this if you have a separate JavaScript file -->

</body>
</html>

 