<?php
session_start();
include '../connection.php';
include 'utils.php'; // Include the utility file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = 'Dean';
    $college = $_POST['College']; // Assuming this comes from your form

    // Generate Unique Dean ID
    $deanID = generateUniqueID($role, $college);

    // Insert into the database
    $stmt = $con->prepare("INSERT INTO users (unique_number, role, college) VALUES (:unique_number, :role, :college)");
    $stmt->execute([':unique_number' => $deanID, ':role' => $role, ':college' => $college]);
}
?>
