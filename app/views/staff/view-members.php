<?php
require_once "../../main.php";


require_once Config::getControllerPath("membercontroller.php");

$userController = new UserController();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php include "dash_header.php"; ?>

    <div class="d-flex bg-light">
        <div class="nav-bar vh-100">
            <?php include "dash_sidepanel.php"; ?>
        </div>
        <div class="container-fluid mx-5 mb-5 bg-white">
            <div class="row">
                <nav class="navbar p-4 navbar-light bg-light">
                    <span class="navbar-brand mb-0 h1">Dashboard <small class="text-muted">control panel</small></span>
                    <a href="#" class="text-decoration-none h5"><i class="fa fa-home"></i> Home</a>
                </nav>
            </div>
            <div class="row m-4">
                <div class="col-md-3 mt-2">
                    <form method="POST" action="<?php echo Config::indexPath() ?>?action=searchUsers">
                        <input name="memberId" class="form-control" type="text" placeholder="Type Membership ID">
                </div>
                <div class="col-md-3 mt-2">
                    <input name="nic" class="form-control" type="text" placeholder="Type NIC">
                </div>
                <div class="col-md-6 mt-2">
                    <div class="d-flex">
                        <input name="userName" class="form-control" type="text" placeholder="Type User Name">
                        <button type="submit" name="search" class="btn btn-primary mx-3 px-3"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                </form>
            </div>

            <div class="px-1">
                <table class="table">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Membership ID</th>
                            <th>NIC</th>
                            <th>User's Name</th>
                            <th>Address</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($users)) {
                            echo "<tr><td colspan='7'>No users found</td></tr>";
                        } else {
                            foreach ($users as $row) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($row["member_id"]) ?></td>
                                    <td><?= htmlspecialchars($row["nic"]) ?></td>
                                    <td><?= htmlspecialchars($row["fname"] . " " . $row["lname"]) ?></td>
                                    <td><?= htmlspecialchars($row["address"]) ?></td>
                                    <td><?= htmlspecialchars($row["mobile"]) ?></td>
                                    <td><?= htmlspecialchars($row["email"]) ?></td>
                                    <td>
                                        
                                            <div class="m-1">
                                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateDetailsModal" onclick="loadUserDataUpdate('<?php echo $row['member_id']; ?>');"><i class="fa fa-edit" style="font-size: 10px"></i></button>
                                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#mailModal" onclick="loadMailData('<?php echo $row['member_id']; ?>');"><i class="fa fa-envelope" style="font-size: 10px"></i></button>
                                            </div>
                                            <div class="m-1">
                                                <button class="btn btn-danger" onclick="deactivateUser('<?php echo $row['id']; ?>');"><i class="fa fa-trash" style="font-size: 10px"></i></button>
                                            </div>
                                    </td>
                                </tr>

                        <?php }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation example" class="">
                <ul class="pagination d-flex justify-content-center">
                    <!-- Previous Button -->
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= Config::indexPath() ?>?action=usermanagement&page=<?= max(1, $page - 1) ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= Config::indexPath() ?>?action=usermanagement&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next Button -->
                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= Config::indexPath() ?>?action=usermanagement&page=<?= min($totalPages, $page + 1) ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>


    <!-- Modal Update details-->
    <div class="modal fade" id="updateDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <div class="d-flex justify-content-between align-items-center m-3">
                    <h3 class="mb-0">Edit User Detail</h3>
                    <i class="fa fa-close text-black" style="cursor: pointer;" data-bs-dismiss="modal" aria-label="Close"></i>


                </div>
                <div class="border border-2"></div>
                <div class="p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="membershipID">Membership ID</label>
                            <input type="text" class="form-control" id="userID" value="" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="nic">NIC</label>
                            <input type="text" class="form-control" id="NIC" value="">
                            <span id="nicError" class="text-danger"></span>
                        </div>
                    </div>
                    <div>
                        <label for="userName">User's Name</label>
                        <input type="text" class="form-control" id="username" value="">
                        <span id="usernameError" class="text-danger"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" value="">
                            <span id="emailError" class="text-danger"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="tel" class="form-control" id="phoneNumber" value="">
                            <span id="phoneError" class="text-danger"></span>
                        </div>
                    </div>
                    <div>
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" rows="3"></textarea>
                        <span id="addressError" class="text-danger"></span>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-primary mt-3 px-4" onclick="updateUserDetails();">Update User Details</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Mail-->
    <div class="modal fade" id="mailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="d-flex justify-content-between align-items-center m-3">
                    <h3 class="mb-0">Send Email</h3>
                    <i class="fa fa-close text-black" style="cursor: pointer;" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>
                <div class="border border-2"></div>
                <div class="p-4">
                    <div class="mb-3">
                        <label for="name" class="form-label">User Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="" disabled>
                        <span id="nameError" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="emailadd" class="form-label">User Email</label>
                        <input type="text" class="form-control" id="emailadd" name="emailadd" value="" disabled>
                        <span id="emailError" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject">
                        <span id="subjectError" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                        <span id="messageError" class="text-danger"></span>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-primary px-4 mt-3" onclick="sendEmail()">Send</button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Bootstrap and JavaScript -->
    <script src="<?php echo Config::getJsPath("member.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>