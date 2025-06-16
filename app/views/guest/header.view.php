<?php
require_once '../../main.php';

$libraryData = HomeModel::getLibraryInfo();
$libraryName = $libraryData['name'];
$libraryAddress = $libraryData['address'];
$libraryEmail = $libraryData['email'];
$libraryPhone = $libraryData['mobile'];
$logo = $libraryData['logo'];
$fee = $libraryData['membership_fee'];
$fine = $libraryData['fine_amount'];

?>

<?php $current = $_GET['action'] ?? ''; ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        
        <!-- Logo -->
        <a class="navbar-brand" href="#">
            <img src="<?php echo Config::indexPathMember() ?>?action=servelogo&image=<?= $logo ?>" 
                 alt="library logo" width="180" height="50">
        </a>

        <!-- LMS for Small Devices -->
        <button class="btn btn-outline-light btn-sm d-md-none" 
                onclick="window.location.href='<?php echo Config::indexPathMember() ?>?action=lmshome'">
            LMS
        </button>

        <!-- Menu Items -->
        <div class="d-flex align-items-center">
            <ul class="navbar-nav flex-row gap-4 align-items-center mb-0">
                <li class="nav-item px-2 px-md-0">
                    <a class="nav-link text-light <?= $current === 'home' ? 'active' : '' ?>" 
                       href="<?php echo Config::indexPathMember() ?>?action=home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light <?= $current === 'openhours' ? 'active' : '' ?>" 
                       href="<?php echo Config::indexPathMember() ?>?action=openhours">Opening Hours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light <?= $current === 'contact' ? 'active' : '' ?>" 
                       href="<?php echo Config::indexPathMember() ?>?action=contact">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light <?= $current === 'about' ? 'active' : '' ?>" 
                       href="<?php echo Config::indexPathMember() ?>?action=about">About</a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <button class="btn btn-outline-light btn-sm <?= $current === 'lmshome' ? 'active' : '' ?>"
                            onclick="window.location.href='<?php echo Config::indexPathMember() ?>?action=lmshome'">
                        LMS
                    </button>
                </li>
            </ul>
        </div>

    </div>
</nav>

<!-- Add the style -->
<style>
    .nav-link.active {
        text-decoration: underline;
        font-weight: bold;
    }

    .btn.active {
        border: 2px solid #fff;
    }
</style>




<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>