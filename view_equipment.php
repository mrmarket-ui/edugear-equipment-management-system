<?php
session_start();

if (!isset($_SESSION["employee_id"]))
{
    header("Location: login.php");
    exit();
}

include("../config/database.php");

$result = $conn->query("SELECT * FROM equipment");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Equipment</title>
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

    <h2>Equipment Inventory</h2>

    <table>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Condition</th>
            <th>Status</th>
            <th>Value (R)</th>
            <th>Actions</th>
        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>

        <tr>
            <td><?php echo $row["equipment_id"]; ?></td>
            <td><?php echo $row["equipment_name"]; ?></td>
            <td><?php echo $row["equipment_category"]; ?></td>
            <td><?php echo $row["equipment_condition"]; ?></td>

            <td>
                <?php
                if ($row["availability_status"] == "Available") {
                    echo "<span style='color:green;'>Available</span>";
                } elseif ($row["availability_status"] == "Loaned Out") {
                    echo "<span style='color:orange;'>Loaned Out</span>";
                } else {
                    echo "<span style='color:red;'>Unavailable</span>";
                }
                ?>
            </td>

            <td>R <?php echo number_format($row["replacement_value"], 2); ?></td>

            <td>
                <a href="edit_equipment.php?id=<?php echo $row['equipment_id']; ?>">
                    Edit
                </a>
                |
                <a href="delete_equipment.php?id=<?php echo $row['equipment_id']; ?>"
                   onclick="return confirm('Are you sure you want to delete this item?')">
                    Delete
                </a>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>