<?php
require_once "../main.php";
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

    <div class="d-flex">
    <div class="nav-bar">
            <?php include "dash_sidepanel.php"; ?>
        </div>
        <div class="container-fluid">
        
            <div class="row">
                <div class="col-md-6 my-3">
                    <input id="bname" type="text" class="form-control" placeholder="Type Book Name">
                </div>
                <div class="col-md-6 d-flex my-3">
                    <input id="isbn" type="text" class="form-control mx-3" placeholder="Type ISBN">
                    <button class="btn btn-primary ml-3 px-4" onclick="searchBook();"><i class="fa fa-search px-2"></i></button>
                </div>
            </div>
   
            <div class="px-1">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>BorrowID</th>
                            <th>Book ID</th>
                            <th>Tiltle</th>
                            <th>Member ID</th>
                            <th>Member Name</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Action</th>
                          
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($books)) {
                            echo "<tr><td colspan='7'>No Books found</td></tr>";
                        } else {

                            foreach ($books as $row) {

                               

                        ?>
                                    <tr>
                                        <td><?php echo $row["borrow_id"]; ?></td>
                                        <td><?php echo $row["book_id"]; ?></td>
                                        <td><?php echo $row["title"]; ?></td>
                                        <td><?php echo $row["member_id"]; ?></td>
                                        <td><?php echo $row["fname"]." ".$row["lname"]; ?></td>
                                        <td><?php echo $row["borrow_date"]; ?></td>
                                        <td><?php echo $row["due_date"]; ?></td>
                                        <td>

                                            <div class="m-1">
                                                <span class="btn btn-success my-1 btn-sm" data-bs-toggle="modal" data-bs-target="#updateBookDetailsModal" onclick="loadBookDataUpdate('<?php echo $row['book_id']; ?>');"><i class="fas fa-edit"></i></span>
                                            </div>
                                        </td>
                                    </tr>
                        <?php
                                }
                            }
                        
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!-- Bootstrap and JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="path_to_your_js_file.js"></script> <!-- Add this if you have a separate JavaScript file -->

</body>
</html>

 