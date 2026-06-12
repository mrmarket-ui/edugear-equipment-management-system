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

$message = "";

// Get active loans
$loans = $conn->query(
"SELECT 
    loans.loan_id,
    loans.student_name,
    loans.student_surname,
    loans.equipment_id,
    equipment.equipment_name
FROM loans
INNER JOIN equipment 
ON loans.equipment_id = equipment.equipment_id
WHERE loans.loan_status = 'Active'"
);

if (isset($_POST["return_loan"]))
{
    $loan_id = $_POST["loan_id"];
    $equipment_id = $_POST["equipment_id"];
    $condition = $_POST["condition"];

    // Update loan
    $stmt = $conn->prepare(
        "UPDATE loans
         SET loan_status='Returned',
             return_date=NOW()
         WHERE loan_id=?"
    );

    $stmt->bind_param("i", $loan_id);
    $stmt->execute();

    // Update equipment
    $stmt2 = $conn->prepare(
        "UPDATE equipment
         SET availability_status='Available',
             equipment_condition=?
         WHERE equipment_id=?"
    );

    $stmt2->bind_param("si", $condition, $equipment_id);
    $stmt2->execute();

    header("Location: return_equipment.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Equipment</title>
</head>
<body>

<h2>Return Equipment</h2>

<table border="1" cellpadding="10">

<tr>
    <th>Student</th>
    <th>Equipment</th>
    <th>Action</th>
</tr>

<?php while ($row = $loans->fetch_assoc()) { ?>

<tr>
    <td>
        <?php echo $row["student_name"] . " " . $row["student_surname"]; ?>
    </td>

    <td>
        <?php echo $row["equipment_name"]; ?>
    </td>

    <td>
        <form method="POST">

            <input type="hidden" name="loan_id"
                   value="<?php echo $row['loan_id']; ?>">

            <input type="hidden" name="equipment_id"
                   value="<?php echo $row['equipment_id']; ?>">

            <select name="condition" required>
                <option>New</option>
                <option>Good</option>
                <option>Fair</option>
                <option>Damaged</option>
            </select>

            <button type="submit" name="return_loan">
                Return
            </button>

        </form>
    </td>

</tr>

<?php } ?>

</table>

<br>

<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>