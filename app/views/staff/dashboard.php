<?php
 

// Session Timeout (30 minutes)
if (isset($_SESSION['staff']['last_activity']) && (time() - $_SESSION['staff']['last_activity'] > 1800)) {
    session_unset();  // Clear session data
    session_destroy();
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['staff'])) {
    header("Location: index.php");
    exit;
}

$_SESSION['staff']['last_activity'] = time();

?>

<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Dashboard";
$pageCss = "staff-dashboard.css";
require_once Config::getViewPath("common", "head.php");
?>

<body>

    <?php include 'dash_header.php'; ?>

    <div class="d-flex min-vh-100">

        <!-- Side Panel -->
        <div class="bg text-white d-flex flex-column d-none d-lg-block">
            <?php include "dash_sidepanel.php"; ?>
        </div>

        <div class="bg text-white d-flex flex-column d-block d-lg-none">
            <?php include "small_sidepanel.php"; ?>
        </div>

        <!-- Main Content -->
        <div class="bg-white flex-grow-1">
            <nav class="navbar p-4 navbar-light bg-light">
                <span class="navbar-brand mb-0 h1">Dashboard</span>
                <a href="#" class="text-decoration-none h5"><i class="fa fa-home"></i></a>
            </nav>

            <div class="mt-3 p-3">
                <!-- Stat Boxes -->
                <div class="row d-flex justify-content-center">
                    <?php
                    $items = [
                        ['id' => 'books', 'label' => 'Books', 'icon' => 'fa-book-open'],
                        ['id' => 'members', 'label' => 'Members', 'icon' => 'fa-users'],
                        ['id' => 'issuedBooks', 'label' => 'Issued Books', 'icon' => 'fa-share-square'],
                        ['id' => 'reservations', 'label' => 'Reservations', 'icon' => 'fa-calendar-check'],
                        ['id' => 'totalfines', 'label' => 'Fines', 'icon' => 'fa-coins'],
                    ];

                    foreach ($items as $item) {
                        echo <<<HTML
                        <div class="col-lg-2 col-md-6">
                            <div class="d-flex justify-content-between m-1 p-2 box-1 rounded">
                                <div class="ms-4">
                                    <h1 class="text-info" id="{$item['id']}"></h1>
                                    <p class="text-info">{$item['label']}</p>
                                </div>
                                <div>
                                    <i class="fa {$item['icon']} text-info" style="font-size: 30px;"></i>
                                </div>
                            </div>
                        </div>
                        HTML;
                    }
                    ?>
                </div>

                <!-- Charts Section 1 -->
                <div class="row gap-5 my-3 p-2 align-items-center justify-content-center">
                    <div class="col-md-6 col-sm-12 bg-light rounded-5">
                        <div class="mt-4 p-3 d-flex justify-content-center">
                            <canvas id="lineChart" class="w-100 h-auto"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 bg-light rounded-5">
                        <div class="mt-4 p-3 d-flex justify-content-center">
                            <canvas id="pieChart" class="w-100 h-auto"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Choices -->
                <div class="row mt-5 justify-content-center">
                    <div class="col-12 text-center">
                        <h3>Top choices</h3>
                    </div>
                    <div class="col-md-12">
                        <div class="row justify-content-center" id="topBookBody">
                            <!-- JS will insert content here -->
                        </div>
                    </div>
                </div>

                <!-- Charts Section 2 -->
                <div class="row gap-5 p-2 my-5 align-items-center justify-content-center">
                    <div class="col-md-6 col-sm-12 bg-light rounded-5">
                        <div class="mt-4 p-3 d-flex justify-content-center">
                            <canvas id="barChart" class="w-100 h-auto"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 bg-light rounded-5">
                        <div class="mt-4 p-3 d-flex justify-content-center">
                            <canvas id="polarChart" class="w-100 h-auto"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php require_once Config::getViewPath("common", "stafffoot.php"); ?>

    <script src="<?php echo Config::getJsPath("staffDashboard.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>