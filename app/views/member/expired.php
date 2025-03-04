<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Expired</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background-image: url('../../../public/images/stafflog.jpg'); /* Replace with your actual image path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .glassmorphism-card {
            background: rgba(0, 0, 0, 0.4); 
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            max-width: 600px;
            color: white;
        }
        .danger{
            color: red;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="glassmorphism-card text-center shadow-lg p-5">
        <h2 class="danger fw-bold"><i class="fas fa-exclamation-circle"></i> Your library membership has expired!</h2>
        <p class="text-white m-4">Please renew your membership for <strong>LKR 1000.</strong></p>
        <button class="btn btn-primary text-white rounded-pill w-50">Proceed to Payment</button>
    </div>
</body>
</html>
