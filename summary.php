<?php
session_start();

if (!isset($_SESSION["employee_id"]))
{
    header("Location: login.php");
    exit();
}

include("../config/database.php");

// Loans today
$loansToday = $conn->query(
    "SELECT COUNT(*) AS total FROM loans
     WHERE DATE(loan_date) = CURDATE()"
)->fetch_assoc();

// Returns today
$returnsToday = $conn->query(
    "SELECT COUNT(*) AS total FROM loans
     WHERE DATE(return_date) = CURDATE()"
)->fetch_assoc();

// Active loans
$activeLoans = $conn->query(
    "SELECT COUNT(*) AS total FROM loans
     WHERE loan_status='Active'"
)->fetch_assoc();

// Overdue loans
$overdueLoans = $conn->query(
    "SELECT COUNT(*) AS total FROM loans
     WHERE loan_status='Overdue'"
)->fetch_assoc();

// Total value of active loans
$totalValue = $conn->query(
    "SELECT SUM(e.replacement_value) AS total
     FROM equipment e
     INNER JOIN loans l
     ON e.equipment_id = l.equipment_id
     WHERE l.loan_status='Active'"
)->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daily Summary</title>
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

    <h2>Daily Loan Summary</h2>

    <table>

        <tr>
            <th>Metric</th>
            <th>Value</th>
        </tr>

        <tr>
            <td>Loans Today</td>
            <td><?php echo $loansToday["total"]; ?></td>
        </tr>

        <tr>
            <td>Returns Today</td>
            <td><?php echo $returnsToday["total"]; ?></td>
        </tr>

        <tr>
            <td>Active Loans</td>
            <td><?php echo $activeLoans["total"]; ?></td>
        </tr>

        <tr>
            <td>Overdue Loans</td>
            <td><?php echo $overdueLoans["total"]; ?></td>
        </tr>

        <tr>
            <td>Total Replacement Value</td>
            <td>R <?php echo number_format($totalValue["total"] ?? 0, 2); ?></td>
        </tr>

    </table>

</div>

</body>
</html>