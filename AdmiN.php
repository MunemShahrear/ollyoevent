<?php
session_start();
include("database/db.php");
include('function/functions.php');
$currentDate = date("Y");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="style/style.css"> <!-- You can keep your custom styles -->
</head>
<body>

<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="text-center">
               
            </div>

            <div class="card mt-4">
                <div class="card-body">

                    <h3 class="card-title text-center">Login As Admin</h3>

                    <form action="admin/Admin_Area.php" method="POST">
                        <div class="form-group">
                            <label for="user_email">User Email:</label>
                            <input type="text" class="form-control" id="user_email" placeholder="Type Your Email" name="name">
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" placeholder="Type Your Password" name="password">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="text-muted text-center mt-3">
            <small>&copy; <?php echo $currentDate; ?> - All rights reserved by Himel</small>
            </div>

        </div>
    </div>

</div>

</body>
</html>
