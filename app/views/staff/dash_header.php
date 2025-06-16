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
    .profile-dropdown {
        position: absolute;
        right: 0;
        top: 100%;
        width: 250px;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        z-index: 1000;
        display: none;
    }

    .dropdown-wrapper:hover .profile-dropdown {
        display: block;
    }

    .bg-library {
        background-color: rgba(26, 50, 65, 1);
    }
</style>

<header>
    <div class="d-flex justify-content-between align-items-center px-3 bg-library text-white">
        <!-- Logo -->
        <div class="navbar navbar-dark">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="<?= Config::indexPath() ?>?action=servelogo&image=<?= $logo ?>" alt="library logo" width="200" height="60">
            </a>
        </div>

        <!-- Profile Section -->
        <nav class="d-flex align-items-center">
            <div class="dropdown-wrapper position-relative">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none">
                    <img src="index.php?action=serveprofimage&image=<?= !empty($profile_img) ? $profile_img : 'user.jpg'; ?>"
                         alt="User" class="rounded-circle me-2" style="height: 40px; width: 40px;">
                    <div class="d-none d-md-block text-start me-2">
                        <div><?= $fname . " " . $lname; ?></div>
                        <small class="text-light"><?= $role_name; ?></small>
                    </div>
                    <i class="fa fa-sort-down ms-1"></i>
                </a>

                <!-- Dropdown Menu -->
                <div class="profile-dropdown mt-2">
                    <div class="text-center border-bottom py-3">
                        <img src="index.php?action=serveprofimage&image=<?= !empty($profile_img) ? $profile_img : 'user.jpg'; ?>"
                             alt="User" class="rounded-circle mb-2" style="height: 60px; width: 60px;">
                        <h6 class="mb-0"><?= $fname . " " . $lname; ?></h6>
                        <small class="text-muted"><?= $role_name; ?></small>
                    </div>
                    <div class="p-3 d-grid gap-2">
                        <a href="<?= Config::indexPath() ?>?action=profile" class="btn btn-outline-primary">
                            <i class="fas fa-user me-2"></i>My Profile
                        </a>
                        <a href="<?= Config::indexPath() ?>?action=logout" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
