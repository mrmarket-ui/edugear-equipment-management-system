<?php
session_start();

if (!isset($_SESSION["employee_id"]))
{
    header("Location: login.php");
    exit();
}

include("../config/database.php");

$message = "";

// Only show available equipment
$equipment = $conn->query(
    "SELECT * FROM equipment WHERE availability_status='Available'"
);

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $student_name = trim($_POST["student_name"]);
    $student_surname = trim($_POST["student_surname"]);
    $student_number = trim($_POST["student_number"]);
    $equipment_id = $_POST["equipment_id"];
    $expected_return_date = $_POST["expected_return_date"];

    if (
        empty($student_name) ||
        empty($student_surname) ||
        empty($student_number) ||
        empty($expected_return_date)
    )
    {
        $message = "All fields are required.";
    }
    elseif (!preg_match("/^[0-9]{8}$/", $student_number))
    {
        $message = "Student number must be 8 digits.";
    }
    elseif ($expected_return_date < date("Y-m-d"))
    {
        $message = "Return date cannot be in the past.";
    }
    else
    {
        // Insert loan
        $stmt = $conn->prepare(
            "INSERT INTO loans
            (student_name, student_surname, student_number, equipment_id, loan_date, expected_return_date, loan_status)
            VALUES (?, ?, ?, ?, NOW(), ?, 'Active')"
        );

        $stmt->bind_param(
            "sssis",
            $student_name,
            $student_surname,
            $student_number,
            $equipment_id,
            $expected_return_date
        );

        if ($stmt->execute())
        {
            // Update equipment status
            $update = $conn->prepare(
                "UPDATE equipment
                 SET availability_status='Loaned Out'
                 WHERE equipment_id=?"
            );

            $update->bind_param("i", $equipment_id);
            $update->execute();

            $message = "Equipment successfully loaned.";
        }
        else
        {
            $message = "Error processing loan.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Loan Equipment</title>
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

    <h2>Loan Equipment</h2>

    <?php if (!empty($message)) { ?>
        <div class="message">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <label>Student Name</label><br>
        <input type="text" name="student_name" placeholder="John"><br><br>

        <label>Student Surname</label><br>
        <input type="text" name="student_surname" placeholder="Smith"><br><br>

        <label>Student Number</label><br>
        <input type="text" name="student_number" placeholder="8-digit number"><br><br>

        <label>Select Equipment</label><br>
        <select name="equipment_id">

            <?php while($row = $equipment->fetch_assoc()) { ?>
                <option value="<?php echo $row['equipment_id']; ?>">
                    <?php echo $row['equipment_name']; ?>
                </option>
            <?php } ?>

        </select><br><br>

        <label>Expected Return Date</label><br>
        <input type="date" name="expected_return_date"><br><br>

        <button type="submit">Issue Loan</button>

    </form>

</div>

</body>
</html>