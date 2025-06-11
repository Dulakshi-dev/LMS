<?php
// logs_viewer.php
require_once __DIR__ . '/../../../main.php';
$logDir = Config::getLogsPath();

$files = array_filter(scandir($logDir), function ($file) {
    return strpos($file, 'library') !== false;
});
rsort($files); // newest first

// Select which file to view (default latest)
$fileToView = $_GET['file'] ?? $files[0] ?? null;
$logs = [];

if ($fileToView && file_exists("$logDir/$fileToView")) {
    $logs = file("$logDir/$fileToView");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Library System Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="bg-light text-dark d-flex flex-column min-vh-100">
    <?php require_once Config::getViewPath("staff", "dash_header.php"); ?>

    <div class="d-flex flex-grow-1">
        <div>
            <div class="h-100 d-none d-lg-block">
                <?php include "dash_sidepanel.php"; ?>
            </div>
            <div class="h-100 d-block d-lg-none">
                <?php include "small_sidepanel.php"; ?>
            </div>
        </div>

        <main class="container-fluid px-4 py-4">
            <h2 class="mb-4">Library System Logs</h2>

            <form method="get" class="mb-3" style="max-width: 400px;">
                <label for="file" class="form-label fw-semibold">Select Log File:</label>
                <select id="file" name="file" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($files as $file): ?>
                        <option value="<?= htmlspecialchars($file) ?>" <?= $file === $fileToView ? 'selected' : '' ?>>
                            <?= htmlspecialchars($file) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <div class="bg-secondary rounded p-3" style="max-height: 600px; overflow-y: auto;">
                <pre class="mb-0 text-light" style="font-family: monospace; white-space: pre-wrap;">
<?= htmlspecialchars(implode("", $logs)) ?>
                </pre>
            </div>
        </main>
    </div>

    <?php require_once Config::getViewPath("staff", "footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
