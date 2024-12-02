<?php
session_start();
include '../connection.php';

if (isset($_POST['login_submit'])) {
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $uniqueNumber = $_POST['unique_number'];

    // Prepare the SQL query to fetch user details
    $requete = "SELECT * FROM users WHERE email = :email AND unique_number = :unique_number";
    $statment = $con->prepare($requete);
    $statment->bindParam(':email', $email);
    $statment->bindParam(':unique_number', $uniqueNumber);
    $statment->execute();
    $result = $statment->fetch();

    // Verify password
    if ($result && password_verify($password, $result['password'])) {
        $_SESSION['name'] = $result['username'];
        $_SESSION['email'] = $result['email'];
        $_SESSION['role'] = $result['role']; // Store user role
        $_SESSION['college'] = $result['college'] ?? null; // For Dean
        $_SESSION['department'] = $result['department'] ?? null; // For Program Chair

        // Set session for user privileges
        setUserSession($result); // Call the function to set user session

        header("location:../dashboard/home.php");
        exit();
    } else if (empty($email) || empty($password) || empty($uniqueNumber)) {
        $error = "Please enter your email, password, and unique number.";
    } else {
        $error = "Email, password, or unique number not found.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Alumni System</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <main class="bg-sign-in d-flex justify-content-center align-items-center">
        <div class="form-sign-in bg-white mt-2 h-auto mb-2 text-center pt-2 pe-4 ps-4 d-flex flex-column">
            <h3 class="sign-in text-uppercase">Sign In</h3>
            <p>Enter your credentials to access your account</p>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3 mt-3 text-start">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required autocomplete="email">
                </div>
                <div class="mb-3 text-start">
                    <label for="pwd">Password:</label>
                    <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pass" required autocomplete="current-password">
                </div>
                <div class="mb-3 text-start">
                    <label for="unique_number">Unique Number:</label>
                    <input type="text" class="form-control" id="unique_number" placeholder="Enter unique number" name="unique_number" required>
                </div>
                <div class="mb-3 form-check d-flex gap-2">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="check">
                    <label class="form-check-label" for="exampleCheck1">Remember Me</label>
                </div>
                <button type="submit" name="login_submit" class="btn text-white w-100 text-uppercase">Sign In</button>
                <p class="mt-4">Forgot your password? <a href="resetpass.php">Reset Password</a></p>
                <button type="button" class="btn btn-success mb-3" onclick="window.location.href='signup.php';">Create Account</button>
            </form>
        </div>
    </main>
    <script src="../assets/js/bootstrap.bundle.js"></script>
    <script src="../assets/js/validation.js"></script>
</body>

</html>