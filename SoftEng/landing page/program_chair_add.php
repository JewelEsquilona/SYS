<?php
session_start();
include '../connection.php';
include 'utils.php'; // Include the utility file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = 'Program Chair';
    $department = $_POST['Department']; // Assuming this comes from your form

    // Generate Unique Program Chair ID
    $programChairID = generateUniqueID($role, null, $department);

    // Insert into the database
    $stmt = $con->prepare("INSERT INTO users (unique_number, role, department) VALUES (:unique_number, :role, :department)");
    $stmt->execute([':unique_number' => $programChairID, ':role' => $role, ':department' => $department]);
}
?>
