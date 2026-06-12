<?php
session_start();

if (!isset($_SESSION["employee_id"]))
{
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="add_equipment.php">Add Equipment</a>
    <a href="view_equipment.php">View Equipment</a>
    <a href="loan_equipment.php">Loan Equipment</a>
    <a href="return_equipment.php">Return Equipment</a>
    <a href="summary.php">Summary</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">

    <h1>EduGear Dashboard</h1>

    <h3>Welcome, <?php echo $_SESSION["employee_name"]; ?></h3>

    <div class="message">
        Select an option from the menu to manage equipment and loans.
    </div>

</div>

</body>
</html>