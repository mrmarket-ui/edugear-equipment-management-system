<?php
session_start();

include("../config/database.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = trim($_POST["name"]);
    $employee_number = trim($_POST["employee_number"]);

    if (empty($name) || empty($employee_number))
    {
        $message = "Please fill in all fields.";
    }
    else
    {
        $stmt = $conn->prepare(
            "SELECT * FROM employees
             WHERE name = ?
             AND employee_number = ?"
        );

        $stmt->bind_param("ss", $name, $employee_number);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1)
        {
            $employee = $result->fetch_assoc();

            $_SESSION["employee_id"] =
                $employee["employee_id"];

            $_SESSION["employee_name"] =
                $employee["name"];

            $loginStmt = $conn->prepare(
                "INSERT INTO login_history
                (employee_id, login_time)
                VALUES (?, NOW())"
            );

            $loginStmt->bind_param(
                "i",
                $employee["employee_id"]
            );

            $loginStmt->execute();

            $_SESSION["login_id"] =
                $conn->insert_id;

            header("Location: dashboard.php");
            exit();
        }
        else
        {
            $message = "Invalid login details.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Login</title>
</head>
<body>

<h2>Employee Login</h2>

<p style="color:red;">
    <?php echo $message; ?>
</p>

<form method="POST">

    <label>Name</label><br>
    <input type="text" name="name"><br><br>

    <label>Employee Number</label><br>
    <input type="text" name="employee_number"><br><br>

    <input type="submit" value="Login">

</form>

<br>

<a href="register.php">Register Employee</a>

</body>
</html>