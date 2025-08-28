<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Contact";
require_once Config::getViewPath("common", "head.php");
?>

<body onload="loadLibraryInfo();">

    <?php
    if (isset($_SESSION['member'])) {
        require_once Config::getViewPath("member", "header.php");
    } else {
        require_once Config::getViewPath("guest", "header.view.php");
    }
    ?>

    <section class="contact py-5" style="background: url('<?php echo Config::getImagePath("con6.jpg"); ?>') no-repeat center center/cover;">
        <div class="container text-center text- mb-5">
            <h1 class="fw-bold display-5">Contact Us</h1>
            <p class="lead">If you have any questions or inquiries, feel free to reach out to us.</p>
        </div>

        <div class="container">
            <div class="row g-4">
                <!-- Contact Info -->
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon bg-light text-primary p-3 rounded-circle">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                            </div>
                            <div class="ms-3">
                                <h5>Address</h5>
                                <p id="address" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm mb-3">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon bg-light text-primary p-3 rounded-circle">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                            </div>
                            <div class="ms-3">
                                <h5>Phone</h5>
                                <p id="phone" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon bg-light text-primary p-3 rounded-circle">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </div>
                            <div class="ms-3">
                                <h5>Email</h5>
                                <p id="email" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0 rounded-4 p-4" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15);">
                        <div class="card-body ">
                            <h3 class="mb-4 fw-bold text-uppercase">Send a Message</h3>

                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control bg-transparent border-secondary" id="name" placeholder="Enter your full name">
                                <p class="text-danger small mt-1" id="nameerror"></p>
                            </div>

                            <div class="mb-3">
                                <label for="emailadd" class="form-label">Email</label>
                                <input type="email" class="form-control bg-transparent border-secondary" id="emailadd" placeholder="Enter your email">
                                <p class="text-danger small mt-1" id="emailerror"></p>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control bg-transparent border-secondary" id="message" rows="4" placeholder="Write your message here..."></textarea>
                                <p class="text-danger small mt-1" id="msgerror"></p>
                            </div>

                            <div class="text-end">
                                <button class="btn btn-outline-dark rounded-pill px-5 py-2 fw-semibold" id="btn" onclick="sendContactMail()">
                                    send
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php require_once Config::getViewPath("common", "footer.php"); ?>
    <script src="<?php echo Config::getJsPath("home.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>