<?php
$member_id = $_SESSION["member"]["member_id"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sidebar</title>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
    rel="stylesheet" />
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    rel="stylesheet" />
</head>

<body>

  <div class="d-flex">
    <!-- Sidebar -->
    <div class="d-flex flex-column bg-light vh-100 p-3" style="width: 300px;">
      <!-- Menu Items -->
      <div class="list-group flex-grow-1">
        <a href="<?php echo Config::indexPathMember() ?>?action=loadbooks" class="list-group-item list-group-item-action active">
          <i class="fas fa-home m-2"></i> Dashboard
        </a>
        <a href="<?php echo Config::indexPathMember() ?>?action=profile" class="list-group-item list-group-item-action">
          <i class="fas fa-user m-2"></i> My Profile
        </a>
        <a href="#" class="list-group-item list-group-item-action">
          <i class="fas fa-book m-2"></i> My Library
        </a>
        <a href="<?php echo Config::indexPathMember() ?>?action=loadissuebooks&member_id=<?php echo $member_id ?>" class="list-group-item list-group-item-action">
          <i class="fas fa-list-alt m-2"></i> Borrow History
        </a>
        <a href="<?php echo Config::indexPathMember() ?>?action=reservedbooks" class="list-group-item list-group-item-action">
          <i class="fas fa-eye m-2"></i> Reserved Books
        </a>
        <a href="#" class="list-group-item list-group-item-action">
          <i class="fas fa-info-circle m-2"></i> About Software
        </a>
      </div>

      <!-- User Section -->
      <div class="mt-auto text-center">
        <div class="d-flex align-items-center">
          <img
            src="<?php echo Config::getImagePath("user.jpg"); ?>"
            alt="User"
            class="rounded-circle me-2"
            style="height: 40px; width: 40px;" />
          <p class="mb-0">User Name</p>
        </div>
        <div class="d-flex"><a href="#" class="btn btn-outline-dark btn-sm mt-4 w-50 d-flex align-items-center">
            <i class="fas fa-sign-out-alt mx-2"></i>
            Log Out
          </a></div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>