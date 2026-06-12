<?php
session_start();

if (!isset($_SESSION["employee_id"]))
{
    header("Location: login.php");
    exit();
}

include("../config/database.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $equipment_name = trim($_POST["equipment_name"]);
    $equipment_category = $_POST["equipment_category"];
    $equipment_condition = $_POST["equipment_condition"];
    $availability_status = $_POST["availability_status"];
    $replacement_value = $_POST["replacement_value"];

    if (empty($equipment_name))
    {
        $message = "Equipment name is required.";
    }
    elseif (!is_numeric($replacement_value) || $replacement_value <= 0)
    {
        $message = "Replacement value must be greater than zero.";
    }
    else
    {
        $stmt = $conn->prepare(
            "INSERT INTO equipment
            (equipment_name, equipment_category, equipment_condition, availability_status, replacement_value)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssd",
            $equipment_name,
            $equipment_category,
            $equipment_condition,
            $availability_status,
            $replacement_value
        );

        if ($stmt->execute())
        {
            $message = "Equipment added successfully.";
        }
        else
        {
            $message = "Error adding equipment.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Equipment</title>
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

    <h2>Add New Equipment</h2>

    <?php if (!empty($message)) { ?>
        <div class="message">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <label>Equipment Name</label><br>
        <input type="text" name="equipment_name" placeholder="e.g. Dell Laptop"><br><br>

        <label>Category</label><br>
        <select name="equipment_category">
            <option>Laptop</option>
            <option>Projector</option>
            <option>Tablet</option>
            <option>Camera</option>
            <option>Microphone</option>
            <option>Speciality Equipment</option>
        </select><br><br>

        <label>Condition</label><br>
        <select name="equipment_condition">
            <option>New</option>
            <option>Good</option>
            <option>Fair</option>
            <option>Damaged</option>
        </select><br><br>

        <label>Availability</label><br>
        <select name="availability_status">
            <option>Available</option>
            <option>Loaned Out</option>
            <option>Unavailable</option>
        </select><br><br>

        <label>Replacement Value (R)</label><br>
        <input type="number" step="0.01" name="replacement_value" placeholder="e.g. 15000"><br><br>

        <button type="submit">Add Equipment</button>

    </form>

</div>

</body>
</html>