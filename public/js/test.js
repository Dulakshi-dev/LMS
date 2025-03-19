function loadprofileimg(profimg){

    var profileImgElement = document.getElementById("headerprofileimg");
    
    if (profimg == "") {
        profileImgElement.src = "index.php?action=serveprofimage&image=user.jpg";
    
    } else {
        profileImgElement.src = "index.php?action=serveprofimage&image=" + profimg;
    
    }
}


function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}
