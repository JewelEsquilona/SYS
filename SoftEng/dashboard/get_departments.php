<?php
include '../connection.php'; 

if (isset($_GET['college'])) {
    $college = $_GET['college'];
    $query = "SELECT DISTINCT department FROM courses WHERE college = :college";
    $statement = $con->prepare($query);
    $statement->bindParam(':college', $college);
    $statement->execute();

    $departments = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($departments);
}
?>
