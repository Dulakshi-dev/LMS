

<!DOCTYPE html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale = 1.0">
    <link rel="stylesheet" href="bootstrap.css" />
    <link rel="stylesheet" href="home.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Home</title>
    <style>

.hero {
    background: url('images/home.jpg') no-repeat center center/cover;
    height: 500px;
  }
  

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        header {
            color: #fff;
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logoimg {
            height: 50px;
            margin-right: 10px;
        }
        
        header nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
            font-size: 20px;
        }
        
        header nav ul li {
            margin-left: 30px;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        
        header nav ul li a {
            color: #fff;
            text-decoration: none;
        }


        .link1{
            font: bold ;
            color: #fff;
            
        }

        .link1:hover {
            color: #ff0000;
        }
        
        header #menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
        }

        footer {
            /* background-color: rgb(31, 31, 31); */
            padding-bottom: 5px;
            padding-left: 20px;
            padding-right: 20px;
        
            left: 0;
            width: 100%;
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
            
        }
        
        footer .footer-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            color: #fff;
        }
        
        footer p {
            margin: 0;
            flex: 1;
            text-align: left;
        }
        
        footer .top-button {
            flex: 1;
            text-align: center;
        }
        
        
        footer .top-button a {
            /* background-color: #000000; */
            color: #fff;
            text-decoration: none;
            border: #fff solid 3px;
            border-radius: 5px;
            padding: 8px 20px;  
        }
        
        footer .top-button a:hover {
            background-color: white;
            color: #000000;
        }
        
        footer .social-icons {
            display: flex;
            gap: 20px;
            flex: 0;
            justify-content: flex-end;
        }
        
        footer .social-icons a {
            color: #ffffff;
            text-decoration: none;
            font-size: 24px;
        }
        
        footer .social-icons a:hover {
            color: #ff0000;
        }
        
        .footer-container {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 5px; 
        }
        .footer-container::before,
        .footer-container::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #fff;
            border-width: 3px; 
        }
        .footer-container::before {
            margin-right: 10px;
        }
        .footer-container::after {
            margin-left: 10px;
        }


    </style>
</head>
<body>
    <section class="hero">
        <h1>Welcome to</h1>
    </section>

    <div class="our-goal-section">
        <h2 class="ms-5 fw-bold">Our Goal</h2>
        <div class="row our-goal-images">
            <div class="col-5">
                <p>At ShelfLoom, our goal is to create a user-friendly and efficient platform that simplifies library management and enhances the experience of accessing our vast collection of resources...</p>
            </div>
            <img src="images/home-goal.jpg" alt="Test Image">

        </div>

        <div class="row our-goal-images"> 
            <img src="images/home-goal2.jpg" alt="Library Image 2" class="col-5 rounded-5">
            <div class="col-5">
                <p>At ShelfLoom, our goal is to create a user-friendly and efficient platform...</p>
            </div>
        </div>
    </div>

    <section class="last-news-update">
        <h2>Latest News and Updates</h2>
        <div class="news-container d-lg-flex">
            <div class="news-item col-12 col-lg-4">
                <div class="news-date">NOV 16</div>
                <img src="images/home-goal.jpg" alt="News Image 1">
                <p>Text here</p>
            </div>
            <div class="news-item col-12 col-lg-4">
                <div class="news-date">NOV 16</div>
                <img src="images/home-goal.jpg" alt="News Image 2">
                <p>Text here</p>
            </div>
            <div class="news-item col-12 col-lg-4">
                <div class="news-date">NOV 16</div>
                <img src="images/home-goal.jpg" alt="News Image 3">
                <p>Text here</p>
            </div>
        </div>
    </section>
</body>
