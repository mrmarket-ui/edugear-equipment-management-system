<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<?php
session_start();

if (!isset($_SESSION["employee_id"]))
{
    header("Location: login.php");
    exit();
}

include("../config/database.php");

$id = $_GET["id"];

$stmt = $conn->prepare(
    "DELETE FROM equipment
     WHERE equipment_id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: view_equipment.php");
exit();
?>