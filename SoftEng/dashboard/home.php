<?php
include 'user_privileges.php'; 
checkLogin();
renderSidebar($_SESSION['user_role']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to the System</h1>
        <!-- Main content goes here -->
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" crossorigin="anonymous" />
</head>
<body class="bg-content">
    <main class="dashboard d-flex">
        <?php 
        include "component/sidebar.php";
        include '../connection.php'; 
        $nbr_alumni = $con->query("SELECT * FROM `2024-2025`")->rowCount();
        $nbr_courses = $con->query("SELECT * FROM courses")->rowCount();
        
        ?>
        <div class="container-fluid px">
            <?php include "component/header.php"; ?>
            <div class="cards row gap-3 justify-content-center mt-5">
                <div class="card__items card__items--blue col-md-3 position-relative">
                    <div class="card__alumni d-flex flex-column gap-2 mt-3">
                        <i class="far fa-graduation-cap h3"></i>
                        <span>Alumni</span>
                    </div>
                    <div class="card__nbr-alumni">
                        <span class="h5 fw-bold nbr"><?php echo $nbr_alumni; ?></span>
                    </div>
                </div>
                <div class="card__items card__items--rose col-md-3 position-relative">
                    <div class="card__Course d-flex flex-column gap-2 mt-3">
                        <i class="fal fa-bookmark h3"></i>
                        <span>Course</span>
                    </div>
                    <div class="card__nbr-course">
                        <span class="h5 fw-bold nbr"><?php echo $nbr_courses; ?></span>
                    </div>
                </div>
                <div class="card__items card__items--yellow col-md-3 position-relative">
                    <div class="card__payments d-flex flex-column gap-2 mt-3">
                        <i class="fal fa-usd-square h3"></i>
                        <span>Staffs</span>
                    </div>
                    <div class="card__payments">
                        <span class="h5 fw-bold nbr">3</span>
                    </div>
                </div>
                <div class="card__items card__items--gradient col-md-3 position-relative">
                    <div class="card__users d-flex flex-column gap-2 mt-3">
                        <i class="fal fa-user h3"></i>
                        <span>Users</span>
                    </div>
                    <span class="h5 fw-bold nbr">2</span>
                </div>
            </div>
        </div>
    </main>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/bootstrap.bundle.js"></script>
</body>
</html>
