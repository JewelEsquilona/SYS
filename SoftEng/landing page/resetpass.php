<?php
session_start();
include '../connection.php';

$successMessage = '';
$error = '';
$userExists = false;
$email = '';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    $requete = "SELECT * FROM users WHERE Email = ?";
    $statement = $con->prepare($requete);
    $statement->execute([$email]);
    $result = $statement->fetch();

    if ($result) {
        $userExists = true;
    } else {
        $error = "Email not found.";
    }
}

if (isset($_POST['new_pass_submit'])) {
    $newPassword = $_POST['new_password'];
    $conPassword = $_POST['con_password'];

    if ($newPassword === $conPassword) {
        $hashedPass = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE users SET Password = ? WHERE Email = ?";
        $updateStatement = $con->prepare($updateQuery);
        $updateStatement->execute([$hashedPass, $email]);
        $successMessage = "Password updated successfully!";
        $userExists = false;
    } else {
        $error = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="bg-sign-in d-flex justify-content-center align-items-center">
    <div class="form-container bg-white p-4 rounded shadow">
        <?php if (!$userExists): ?>
            <h4 class="text-center sign-in text-uppercase">Reset Password</h4>
            <p class="text-center">Please enter your email to reset your password</p>
            <!-- Reset Password Form -->
            <form method="POST" class="mt-4">
                <div class="mb-3">
                    <label for="email" class="form-label custom-font">Enter Email</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($email) ?>">
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary btn-space" name="submit">Reset Password</button>
                    <button class="btn btn-secondary btn-space" onclick="window.location.href='index.php'; return false;">Back</button>
                </div>
            </form>
        <?php else: ?>
            <h4 class="text-center sign-in text-uppercase">Change Password</h4>
            <p class="text-center">Please enter your new password below</p>
            <!-- Change Password Form -->
            <form method="POST" class="mt-4">
                <div class="mb-3">
                    <label for="new_password" class="form-label custom-font">Enter New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="con_password" class="form-label custom-font">Confirm New Password</label>
                    <input type="password" class="form-control" id="con_password" name="con_password" required>
                </div>
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary btn-space" name="new_pass_submit">Change Password</button>
                    <button class="btn btn-secondary btn-space" onclick="window.location.href='resetpass.php'; return false;">Back</button>
                </div>
            </form>
        <?php endif; ?>

        <?php if ($successMessage): ?>
            <div class="alert alert-success mt-3" role="alert">
                <?= htmlspecialchars($successMessage) ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>