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

<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-body m-0">
    <div class="container-fluid">
        <a class="navbar-brand m-0" href="#">
            <img src="<?php echo Config::indexPathMember() ?>?action=servelogo&image=<?= $logo ?>" alt="library logo" width="200" height="60">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $_GET['action'] == 'home' ? 'text-white fw-bold' : '' ?>" id="li" href="<?php echo Config::indexPathMember() ?>?action=home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $_GET['action'] == 'openhours' ? 'text-white fw-bold' : '' ?>" id="li" href="<?php echo Config::indexPathMember() ?>?action=openhours">Opening Hours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $_GET['action'] == 'contact' ? 'text-white fw-bold' : '' ?>" id="li" href="<?php echo Config::indexPathMember() ?>?action=contact">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $_GET['action'] == 'about' ? 'text-white fw-bold' : '' ?>" id="li" href="<?php echo Config::indexPathMember() ?>?action=about">About</a>
                </li>
                <li class="nav-item">
                    <button type="button" id="li" class="btn btn-outline-light <?= $_GET['action'] == 'lmshome' ? 'text-black bg-white fw-bold' : '' ?>"
                        onclick="window.location.href='<?php echo Config::indexPathMember() ?>?action=lmshome'">
                        LMS
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>