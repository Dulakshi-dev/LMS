<?php
require_once __DIR__ . '/../../../main.php';
$fname = $_SESSION["staff"]["fname"];
$lname = $_SESSION["staff"]["lname"];
$role_name = $_SESSION["staff"]["role_name"];
$profile_img = $_SESSION["staff"]["profile_img"];

$libraryData = LibrarySetupModel::getLibraryInfo();
$libraryName = $libraryData['name'];
$libraryAddress = $libraryData['address'];
$libraryEmail = $libraryData['email'];
$libraryPhone = $libraryData['mobile'];
$logo = $libraryData['logo'];
$fee = $libraryData['membership_fee'];
$fine = $libraryData['fine_amount'];
?>

<style>
    nav ul li a img {
        height: 40px;
        width: 40px;
        border-radius: 50%;
    }

    .profile-dropdown {
        position: absolute;
        right: 20px;
        top: 70px;
        width: 250px;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        display: none;
    }

    .profile-dropdown.show {
        display: block;
    }

    .profile-dropdown .dropdown-header {
        padding: 15px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .profile-dropdown .dropdown-body {
        padding: 15px;
    }

    .bg {
        background: rgba(26, 50, 65, 1);
    }
</style>

<header>
    <div class="row m-0 pt-2 bg align-items-center">
        <div class="col navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="<?php echo Config::indexPath() ?>?action=servelogo&image=<?= $logo ?>" alt="library logo" width="200" height="60">
            </a>
        </div>
        <div class="col d-flex justify-content-end">
            <nav>
                <ul class="inline d-flex align-items-center list-unstyled mb-0">
                    <li class="list-inline-item me-3">
                        <a class="text-white text-decoration-none" href="#">
                            <i class="fas fa-bell"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a id="profileDropdownToggle" class="text-white text-decoration-none d-flex align-items-center" href="#" role="button">
                            <img src="index.php?action=serveprofimage&image=<?= !empty($profile_img) ? $profile_img : 'user.jpg'; ?>"
                                alt="User" class="rounded-circle me-2" style="height: 40px; width: 40px;">
                            <div class="text-left mx-3">
                                <span class="d-block"><?php echo $fname . " " . $lname; ?></span>
                                <small><?php echo $role_name; ?> <i class="fas fa-caret-down ms-2"></i></small>
                            </div>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Dropdown Content -->
    <div class="profile-dropdown" id="profileDropdown">
        <div class="dropdown-header">
            <img src="index.php?action=serveprofimage&image=<?= !empty($profile_img) ? $profile_img : 'user.jpg'; ?>"
                alt="User" class="rounded-circle mb-2" style="height: 60px; width: 60px;">
            <h5 class="mb-1"><?php echo $fname . " " . $lname; ?></h5>
            <small class="text-muted"><?php echo $role_name; ?></small>
        </div>
        <div class="dropdown-body">
            <div class="d-grid gap-2">
                <a href="<?php echo Config::indexPath() ?>?action=profile" class="btn btn-outline-primary">
                    <i class="fas fa-user me-2"></i> My Profile
                </a>
                <a href="<?php echo Config::indexPath() ?>?action=logout" class="btn btn-outline-danger">
                    <i class="fas fa-sign-out-alt me-2"></i> Sign Out
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownToggle = document.getElementById('profileDropdownToggle');
        const dropdownMenu = document.getElementById('profileDropdown');

        dropdownToggle.addEventListener('click', function (e) {
            e.preventDefault();
            dropdownMenu.classList.toggle('show');
        });

        document.addEventListener('click', function (e) {
            if (!dropdownToggle.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                dropdownMenu.classList.remove('show');
            }
        });
    });
</script>
