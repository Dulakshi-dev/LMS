function loadprofileimg(profimg){

    var profileImgElement = document.getElementById("headerprofileimg");
    
    if (profimg == "") {
        profileImgElement.src = "index.php?action=serveprofimage&image=user.jpg";
    
    } else {
        profileImgElement.src = "index.php?action=serveprofimage&image=" + profimg;
    
    }
}
