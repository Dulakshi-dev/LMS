<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #li{
            color: white;
            transition: 0.3s;
            border: 2px solid transparent;
        }
        #li:hover{
            color: violet;
            border-bottom-color: violet;
            border-top-color: violet;
        
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-body m-0">
        <div class="container-fluid">
            <a class="navbar-brand m-0" href="#">
                <img src="../../../public/images/logo.png" alt="library logo" width="80" height="60">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav ms-auto gap-5">
                    <li class="nav-item">
                        <a class="nav-link" id="li" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="li" href="opening-hours.php">Opening Hours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="li" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="li" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <button type="button" id="li" class="btn btn-outline-light"
                            onclick="window.location.href='lms-home.php'">LMS</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>

</html>
