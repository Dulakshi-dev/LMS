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
$memberId =$_POST["member_id"]; 
$nic =$_POST["nic"]; 
$userName =$_POST["userName"]; 

$query = "SELECT * FROM `user` INNER JOIN `login` ON `user`.`id`=`login`.`userId` WHERE 1=1"; 

if (!empty($memberId)) {
    $query .= " AND `user_id` LIKE '%$memberId%'";
}

if (!empty($nic)) {
    $query .= " AND `nic` LIKE '%$nic%'";
}

if (!empty($userName)) {
    $query .= " AND `fname` LIKE '%$userName%' OR `lname` LIKE '%$userName%'";
}

$rs = Database::search($query); 
$num = $rs->num_rows; 
 
if($num > 0){ 
    for($x = 0; $x < $num; $x++){ 
        $row = $rs->fetch_assoc(); 
        ?>    
             <tr>
                <td><?php echo $row["user_id"]; ?></td>
                <td><?php echo $row["nic"]; ?></td>
                <td><?php echo $row["fname"] . " " . $row["lname"]; ?></td>
                <td><?php echo $row["address"]; ?></td>
                <td><?php echo $row["mobile"]; ?></td>
                <td><?php echo $row["email"]; ?></td>
                <td><img src="<?php echo $row["receipt"]; ?>" width="50" height="50"></td>
                

                <td>
                    <?php
                        if ($row["status_id"] == '1') {
                        ?>
                            <div class="m-1">
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateDetailsModal" onclick="loadUserDataUpdate(<?php echo $row['id']; ?>, 1);"><i class="fa fa-edit" style="font-size: 10px"></i></button>
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
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateDetailsModal" onclick="loadUserDataUpdate(<?php echo $row['id']; ?>, 1);"><i class="fa fa-edit" style="font-size: 10px"></i></button>
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
}else{ 
    echo("User Not Found"); 
} 
?> 
</tbody> 
</table>


<!-- Modal Mail-->
<div class="modal fade" id="mailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="d-flex justify-content-between align-items-center m-3">
                    <h3 class="mb-0">Send Email</h3>
                    <i class="fa fa-close text-black" style="cursor: pointer;" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>    
                <div class="border border-2"></div>
                <div class="p-4">
                    <form action="index.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">User Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Your Message</label>
                            <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                        </div>
                        <div class="text-end">
                        <button type="button" class="btn btn-primary px-4 mt-3" onclick="sendEmail()">Send</button>

                        </div>
                    </form>
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