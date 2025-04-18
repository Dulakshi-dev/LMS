
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . " - Library Management System" : "Library Management System"; ?></title>
    <link rel="icon" href="/LMS/public/images/book_icon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php if (isset($pageCss)): ?>
        <link rel="stylesheet" href="<?php echo Config::getCssPath($pageCss); ?>">
    <?php endif; ?>
</head>
