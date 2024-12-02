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
                        <span class="btn btn-success my-1 btn-sm" data-bs-toggle="modal" data-bs-target="#updateBookDetailsModal" onclick="loadBookDataUpdate('<?php echo $row['book_id']; ?>');"><i class="fas fa-edit"></i></span>
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Books</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label for="book_id" class="form-label">Book ID</label>
                            <input id="book_id" class="form-control" type="text" disabled />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input id="isbn_no" class="form-control" type="text" placeholder="Enter ISBN" />
                        </div>
                        <div class="col-lg-6 col-sm-12 mb-3">
                           
                            <label for="title" class="form-label">Book Title</label>
                            <input id="title" class="form-control" type="text" placeholder="Enter Book Title" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Author Name</label>
                        <input id="author" class="form-control" type="text" placeholder="Enter Author Name" />
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label for="category" class="form-label">Book Category</label>
                            <select class="form-select" id="category">
                                <option value="">Select Category</option>
                                <?php
                                $rs = Database::search("SELECT * FROM `category`");
                                $n = $rs->num_rows;

                                for ($x = 0; $x < $n; $x++) {
                                    $d = $rs->fetch_assoc();
                                    ?>
                                    <option value="<?php echo $d['category_id']; ?>"><?php echo $d['category_name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>

                        </div>
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label for="pub_year" class="form-label">Published Year</label>
                            <input id="pub_year" class="form-control" type="text" placeholder="Enter Publisher" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label for="qty" class="form-label">Quantity</label>
                            <input id="qty" class="form-control" type="number" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="des" class="form-label">Description</label>
                        <textarea id="des" class="form-control" rows="3" placeholder="Enter Book Description"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" onclick="updateBookDetails();">Update Book Details</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>