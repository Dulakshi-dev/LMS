<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore</title>
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
        rel="stylesheet" />
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        rel="stylesheet" />
    <style>
        .book-card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .book-card:hover {
            transform: scale(1.03);
        }

        .book-image img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .book-title {
            font-weight: bold;
            margin: 10px 0 5px;
        }

        .success {
            color: rgba(21, 83, 28, 1);
            background: rgba(185, 247, 192, 1);
        }

        .success:hover {
            background: rgb(69, 161, 80);
        }
    </style>
</head>

<body class="bg-light">


    <div class="d-flex">
        <div class="nav-bar">
            <!-- sidepanel -->
        </div>
        <div>
            <div class="d-flex justify-content-between align-items-center m-4">
                <div class="flex-grow-1 w-50 d-flex justify-content-center">
                    <input type="text" class="form-control w-50" placeholder="Search your favourite Book">
                </div>
                <div class="ms-auto">
                    <a href="#" class="text-decoration-none text-dark">
                        <i class="fa fa-home"></i> Home
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-4 px-3">
                    <input type="text" class="form-control" placeholder="Category">
                </div>
                <div class="col-md-4 d-flex px-3">
                    <input type="text" class="form-control" placeholder="Language">
                    <button class="btn btn-primary mx-4 px-3"><i class="fas fa-search"></i></button>
                </div>
            </div>


            <div class="containe bg-white rounded p-4 m-5">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Recommended</h5>
                    <a href="#" class="text-decoration-none">See All <i class="fa fa-angle-right"></i></a>
                </div>
                <div class="row g-4">
                    <?php if (!empty($booksrec)) {
                        foreach ($booksrec as $row) { ?>
                            <div class="col-md-3 col-sm-6">
                                <div class="book-card">
                                    <div class="book-image">
                                        <img src="<?php echo Config::indexPath() ?>?action=serveimage&image=<?php echo urlencode(basename($row['cover_page'])); ?>" alt="Book Cover">
                                    </div>
                                    <div class="p-3 d-flex justify-content-between align-items-center">
                                        <div class="text-start">
                                            <div class="book-title"><?php echo $row["title"]; ?></div>
                                            <div><?php echo $row["author"]; ?></div>
                                        </div>
                                        <button class="btn btn-sm view-details" id="success"
                                            data-title="<?php echo $row["title"]; ?>"
                                            data-author="<?php echo $row["author"]; ?>"
                                            data-id="<?php echo $row["book_id"]; ?>"
                                            data-description="<?php echo $row["description"]; ?>"
                                            data-availability="<?php echo ($row["available_qty"] > 0) ? 'Available' : 'Not Available'; ?>">

                                            View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } else {
                        echo "<p>No Books found</p>";
                    } ?>
                </div>


                <div class="row g-5">
                    <div class="col-md-3 col-sm-6">
                        <div class="book-card">
                            <div class="book-image">
                                <img src="../../../public/images/about.png" alt="Book Cover">
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="x text-start">
                                    <div class="book-title">James</div>
                                    <div>Percival Everett</div>
                                </div>
                                <button class="btn success btn-sm">View Details</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="book-card">
                            <div class="book-image">
                                <img src="../../../public/images/about.png" alt="Book Cover">
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="x text-start">
                                    <div class="book-title">James</div>
                                    <div>Percival Everett</div>
                                </div>
                                <button class="btn success btn-sm">View Details</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="book-card">
                            <div class="book-image">
                                <img src="../../../public/images/about.png" alt="Book Cover">
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="x text-start">
                                    <div class="book-title">James</div>
                                    <div>Percival Everett</div>
                                </div>
                                <button class="btn success btn-sm">View Details</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="book-card">
                            <div class="book-image">
                                <img src="../../../public/images/about.png" alt="Book Cover">
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="text-start">
                                    <div class="book-title">James</div>
                                    <div>Percival Everett</div>
                                </div>
                                <button class="btn success btn-sm">View Details</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="book-card">
                            <div class="book-image">
                                <img src="../../../public/images/about.png" alt="Book Cover">
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="x text-start">
                                    <div class="book-title">James</div>
                                    <div>Percival Everett</div>
                                </div>
                                <button class="btn success btn-sm">View Details</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="book-card">
                            <div class="book-image">
                                <img src="../../../public/images/about.png" alt="Book Cover">
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="x text-start">
                                    <div class="book-title">James</div>
                                    <div>Percival Everett</div>
                                </div>
                                <button class="btn success btn-sm">View Details</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="book-card">
                            <div class="book-image">
                                <img src="../../../public/images/about.png" alt="Book Cover">
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="x text-start">
                                    <div class="book-title">James</div>
                                    <div>Percival Everett</div>
                                </div>
                                <button class="btn success btn-sm">View Details</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="book-card">
                            <div class="book-image">
                                <img src="../../../public/images/about.png" alt="Book Cover">
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="text-start">
                                    <div class="book-title">James</div>
                                    <div>Percival Everett</div>
                                </div>
                                <button class="btn success btn-sm">View Details</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="book-card">
                            <div class="book-image">
                                <img src="../../../public/images/about.png" alt="Book Cover">
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="x text-start">
                                    <div class="book-title">James</div>
                                    <div>Percival Everett</div>
                                </div>
                                <button class="btn success btn-sm">View Details</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="book-card">
                            <div class="book-image">
                                <img src="../../../public/images/about.png" alt="Book Cover">
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="x text-start">
                                    <div class="book-title">James</div>
                                    <div>Percival Everett</div>
                                </div>
                                <button class="btn success btn-sm">View Details</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="book-card">
                            <div class="book-image">
                                <img src="../../../public/images/about.png" alt="Book Cover">
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="x text-start">
                                    <div class="book-title">James</div>
                                    <div>Percival Everett</div>
                                </div>
                                <button class="btn success btn-sm">View Details</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="book-card">
                            <div class="book-image">
                                <img src="../../../public/images/about.png" alt="Book Cover">
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="text-start">
                                    <div class="book-title">James</div>
                                    <div>Percival Everett</div>
                                </div>
                                <button class="btn success btn-sm">View Details</button>
                            </div>
                        </div>
                    </div>
                    <!-- Repeat for other books -->
                </div>
            </div>
        </div>

    </div>


    <div class="offcanvas offcanvas-end bg-dark text-white" tabindex="-1" id="bookDetailsCanvas" aria-labelledby="bookDetailsCanvasLabel" style="width: 320px;">
        <div class="offcanvas-header">
            <h5 id="bookDetailsCanvasLabel">Book Details</h5>
            <button type="button" class="btn-close bg-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>


        <div class="offcanvas-body">
            <div class="d-flex justify-content-end ">
                <button style="background: none; border: none; padding: 0; cursor: pointer;">
                    <i id="save-btn" class="fa fa-bookmark text-white fs-4"></i>
                </button>

            </div>
            <div class="book-details">
                <div class="bg-white rounded m-3 d-flex flex-column align-items-center justify-content-center"
                    style="width: 160px; height: 250px;">
                    <img src="" alt="">
                    <p class="text-warning mt-2 text-center"><strong>ID:</strong> <span id="book-id"></span></p>
                </div>
                <h5 class="my-2 text-warning" id="book-title"></h5>
                <p id="book-author"></p>
                <p> <span id="book-availability"></span></p>

                <p style="text-align: justify;"><strong>Description:</strong> <span id="book-description"></span></p>
                <button id="reserve-btn" class="btn btn-primary">Reserve</button>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function() {
                const title = this.getAttribute('data-title');
                const author = this.getAttribute('data-author');
                const id = this.getAttribute('data-id');
                const description = this.getAttribute('data-description');
                const coverImage = this.closest('.book-card').querySelector('img').getAttribute('src');
                const availability = this.getAttribute('data-availability');

                document.getElementById('book-title').textContent = title;
                document.getElementById('book-author').textContent = author;
                document.getElementById('book-id').textContent = id;
                document.getElementById('book-description').textContent = description;
                document.getElementById('book-availability').textContent = availability;

                const reserveBtn = document.getElementById('reserve-btn');
                const availabilityText = document.getElementById('book-availability');

                if (availability === "Not Available") {
                    reserveBtn.classList.add("d-none");
                    availabilityText.style.color = "#F08080"; // Light Coral
                } else {
                    reserveBtn.classList.remove("d-none");
                    availabilityText.style.color = "#98FF98"; // Mint Green
                }

                const offcanvasImage = document.querySelector('.offcanvas-body .book-details img');
                offcanvasImage.setAttribute('src', coverImage);
                offcanvasImage.style.width = "150px";
                offcanvasImage.style.height = "200px";
                offcanvasImage.style.objectFit = "cover";

                // Ensure memberId is properly defined before using it
                const memberId = "<?php echo $_SESSION['member']['member_id'] ?? ''; ?>";

                if (memberId) {
                    document.getElementById('reserve-btn').onclick = function() {
                        window.location.href = "<?php echo Config::indexPathMember(); ?>?action=reserve&book_id=" + id + "&member_id=" + memberId;
                    };

                    document.getElementById('save-btn').onclick = function() {
                        window.location.href = "<?php echo Config::indexPathMember(); ?>?action=save&book_id=" + id + "&member_id=" + memberId;
                    };
                } else {
                    console.error("Member ID is not set.");
                }

                const offcanvas = new bootstrap.Offcanvas(document.getElementById('bookDetailsCanvas'));
                offcanvas.show();
            });
        });
    </script>
</body>

</html>