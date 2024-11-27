<table class="table">
    <thead class="thead-light">
        <tr>
            <th>ISBN</th>
            <th>Cover Page</th>
            <th>Book Name</th>
            <th>Author</th>
            <th>Published Year</th>
            <th>Category</th>
            <th>Qty</th>
            <th>Borrowed</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include "connection.php";
        $rs = Database::search("SELECT * FROM book_details");
        $num = $rs->num_rows;

        for ($x = 0; $x < $num; $x++) {

            $row = $rs->fetch_assoc();

            if ($row["status"] == 'Active') {
                
        ?>
            <tr>
            <td><?php echo $row["isbn"]; ?></td>
                <td>Image</td>
                <td><?php echo $row["title"]; ?></td>
                <td><?php echo $row["author"]; ?></td>
                <td><?php echo $row["pub_year"]; ?></td>
                <td><?php echo $row["category_name"]; ?></td>
                <td><?php echo $row["qty"]; ?></td>
                <td><?php echo $row["qty"] - $row["available_qty"]; ?></td>
                <td>
                
                    <div class="m-1">
                        <span class="btn btn-success my-1 btn-sm" data-bs-toggle="modal" data-bs-target="#updateBookDetailsModal"><i class="fas fa-edit"></i></span>
                        <span class="btn btn-danger my-1 btn-sm"><i class="fas fa-trash-alt"></i></span>
                    </div>
                </td>
            </tr>
        <?php
        }}
        ?>
    </tbody>
</table>

<div class="modal fade" id="updateBookDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content p-5">

            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Edit Books</h3>
                <i class="fa fa-close text-black" style="cursor: pointer;" data-bs-dismiss="modal" aria-label="Close"></i>
            </div>

            <div class="border border-dark"></div>
            
            <div class="row justify-content-end">

                <div>
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
</div>