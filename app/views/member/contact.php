<?php

// Required !
require_once "../main.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <title>Contact Us Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
<style>
     @import url('https://fonts.goog1eapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap');
body{
    overflow-x: hidden;
}

.contact{
    position: relative;
    min-height: 100vh;
    padding: 50px 100px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    background: url('images/contactbg.png') no-repeat center center/cover;
    background-size: cover;
}

.contact .content h1{
    font-size: 50px;
    font-weight: 500;
    color: #000;
} */
.contact .content p{
    
    font-weight: 300;
    color: #fff;
}
.container{
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 30px;
    padding: 20px;
}
.container .contactInfo{
    width: 50%;
    display: flex;
    flex-direction: column;
    padding: 0 15px;
    right: 4cm;
    backdrop-filter: blur(10px);
    margin-right: 50px;
    border-radius: 20px;
}
.container .contactInfo .box{
    position: relative;
    padding: 20px 0;
    display: flex;
    padding-right: 40px;
}
.container .contactInfo .box .icon{
    min-width: 60px;
    height: 60px;
    background: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    font-size: 22px;
}
.container .contactInfo .box .text{
    display: flex;
    margin-left: 20px;
    font-size: 20px;
    color: #000;
    flex-direction: column;
    font-weight: 300;
}
.container .contactInfo .box .text h3{
    font-weight: 650;
    color: #000;
}
.contactForm{
    width: 40%;
    padding: 40px;
    background: #fff;
    padding: 15px;
    backdrop-filter: blur(10px);
    margin-left: 50px;
    border-radius: 20px;
}
.contactForm h2{
    font-size: 30px;
    color: #333;
    font-weight: 500;
}
.contactForm .inputBox{
    position: relative;
    width: 100%;
    margin-top: 10px;
}
.contactForm .inputBox input,
.contactForm .inputBox textarea
{
    width: 100%;
    padding: 5px 0;
    font-size: 16px;
    margin: 10px 0;
    border: none;
    border-bottom: 2px solid #333;
    outline: none;
    resize: none;
}
.contactForm .inputBox span{
    position: absolute;
    left: 0;
    padding: 5px 0;
    font-size: 16px;
    margin: 10px 0;
    pointer-events: none;
    transition: 0.5s;
    color: #666;
}
.contactForm .inputBox input:focus ~ span,
.contactForm .inputBox input:valid ~ span,
.contactForm .inputBox textarea:focus ~ span,
.contactForm .inputBox textarea:valid ~ span
{
    color: #e91e63;
    font-size: 12px;
    transform: translateY(-20px);
}
.contactForm .inputBox input[type="submit"]{
    width: 100px;
    background: #00bcd4;
    color: #fff;
    border: none;
    cursor: pointer;
    padding: 10px;
    font-size: 18px;
}

@media (max-width: 991px)
{
    .contact{
        padding: 50px;
    }
    .container{
        flex-direction: column;
    }
    .container .contactInfo{
        margin-bottom: 40px;
    }
    .container .contactInfo, 
    .contactForm{
        width: 100%;
    }
}
</style>

<body>

<?php
include "header.php";
?>

<section class="contact">
        <div class="content">
            <h1>Contact Us</h1>
            <p></p>
        </div>
        
        <div class="container">

            <div class="contactInfo">
                <div class="box">
                    <div class="icon"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                    <div class="text">
                        <h3>Address</h3>
                        <p>432/1, Nidahasmawatha, Yanthampalawa,Kurunegala</p>
                    </div>
                </div>
                <div class="box">
                    <div class="icon"><i class="fa fa-phone" aria-hidden="true"></i></div>
                    <div class="text">
                        <h3>Phone</h3>
                        <p>0702319145</p>
                    </div>
                </div>
                <div class="box">
                    <div class="icon"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                    <div class="text">
                        <h3>Email</h3>
                        <p>yehanwickramasinghe@yahoo.com</p>
                    </div>
                </div>
            </div>
            
            <div class="contactForm ">
                <form>
                    <h2>Send Message</h2>
                    <div class="inputBox">
                        <input type="text" name="" required="required">
                        <span>Full Name</span>
                    </div>
                    <div class="inputBox">
                        <input type="text" name="" required="required">
                        <span>Email</span>
                    </div>
                    <div class="inputBox">
                        <textarea required="required"></textarea>
                        <span>Type Your Message...</span>
                    </div>
                    <div class="inputBox">
                        <input type="submit" name="" value="Send">
                    </div>
                </form>
            </div>
           
        </div>
    </section>

    <?php
        include "footer.php";
    ?>
</body>


</html>