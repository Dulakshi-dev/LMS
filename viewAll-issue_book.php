
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
   

    <div class="d-flex">
        <div class="container-fluid">
        
            <div class="row">
                <div class="col-md-6 my-3">
                    <input id="bname" type="text" class="form-control" placeholder="Type Book Name">
                </div>
                <div class="col-md-6 d-flex my-3">
                    <input id="isbn" type="text" class="form-control mx-3" placeholder="Type ISBN">
                    <button class="btn btn-primary ml-3 px-4" onclick="searchBook();"><i class="fa fa-search px-2"></i></button>
                </div>
            </div>
   
            <div class="px-1" id="content">
                <!-- Content will be loaded here from loadUser function -->
            </div>
        </div>
    </div>

    <!-- Bootstrap and JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="path_to_your_js_file.js"></script> <!-- Add this if you have a separate JavaScript file -->

</body>
</html>

 