function staffLogin() {
    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value.trim();
    var errorMsgDiv = document.getElementById('errormsgdiv');
    var errorMsg = document.getElementById('errormsg');

    errorMsg.innerText = '';
    errorMsgDiv.style.display = 'none';  

    if (username === '') {
        errorMsg.innerText = 'Username is required.';
        errorMsgDiv.style.display = 'block';  
        return false;  
    } else if (password === '') {
        errorMsg.innerText = 'Password is required.';
        errorMsgDiv.style.display = 'block';  
        return false;  
    }

    return true;
}
