<?php
include '../connection.php';

if (isset($_GET['department'])) {
    $department = $_GET['department'];
    $stmt = $con->prepare("SELECT DISTINCT section FROM courses WHERE department = ?");
    $stmt->execute([$department]);
    $sections = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($sections);
}
?>
