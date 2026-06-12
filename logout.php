<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<?php
session_start();
include("../config/database.php");

// Update logout time if session exists
if (isset($_SESSION["login_id"]))
{
    $stmt = $conn->prepare(
        "UPDATE login_history
         SET logout_time = NOW()
         WHERE login_id = ?"
    );

    $stmt->bind_param("i", $_SESSION["login_id"]);
    $stmt->execute();
}

// Clear session completely
$_SESSION = [];
session_unset();
session_destroy();

// Redirect immediately
header("Location: login.php");
exit();
?>