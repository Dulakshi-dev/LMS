<table class="table">
    <thead class="thead-light text-center">
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

$title = $_POST["bname"];
$isbn = $_POST["isbn"];

$query = "SELECT * FROM `book_details` WHERE 1=1";

if (!empty($title)) {
    $query .= " AND `title` LIKE '%$title%'";
}

if (!empty($isbn)) {
    $query .= " AND `isbn` LIKE '%$isbn%'";
}

$rs = Database::search($query);
$num = $rs->num_rows;

if ($num > 0) {
    for ($x = 0; $x < $num; $x++) {
        $row = $rs->fetch_assoc();
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
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateDetailsModal" ><i class="fa fa-edit" style="font-size: 10px"></i></button>
                    <button class="btn btn-danger"><i class="fa fa-trash" style="font-size: 10px"></i></button>
                </div>
            </td>
        </tr>
        <?php
    }
} else {
    echo("Book Not Found");
}
?>
</tbody>
</table>

<!-- Modal Update details -->
<div class="modal fade" id="updateDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="d-flex justify-content-between align-items-center m-3">
                <h3 class="mb-0">Edit Book Details</h3>
                <i class="fa fa-close text-black" style="cursor: pointer;" data-bs-dismiss="modal" aria-label="Close"></i>
             </div>
            <div class="border border-2"></div>
            <div class="p-3">
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="isbn">ISBN</label>
                            <input type="text" class="form-control" id="isbn" placeholder="Enter ISBN">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" placeholder="Enter Title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" class="form-control" id="author" placeholder="Enter Author Name">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="category">Category</label>
                            <input type="text" class="form-control" id="category" placeholder="Enter Category">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" id="quantity" placeholder="Enter Quantity">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" rows="3" placeholder="Enter Description"></textarea>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary mt-3 px-4">Update Book Details</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
