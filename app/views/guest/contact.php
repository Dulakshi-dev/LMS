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

    <section class="contact py-5" style="background: url('<?php echo Config::getImagePath("contactbg.png"); ?>') no-repeat center center/cover;">
        <div class="container text-center text-white mb-5">
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
                    <div class="card shadow-lg p-4" style="background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.3);">
                        <div class="card-body">
                            <h3 class="mb-4">Send a Message</h3>
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" required>
                                <p class="text-danger small" id="nameerror"></p>
                            </div>
                            <div class="mb-3">
                                <label for="emailadd" class="form-label">Email</label>
                                <input type="email" class="form-control" id="emailadd" required>
                                <p class="text-danger small" id="emailerror"></p>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="4" required></textarea>
                                <p class="text-danger small" id="msgerror"></p>
                            </div>
                            <div class="text-end">
                                <button class="btn bg-black rounded-5  px-5" id="btn" onclick="sendContactMail()">
                                    <span id="btnText" class="text-white">Send</span>
                                    <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status"></span>
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
