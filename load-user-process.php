<table class="table">
    <thead class="thead-light text-center">
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
        <?php
        include "connection.php";
        $rs = Database::search("SELECT * FROM member");
        $num = $rs->num_rows;

        for ($x = 0; $x < $num; $x++) {
            $row = $rs->fetch_assoc();
        ?>
            <tr>
                <td><?php echo $row["member_id"]; ?></td>
                <td><?php echo $row["nic"]; ?></td>
                <td><?php echo $row["fname"] . " " . $row["lname"]; ?></td>
                <td><?php echo $row["address"]; ?></td>
                <td><?php echo $row["mobile"]; ?></td>
                <td><?php echo $row["email"]; ?></td>
                <td><img src="<?php echo $row["profile_img"]; ?>" width="50" height="50"></td>
                

                <td>
                    <?php
                        if ($row["status"] == '1') {
                        ?>
                            <div class="m-1">
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateDetailsModal"><i class="fa fa-edit" style="font-size: 10px"></i></button>
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#mailModal"><i class="fa fa-envelope" style="font-size: 10px"></i></button>
                            </div>
                            <div class="m-1">
                                <button class="btn btn-info" onclick="changeUserStatus(<?php echo $row['id']; ?>, 1);"><i class="fa fa-check" style="font-size: 10px"></i></button>
                                <button class="btn btn-danger"><i class="fa fa-trash" style="font-size: 10px"></i></button>
                            </div>

                        <?php
                        } else {
                        ?>
                            <div class="m-1">
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateDetailsModal"><i class="fa fa-edit" style="font-size: 10px"></i></button>
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#mailModal"><i class="fa fa-envelope" style="font-size: 10px"></i></button>
                            </div>
                            <div class="m-1">
                                <button class="btn btn-info" onclick="changeUserStatus(<?php echo $row['id']; ?>, 1);"><i class="fa fa-times" style="font-size: 10px"></i></button>
                                <button class="btn btn-danger"><i class="fa fa-trash" style="font-size: 10px"></i></button>
                            </div>
                      
                        <?php
                        }
                        ?>
                </td>
                    




               
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<!-- Modal Mail-->
<div class="modal fade" id="mailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="d-flex justify-content-between align-items-center m-3">
                <h3 class="mb-0">Send Email</h3>
                <i class="fa fa-close text-black" style="cursor: pointer;" data-bs-dismiss="modal" aria-label="Close"></i>
            </div>    
            <div class="border border-2"></div>
            <div class="row">
                <div class="p-3">
                    <form>
                        <div class="form-group">
                            <label for="From">From</label>
                            <input type="text" class="form-control" id="From">
                        </div>
                        <div class="form-group">
                            <label for="To">To</label>
                            <input type="text" class="form-control" id="To">
                        </div>
                        <div class="form-group">
                            <label for="Subject">Subject</label>
                            <input type="text" class="form-control" id="Subject">
                        </div>
                        <div class="form-group">
                            <label for="Message">Message</label>
                            <textarea class="form-control" id="Message" rows="3"></textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary mt-3 px-4">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update details-->
<div class="modal fade" id="updateDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="d-flex justify-content-between align-items-center m-3">
                <h3 class="mb-0">Edit User Detail</h3>
                <i class="fa fa-close text-black" style="cursor: pointer;" data-bs-dismiss="modal" aria-label="Close"></i>
                    
                
             </div>    
            <div class="border border-2"></div>
            <div class="p-3">
                
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
</div>
