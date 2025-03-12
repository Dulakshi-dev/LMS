

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php 
require_once Config::getViewPath("home", "header.view.php");
?>

    <section class="contact py-5" style="background: url('<?php echo Config::getImagePath("contactbg.png"); ?>') no-repeat center center/cover;">
        <div class="container text-center text-white">
            <h1>Contact  Us with malanka</h1>
            <p>If you have any questions or inquiries, feel free to reach out to us.</p>
        </div>

        <div class="container d-flex flex-column flex-md-row justify-content-between py-5">
            <!-- Contact Information -->
            <div class="col-md-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon bg-light p-3 rounded-circle">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                        </div>
                        <div class="ms-3">
                            <h5>Address</h5>
                            <p>432/1, Nidahasmawatha, Yanthampalawa, Kurunegala</p>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm mb-3">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon bg-light p-3 rounded-circle">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                        </div>
                        <div class="ms-3">
                            <h5>Phone</h5>
                            <p>0702319145</p>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon bg-light p-3 rounded-circle">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </div>
                        <div class="ms-3">
                            <h5>Email</h5>
                            <p>yehanwickramasinghe@yahoo.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-md-6 position-relative p-4">
                <div class="card shadow-sm bg-light opacity-75 position-absolute top-0 start-0 w-100 h-100">
                    <!-- Contact form background with opacity and positioning for blur effect -->
                </div>
                <div class="card-body position-relative">
                    <h2>Send Message</h2>
                    <form>
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullName" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Type Your Message</label>
                            <textarea class="form-control" id="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php 
    require_once Config::getViewPath("home", "footer.view.php");
 ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
